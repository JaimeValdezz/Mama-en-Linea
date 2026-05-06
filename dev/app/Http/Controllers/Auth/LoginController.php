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

    private function getFirestore()
    {
        $json = env('FIREBASE_CREDENTIALS_JSON');

        if (!$json) {
            throw new \Exception('FIREBASE_CREDENTIALS_JSON no existe en Railway');
        }

        $credentials = json_decode($json, true);

        if (!$credentials || !isset($credentials['private_key'])) {
            throw new \Exception('FIREBASE_CREDENTIALS_JSON inválido');
        }

        // Convierte \n en saltos reales
        $credentials['private_key'] = str_replace('\\n', "\n", $credentials['private_key']);

        return new FirestoreClient([
            'projectId' => $credentials['project_id'] ?? 'proyectointegrador-43071',
            'keyFile'   => $credentials,
            'transport' => 'grpc',
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

            $errorMessage = str_contains($e->getMessage(), 'invalid_grant')
                ? 'Error de conexión con Google (credenciales inválidas en Railway).'
                : 'Error al iniciar sesión: ' . $e->getMessage();

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

        return redirect('/login')
            ->with('success', 'Has cerrado sesión correctamente.');
    }
}
