<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Google\Cloud\Firestore\FirestoreClient; // Importación directa para mayor claridad

class LoginController extends Controller
{
    /**
     * Crea la conexión con Firestore limpiando las credenciales de Railway.
     */
    private function getFirestore()
    {
        $json = env('FIREBASE_CREDENTIALS_JSON');
        $credentials = json_decode($json, true);

        // 💡 PARCHE MAESTRO: Convierte los escapes de Railway en saltos de línea reales.
        if (isset($credentials['private_key'])) {
            $credentials['private_key'] = str_replace(['\\n', "\n"], "\n", $credentials['private_key']);
        }

        return new FirestoreClient([
            'projectId' => $credentials['project_id'] ?? 'proyectointegrador-43071',
            'keyFile'   => $credentials,
            'transport' => 'rest', // 🚀 Evita el error de "Maximum stack depth" y gRPC
        ]);
    }

    /**
     * Procesa el inicio de sesión.
     */
    public function login(Request $request)
    {
        $request->validate([
            'phone'    => 'required|string',
            'password' => 'required|string',
        ]);

        try {
            // Limpiamos el teléfono (solo números) y agregamos el código de país
            $cleanPhone = preg_replace('/[^0-9]/', '', $request->phone);
            $firebasePhone = '+52' . ltrim($cleanPhone, '0');

            // 1. Autenticación con Firebase Auth
            $auth = app('firebase.auth'); 
            $userRecord = $auth->getUserByPhoneNumber($firebasePhone);

            // 2. Conexión con Firestore para obtener el ROL
            $firestore = $this->getFirestore();

            $userDoc = $firestore
                ->collection('Usuarios')
                ->document($userRecord->uid)
                ->snapshot();

            if ($userDoc->exists()) {
                $userData = $userDoc->data();
                $rol = isset($userData['rol']) ? trim($userData['rol']) : 'usuario';

                // 3. Guardamos la sesión del usuario
                Session::put('firebase_user', [
                    'uid'    => $userRecord->uid,
                    'nombre' => $userData['nombre_completo'] ?? 'Usuario',
                    'rol'    => $rol
                ]);

                // 4. Redirección inteligente según el rol encontrado
                if ($rol === 'admin') {
                    return redirect()->route('admin.gestion')->with('success', 'Bienvenido administrador');
                }

                if ($rol === 'empresa') {
                    return redirect()->route('vacantes.index')->with('success', 'Acceso exitoso como Empresa');
                }

                return redirect()->route('home')->with('success', 'Bienvenido');
            }

            return back()->withErrors(['phone' => 'El número está registrado pero no tiene perfil en la base de datos.']);

        } catch (\Exception $e) {
            // Logeamos el error para revisarlo en Railway -> Deploy Logs
            Log::error('Login error: ' . $e->getMessage());
            
            // Si el error es invalid_grant, mandamos un mensaje más humano
            $errorMessage = str_contains($e->getMessage(), 'invalid_grant') 
                ? 'Error de conexión con Google (Credenciales). Revisa el JSON en Railway.' 
                : 'Error al iniciar sesión: ' . $e->getMessage();

            return back()->withErrors(['phone' => $errorMessage]);
        }
    }
}
