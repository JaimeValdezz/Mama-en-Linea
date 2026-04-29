<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
// Importamos la fachada correcta
use Kreait\LaravelFirebase\Facades\Firebase; 

class LoginController extends Controller
{
    private function getFirestore()
    {
        $json = env('FIREBASE_CREDENTIALS_JSON');
        $credentials = json_decode($json, true);

        // 💡 CORRECCIÓN 1: Limpieza de la llave privada
        // Esto convierte el texto "\n" en saltos de línea reales que Google requiere
        if (isset($credentials['private_key'])) {
            $credentials['private_key'] = str_replace('\n', "\n", $credentials['private_key']);
        }

        return new \Google\Cloud\Firestore\FirestoreClient([
            'projectId' => $credentials['project_id'] ?? 'proyectointegrador-43071',
            'keyFile'   => $credentials,
            'transport' => 'rest', // 💡 CORRECCIÓN 2: Vital para Railway sin gRPC
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

            // 🔹 Usamos el asistente app() para evitar conflictos de clases
            $auth = app('firebase.auth'); 
            $userRecord = $auth->getUserByPhoneNumber($firebasePhone);

            $firestore = $this->getFirestore();

            $userDoc = $firestore
                ->collection('Usuarios')
                ->document($userRecord->uid)
                ->snapshot();

            if ($userDoc->exists()) {
                $userData = $userDoc->data();
                $rol = isset($userData['rol']) ? trim($userData['rol']) : 'usuario';

                Session::put('firebase_user', [
                    'uid'    => $userRecord->uid,
                    'nombre' => $userData['nombre_completo'] ?? 'Usuario',
                    'rol'    => $rol
                ]);

                // 🔹 Redirecciones basadas en Rol
                if ($rol === 'admin') {
                    return redirect()->route('admin.gestion')->with('success', 'Bienvenido admin');
                }

                if ($rol === 'empresa') {
                    return redirect()->route('vacantes.crear')->with('success', 'Bienvenido empresa');
                }

                return redirect()->route('home');
            }

            return back()->withErrors(['phone' => 'Usuario no encontrado en Firestore']);

        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            
            // Si el error sigue siendo invalid_grant, es por la caché o el JSON
            return back()->withErrors(['phone' => 'Error de autenticación: ' . $e->getMessage()]);
        }
    }
}
