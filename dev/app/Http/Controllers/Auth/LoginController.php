<?php

namespace App\Http\Controllers\Auth; // 👈 Asegúrate que NO diga "dev" aquí.

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Kreait\LaravelFirebase\Facades\Firebase;
// ... otras importaciones
private function getFirestore()
{
    $credentials = json_decode(env('FIREBASE_CREDENTIALS_JSON'), true);

    return new \Google\Cloud\Firestore\FirestoreClient([
        'projectId' => $credentials['project_id'],
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
        // 🔹 Formatear teléfono
        $cleanPhone = preg_replace('/[^0-9]/', '', $request->phone);
        $firebasePhone = '+52' . ltrim($cleanPhone, '0');

        // 🔹 Auth Firebase
        $auth = Firebase::auth();
        $userRecord = $auth->getUserByPhoneNumber($firebasePhone);

        // 🔥 Firestore REST (Railway compatible)
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

            // 🔹 Redirecciones
            if ($rol === 'admin') {
                return redirect()->route('admin.gestion')
                    ->with('success', 'Bienvenido admin');
            }

            if ($rol === 'empresa') {
                return redirect()->route('vacantes.crear')
                    ->with('success', 'Bienvenido empresa');
            }

            return redirect()->route('home');
        }

        return back()->withErrors([
            'phone' => 'Usuario no encontrado en Firestore'
        ]);

    } catch (\Exception $e) {

        \Log::error('Login error: ' . $e->getMessage());

        return back()->withErrors([
            'phone' => 'Error: ' . $e->getMessage()
        ]);
    }
}
