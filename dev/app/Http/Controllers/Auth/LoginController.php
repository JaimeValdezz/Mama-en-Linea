<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Google\Cloud\Firestore\FirestoreClient;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Parche de Doble Decodificación para Railway
     */
    private function getFirestore()
    {
        $jsonRaw = env('FIREBASE_CREDENTIALS_JSON');

        if (!$jsonRaw) {
            throw new \Exception('FIREBASE_CREDENTIALS_JSON no existe en Railway');
        }

        // 1. Limpieza inicial de caracteres basura vistos en Raw Editor
        $jsonClean = str_replace(['\\n', '\\"'], ["\n", '"'], $jsonRaw);
        
        // 2. Quitamos comillas que Railway pone al inicio y al final del bloque
        $jsonClean = trim($jsonClean, '" ');

        // 3. PRIMER INTENTO: Convertir a array o string limpio
        $credentials = json_decode($jsonClean, true);

        // 4. SEGUNDO INTENTO (Doble Escapado): 
        // Si Railway envolvió el JSON en un string, $credentials será un string y no un array.
        if (is_string($credentials)) {
            $credentials = json_decode($credentials, true);
        }

        if (!is_array($credentials) || !isset($credentials['private_key'])) {
            Log::error("Error crítico: El JSON no se pudo convertir a array. Contenido: " . substr($jsonClean, 0, 50));
            throw new \Exception('FIREBASE_CREDENTIALS_JSON inválido después de limpieza profunda');
        }

        // 5. Aseguramos saltos de línea reales en la llave privada
        $credentials['private_key'] = str_replace(['\\n', "\n"], "\n", $credentials['private_key']);

        return new FirestoreClient([
            'projectId' => $credentials['project_id'] ?? 'proyectointegrador-43071',
            'keyFile'   => $credentials,
            'transport' => 'rest', // ✅ Vital para entornos serverless/Railway
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone'    => 'required|string',
            'password' => 'required|string',
        ]);

        try {
            $cleanPhone = preg_replace('/[^0-9]/', '', $request->phone);
            $firebasePhone = '+52' . ltrim($cleanPhone, '0');

            // 💡 NOTA: app('firebase.auth') usa la config global. 
            // Si esto falla, el error está en config/firebase.php
            $auth = app('firebase.auth'); 
            $userRecord = $auth->getUserByPhoneNumber($firebasePhone);

            $firestore = $this->getFirestore();

            $userDoc = $firestore
                ->collection('Usuarios')
                ->document($userRecord->uid)
                ->snapshot();

            if (!$userDoc->exists()) {
                return back()->withErrors([
                    'phone' => 'El número está registrado pero no tiene perfil en la base de datos.'
                ]);
            }

            $userData = $userDoc->data();
            $rol = isset($userData['rol']) ? trim($userData['rol']) : 'usuario';

            Session::put('firebase_user', [
                'uid'    => $userRecord->uid,
                'nombre' => $userData['nombre_completo'] ?? 'Usuario',
                'rol'    => $rol
            ]);

            if ($rol === 'admin') {
                return redirect()->route('admin.gestion')
                    ->with('success', 'Bienvenido administrador');
            }

            if ($rol === 'empresa') {
                return redirect()->route('vacantes.index')
                    ->with('success', 'Acceso exitoso como empresa');
            }

            return redirect()->route('home')
                ->with('success', 'Bienvenido');

        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());

            // Mensaje detallado para saber si es el JSON o algo más
            $errorMessage = 'Error al iniciar sesión: ' . $e->getMessage();
            
            if (str_contains($e->getMessage(), 'invalid_grant') || str_contains($e->getMessage(), 'JSON')) {
                $errorMessage = 'Error de credenciales (JSON corrupto). Por favor, limpia la variable en Railway.';
            }

            return back()->withErrors([
                'phone' => $errorMessage
            ]);
        }
    }

    public function logout(Request $request)
    {
        Session::forget('firebase_user');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Has cerrado sesión correctamente.');
    }
}
