<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Asegúrate de que el modelo User exista

class RegisterController extends Controller
{
    public function showRegistrationForm() { return view('auth.register'); }

    public function registerEmpresa(Request $request)
    {
        $request->merge(['rol' => 'empresa']);
        return $this->register($request);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => ['required', 'string', 'max:255'],
            'phone'    => ['required', 'string', 'min:10', 'max:15'],
            'password' => ['required', Password::defaults()],
            'rol'      => ['required', 'string'] 
        ]);

        if ($validator->fails()) { 
            return back()->withErrors($validator)->withInput(); 
        }

        try {
            // 1. Limpieza del número de teléfono (solo números)
            $cleanPhone = preg_replace('/[^0-9]/', '', $request->phone);

            // 2. Verificar si el teléfono ya existe en SQL
            if (User::where('telefono', $cleanPhone)->exists()) {
                return back()->withErrors(['phone' => 'Este número ya está registrado en el sistema local.'])->withInput();
            }

            // 3. GUARDAR EN SQL (SQLite)
            $rolAsignado = $request->rol;
            
            $user = User::create([
                'nombre_completo' => $request->name,
                'telefono'        => $cleanPhone,
                'password'        => Hash::make($request->password), // Encriptamos la clave
                'rol'             => $rolAsignado,
            ]);

            // 4. Crear Sesión (Mantenemos la estructura para no romper tus vistas)
            Session::put('firebase_user', [
                'uid'    => $user->id, 
                'nombre' => $user->nombre_completo,
                'rol'    => $user->rol 
            ]);
            
            Log::info("Nuevo registro local exitoso: {$user->nombre_completo} con rol: {$user->rol}");

            // Redirección inteligente
            if ($user->rol === 'empresa') {
                return redirect()->route('vacantes.index')->with('success', '¡Registro de empresa exitoso!');
            }

            return redirect()->route('home')->with('success', '¡Registro exitoso!');

        } catch (\Exception $e) {
            Log::error('Error en Registro Local: ' . $e->getMessage());
            
            return back()->withErrors(['phone' => 'Hubo un error al crear la cuenta. Inténtalo de nuevo.'])
                         ->withInput();
        }
    }
}
