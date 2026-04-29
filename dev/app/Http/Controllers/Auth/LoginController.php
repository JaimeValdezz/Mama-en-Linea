<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
// Importamos la fachada para que no salga "Class not found"
use Kreait\LaravelFirebase\Facades\Firebase; 

class LoginController extends Controller
{
    private function getFirestore()
    {
        $json = env('FIREBASE_CREDENTIALS_JSON');
        $credentials = json_decode($json, true);

        // Parche para el error invalid_grant
        if (isset($credentials['private_key'])) {
            $credentials['private_key'] = str_replace(['\\n', "\n"], "\n", $credentials['private_key']);
        }

        return new \Google\Cloud\Firestore\FirestoreClient([
            'projectId' => $credentials['project_id'] ?? 'proyectointegrador-43071',
            'keyFile'   => $credentials,
            'transport' => 'rest', // Necesario en Railway
        ]);
    }

    // 💡 ESTA ES LA FUNCIÓN QUE TE FALTABA Y CAUSABA EL ERROR "Method does not exist"
    public function login(Request $request)
    {
        $request->validate([
            'phone'    => 'required|string',
            'password' => 'required|string',
        ]);

        try {
            $cleanPhone = preg_replace('/[^0-9]/', '', $request->phone);
            $firebasePhone = '+52' . ltrim($cleanPhone, '0');

            // Acceso seguro al Auth de Firebase
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

                // Guardamos la sesión (asegúrate de tener SESSION_DRIVER=file en Railway)
                Session::put('firebase_user', [
                    'uid'    => $userRecord->uid,
                    'nombre' => $userData['nombre_completo'] ?? 'Usuario',
                    'rol'    => $rol
                ]);

                // Redirecciones por rol
                if ($rol === 'admin') {
                    return redirect()->route('admin.gestion')->with('success', 'Bienvenido admin');
                }

                if ($rol === 'empresa') {
                    return redirect()->route('vacantes.index')->with('success', 'Bienvenido empresa');
                }

                return redirect()->route('home');
            }

            return back()->withErrors(['phone' => 'Usuario no encontrado en Firestore']);

        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return back()->withErrors(['phone' => 'Error: ' . $e->getMessage()]);
        }
    }
}
