<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Kreait\Laravel\Firebase\Facades\Firebase;

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
            // 1. Formatear el teléfono para Firebase
            $cleanPhone = preg_replace('/[^0-9]/', '', $request->phone);
            $firebasePhone = '+52' . ltrim($cleanPhone, '0');

            // 2. Buscar usuario en Firebase Auth
            $auth = Firebase::auth();
            $userRecord = $auth->getUserByPhoneNumber($firebasePhone);

            // 3. Obtener datos de perfil en Firestore
            $database = Firebase::firestore()->database(['transport' => 'rest']);
            $userDoc = $database->collection('Usuarios')->document($userRecord->uid)->snapshot();

            if ($userDoc->exists()) {
                $userData = $userDoc->data();
                // Usamos trim para evitar errores por espacios invisibles en la base de datos
                $rol = isset($userData['rol']) ? trim($userData['rol']) : 'usuario';

                // 4. Guardar datos en la Sesión de Laravel
                Session::put('firebase_user', [
                    'uid'    => $userRecord->uid,
                    'nombre' => $userData['nombre_completo'] ?? 'Usuario',
                    'rol'    => $rol
                ]);

                // --- LÓGICA DE REDIRECCIÓN POR ROL ---

                // Si es ADMINISTRADOR -> Al Panel de Gestión
                if ($rol === 'admin') {
                    return redirect()->route('admin.gestion')->with('success', 'Bienvenido al panel de administración.');
                } 
                
                // Si es EMPRESA -> A Publicar Vacante (según tu ruta vacantes.crear)
                if ($rol === 'empresa') {
                    return redirect()->route('vacantes.crear')->with('success', 'Acceso concedido. Puedes publicar tu vacante.');
                }

                // Cualquier otro rol (como 'usuario') -> A la Home
                return redirect()->route('home');

            } else {
                return back()->withErrors(['phone' => 'El perfil de usuario no existe en Firestore.'])->withInput();
            }

        } catch (\Exception $e) {
            // Captura errores de Firebase o de conexión para evitar la pantalla en blanco
            \Log::error('Error de Login: ' . $e->getMessage());
            return back()->withErrors(['phone' => 'Error de autenticación: ' . $e->getMessage()])->withInput();
        }
    }
}
