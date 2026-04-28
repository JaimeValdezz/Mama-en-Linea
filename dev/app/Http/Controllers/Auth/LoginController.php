<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Google\Cloud\Firestore\FirestoreClient;
use Kreait\Firebase\Factory; //  IMPORTANTE

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function logout(Request $request)
    {
        Session::forget('firebase_user');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Has cerrado sesión correctamente.');
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone'    => 'required|string',
            'password' => 'required|string',
        ]);

        try {
            // 1. Formatear teléfono
            $cleanPhone = preg_replace('/[^0-9]/', '', $request->phone);
            $firebasePhone = '+52' . ltrim($cleanPhone, '0');

            //  2. Firebase Auth CORRECTO
            $factory = (new Factory)
               ->withServiceAccount(json_decode(env('FIREBASE_CREDENTIALS_JSON'), true));

            $auth = $factory->createAuth();

            $userRecord = $auth->getUserByPhoneNumber($firebasePhone);

            // 🔥 3. Firestore (REST)
            $firestore = new FirestoreClient([
                'keyFilePath' => storage_path('app/firebase-credentials.json'),
                'transport' => 'rest',
            ]);

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

                if ($rol === 'admin') {
                    return redirect()->route('admin.gestion')
                        ->with('success', 'Bienvenido al panel de administración.');
                }

                if ($rol === 'empresa') {
                    return redirect()->route('vacantes.crear')
                        ->with('success', 'Acceso concedido. Puedes publicar tu vacante.');
                }

                return redirect()->route('home');

            } else {
                return back()->withErrors([
                    'phone' => 'El perfil de usuario no existe en Firestore.'
                ])->withInput();
            }

        } catch (\Exception $e) {
            \Log::error('Error de Login: ' . $e->getMessage());

            return back()->withErrors([
                'phone' => 'Error de autenticación: ' . $e->getMessage()
            ])->withInput();
        }
    }
}
