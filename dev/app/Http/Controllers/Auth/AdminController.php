<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Limpia y obtiene las credenciales de Firebase desde Railway
     */
    private function getFirebaseDatabase()
    {
        // Obtenemos el JSON crudo de la variable de entorno
        $jsonRaw = env('FIREBASE_CREDENTIALS_JSON');
        
        // Limpiamos basura de escape (diagonales y saltos de línea mal formateados)
        $jsonClean = str_replace(['\\n', '\\"'], ["\n", '"'], $jsonRaw);
        
        // Decodificamos a un array
        $credentials = json_decode($jsonClean, true);

        // Retornamos la base de datos Firestore configurada manualmente
        return Firebase::withServiceAccount($credentials)
            ->firestore()
            ->database();
    }

    public function gestion()
    {
        $vacantes = [];
        try {
            // Usamos nuestra función de limpieza para conectar
            $database = $this->getFirebaseDatabase();
            $documents = $database->collection('Vacantes')->documents();

            foreach ($documents as $document) {
                if ($document->exists()) {
                    $vacantes[] = array_merge(['id' => $document->id()], $document->data());
                }
            }
            
            $vacantes = collect($vacantes); 

            return view('auth.admin-gestion', compact('vacantes'));
            
        } catch (\Exception $e) {
            // Este log aparecerá en "View Logs" de Railway si algo falla
            Log::error("Error en Admin Firestore (Gestion): " . $e->getMessage());
            return view('auth.admin-gestion', ['vacantes' => collect([])]);
        }
    }

    public function toggle($id)
    {
        try {
            // Usamos la conexión limpia también aquí
            $database = $this->getFirebaseDatabase();
            $docRef = $database->collection('Vacantes')->document($id);
            $snapshot = $docRef->snapshot();

            if ($snapshot->exists()) {
                $data = $snapshot->data();
                $currentStatus = isset($data['is_approved']) ? $data['is_approved'] : false;
                
                $docRef->set([
                    'is_approved' => !$currentStatus
                ], ['merge' => true]);

                return back()->with('success', 'Estado actualizado correctamente.');
            }

            return back()->with('error', 'La vacante no existe.');

        } catch (\Exception $e) {
            Log::error("Error al actualizar vacante: " . $e->getMessage());
            return back()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }
}
