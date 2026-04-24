<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Session;
use Kreait\Laravel\Firebase\Facades\Firebase;

class RegisterController extends Controller
{
    public function showRegistrationForm() { return view('auth.register'); }

    // Esta es la función que usará la ruta de empresas
    public function registerEmpresa(Request $request)
    {
        // Forzamos el rol a 'empresa' antes de validar y procesar
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

        if ($validator->fails()) { return back()->withErrors($validator)->withInput(); }

        try {
            $cleanPhone = preg_replace('/[^0-9]/', '', $request->phone);
            $firebasePhone = '+52' . ltrim($cleanPhone, '0');

            // 1. Registro en Firebase Auth
            $auth = Firebase::auth();
            $userRecord = $auth->createUser([
                'phoneNumber' => $firebasePhone,
                'password'    => $request->password,
                'displayName' => $request->name,
            ]);

            $uid = $userRecord->uid;

            // 2. GUARDAR EN FIRESTORE
            $database = Firebase::firestore()->database();
            $rolAsignado = $request->rol;

            $database->collection('Usuarios')
                ->document($uid)
                ->set([
                    'uid'             => $uid,
                    'nombre_completo' => $request->name,
                    'telefono'        => $firebasePhone,
                    'created_at'      => now()->toIso8601String(),
                    'rol'             => $rolAsignado, 
                ]);

            // 3. Crear Sesión
            Session::put('firebase_user', [
                'uid'    => $uid, 
                'nombre' => $request->name,
                'rol'    => $rolAsignado 
            ]);
            
            \Log::info("Nuevo registro: {$request->name} con rol: {$rolAsignado}");

            // Redirección inteligente
            if ($rolAsignado === 'empresa') {
                return redirect()->route('vacantes.crear')->with('success', '¡Registro de empresa exitoso! Ya puedes publicar.');
            }

            return redirect()->route('home')->with('success', '¡Registro exitoso!');

        } catch (\Exception $e) {
            \Log::error('Error Firebase Register: ' . $e->getMessage());
            return back()->withErrors(['phone' => 'El número ya está registrado o hubo un error de conexión.'])->withInput();
        }
    }
}