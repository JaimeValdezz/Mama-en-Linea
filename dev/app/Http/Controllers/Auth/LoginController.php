<?php
namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Kreait\LaravelFirebase\Facades\Firebase;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    private function getFirestore()
    {
        $json = env('FIREBASE_CREDENTIALS_JSON');
        $credentials = json_decode($json, true);

        return new \Google\Cloud\Firestore\FirestoreClient([
            'projectId' => $credentials['project_id'] ?? null,
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
            $cleanPhone = preg_replace('/[^0-9]/', '', $request->phone);
            $firebasePhone = '+52' . ltrim($cleanPhone, '0');

            $auth = Firebase::auth();
            $userRecord = $auth->getUserByPhoneNumber($firebasePhone);

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

                if ($rol === 'admin') {
                    return redirect()->route('admin.gestion')->with('success', 'Bienvenido admin');
                }

                if ($rol === 'empresa') {
                    return redirect()->route('vacantes.crear')->with('success', 'Bienvenido empresa');
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
