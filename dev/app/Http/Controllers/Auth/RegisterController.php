<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Session;
use Google\Cloud\Firestore\FirestoreClient;

class RegisterController extends Controller
{
    public function showRegistrationForm() { return view('auth.register'); }

    /**
     * Parche para obtener Firestore con transporte REST y limpieza profunda de JSON
     */
    private function getFirestore()
    {
        // 1. Obtenemos el JSON crudo que Railway ensucia
        $jsonRaw = env('FIREBASE_CREDENTIALS_JSON');

        if (!$jsonRaw) {
            throw new \Exception('FIREBASE_CREDENTIALS_JSON no configurado en Railway.');
        }

        // 2. LIMPIEZA MAESTRA: Quitamos barras extra y escapes de comillas (visto en tu Raw Editor)
        $jsonClean = str_replace(['\\n', '\\"'], ["\n", '"'], $jsonRaw);
        $credentials = json_decode($jsonClean, true);

        // 3. Verificación y limpieza final de la private_key
        if (isset($credentials['private_key'])) {
            $credentials['private_key'] = str_replace(['\\n', "\n"], "\n", $credentials['private_key']);
        }

        return new FirestoreClient([
            'projectId' => $credentials['project_id'] ?? 'proyectointegrador-43071',
            'keyFile'   => $credentials,
            'transport' => 'rest', // 🚀 Vital para evitar caídas en Railway
        ]);
    }

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

        if ($validator->fails()) { return back()->withErrors($validator)->withInput(); }

        try {
            $cleanPhone = preg_replace('/[^0-9]/', '', $request->phone);
            $firebasePhone = '+52' . ltrim($cleanPhone, '0');

            // 1. Registro en Firebase Auth
            // Nota: Este helper app('firebase.auth') depende de tu ServiceProvider.
            // Si el login falla, asegúrate de que el ServiceProvider también limpie el JSON.
            $auth = app('firebase.auth');
            $userRecord = $auth->createUser([
                'phoneNumber' => $firebasePhone,
                'password'    => $request->password,
                'displayName' => $request->name,
            ]);

            $uid = $userRecord->uid;

            // 2. GUARDAR EN FIRESTORE usando el parche de limpieza
            $firestore = $this->getFirestore();
            $rolAsignado = $request->rol;

            $firestore->collection('Usuarios')
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
            
            Log::info("Nuevo registro exitoso: {$request->name} con rol: {$rolAsignado}");

            // Redirección inteligente
            if ($rolAsignado === 'empresa') {
                return redirect()->route('vacantes.index')->with('success', '¡Registro de empresa exitoso!');
            }

            return redirect()->route('home')->with('success', '¡Registro exitoso!');

        } catch (\Exception $e) {
            Log::error('Error Firebase Register: ' . $e->getMessage());
            
            // Mensaje amigable para el error de credenciales corruptas
            $msg = str_contains($e->getMessage(), 'invalid_grant') 
                ? 'Error de conexión (Credenciales corruptas en Railway). Intenta de nuevo en unos minutos.' 
                : 'El número ya está registrado o hubo un error de red.';

            return back()->withErrors(['phone' => $msg])->withInput();
        }
    }
}
