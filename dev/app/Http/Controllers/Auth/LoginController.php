<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Asegúrate de tener el modelo User creado

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // 1. Validación básica
        $request->validate([
            'phone'    => 'required|string',
            'password' => 'required|string',
        ]);

        try {
            // 2. Limpieza del número de teléfono (quitar espacios, guiones, etc.)
            $cleanPhone = preg_replace('/[^0-9]/', '', $request->phone);

            // 3. BUSQUEDA EN SQL: Buscamos al usuario en nuestra tabla local
            // Ya no usamos Firestore, buscamos directamente por el campo 'telefono'
            $user = User::where('telefono', $cleanPhone)->first();

            // 4. Verificación de existencia y contraseña
            if (!$user || !Hash::check($request->password, $user->password)) {
                return back()->withErrors([
                    'phone' => 'Las credenciales no coinciden con nuestros registros locales.'
                ]);
            }

            // 5. Guardar sesión (Mantenemos la estructura que ya tenías)
            $rol = trim($user->rol ?? 'usuario');

            Session::put('firebase_user', [
                'uid'    => $user->id,
                'nombre' => $user->nombre_completo ?? 'Usuario',
                'rol'    => $rol
            ]);

            // 6. Redirección según rol
            if ($rol === 'admin') {
                return redirect()->route('admin.gestion')->with('success', 'Bienvenido administrador');
            }

            if ($rol === 'empresa') {
                return redirect()->route('vacantes.index')->with('success', 'Acceso exitoso como empresa');
            }

            return redirect()->route('home')->with('success', 'Bienvenido');

        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());

            return back()->withErrors([
                'phone' => 'Error interno al intentar iniciar sesión. Inténtelo más tarde.'
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
