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

        // 💡 LIMPIEZA AGRESIVA: Buscamos el JSON real entre las comillas de Railway
        $jsonClean = str_replace(['\\n', '\\"', '\\/'], ["\n", '"', '/'], $jsonRaw);
        $jsonClean = trim($jsonClean, '" ');

        $credentials = json_decode($jsonClean, true);

        // Si Railway envolvió todo en un string, decodificamos de nuevo
        if (is_string($credentials)) {
            $credentials = json_decode($credentials, true);
        }

        if (!is_array($credentials) || !isset($credentials['private_key'])) {
            throw new \Exception('Invalid JSON source.');
        }

        $credentials['private_key'] = str_replace(['\\n', "\n"], "\n", $credentials['private_key']);

        return new FirestoreClient([
            'projectId' => $credentials['project_id'] ?? 'proyectointegrador-43071',
            'keyFile'   => $credentials,
            'transport' => 'rest',
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone'    => 'required|string',
            'password' => 'required|string',
        ]);

        try {
            // 💡 PASO CLAVE: Antes de llamar a Firebase Auth, forzamos la limpieza global
            // Esto arregla el error "Invalid JSON source" del paquete Kreait
            $firestore = $this->getFirestore(); 
            $credentials = $firestore->getSerializer()->decode(env('FIREBASE_CREDENTIALS_JSON')); // Intento de forzar carga

            $cleanPhone = preg_replace('/[^0-9]/', '', $request->phone);
            $firebasePhone = '+52' . ltrim($cleanPhone, '0');

            // Si esto sigue fallando, es porque el paquete lee el .env directamente.
            $auth = app('firebase.auth'); 
            $userRecord = $auth->getUserByPhoneNumber($firebasePhone);

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
                return redirect()->route('admin.gestion')->with('success', 'Bienvenido administrador');
            }

            if ($rol === 'empresa') {
                return redirect()->route('vacantes.index')->with('success', 'Acceso exitoso como empresa');
            }

            return redirect()->route('home')->with('success', 'Bienvenido');

        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());

            $errorMessage = 'Error al iniciar sesión: ' . $e->getMessage();
            
            // Si detectamos el error de JSON corrupto, damos el mensaje claro
            if (str_contains($e->getMessage(), 'Invalid JSON') || str_contains($e->getMessage(), 'invalid_grant')) {
                $errorMessage = 'Error de credenciales (JSON corrupto en Railway).';
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
