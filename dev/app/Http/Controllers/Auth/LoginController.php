<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// ... (tus otras importaciones)

class LoginController extends Controller
{
    // 💡 LA FUNCIÓN DEBE IR AQUÍ ADENTRO, después de la llave de la clase
    private function getFirestore()
    {
        $json = env('FIREBASE_CREDENTIALS_JSON');
        $credentials = json_decode($json, true);

        if (isset($credentials['private_key'])) {
            $credentials['private_key'] = str_replace(['\\n', "\n"], "\n", $credentials['private_key']);
        }

        return new \Google\Cloud\Firestore\FirestoreClient([
            'projectId' => $credentials['project_id'] ?? 'proyectointegrador-43071',
            'keyFile'   => $credentials,
            'transport' => 'rest',
        ]);
    }

    // Luego siguen tus otras funciones como login()...
}
