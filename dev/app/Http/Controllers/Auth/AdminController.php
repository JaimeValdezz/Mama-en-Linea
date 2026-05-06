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
     * Soluciona el problema de "escapado" de caracteres detectado en el Raw Editor
     */
    private function getFirebaseDatabase()
    {
        // 1. Obtenemos el JSON crudo de la variable de entorno
        $jsonRaw = env('FIREBASE_CREDENTIALS_JSON');
        
        if (!$jsonRaw) {
            Log::error("FIREBASE_CREDENTIALS_JSON no configurado en Railway.");
            throw new \Exception('Configuración de Firebase ausente.');
        }
        
        // 2. Limpieza de basura de escape que Railway añade automáticamente
        // Convertimos los literal "\n" en saltos reales y eliminamos el escape de comillas
        $jsonClean = str_replace(['\\n', '\\"'], ["\n", '"'], $jsonRaw);
        
        // 3. Decodificamos a un array
        $credentials = json_decode($jsonClean, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error("Error decodificando JSON de Firebase: " . json_last_error_msg());
            throw new \Exception('JSON de credenciales inválido.');
        }

        // 4. Retornamos la base de datos Firestore configurada manualmente
        // Forzamos el uso del array limpio para evitar que use el archivo .json local
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
            // Este log aparecerá en "View Logs" de Railway si hay fallos de credenciales
            Log::error("Error en Admin Firestore (Gestion): " . $e->getMessage());
            return view('auth.admin-gestion', ['vacantes' => collect([])])
                ->withErrors(['conexion' => 'Error de conexión con la base de datos.']);
        }
    }

    public function toggle($id)
    {
        try {
            // Usamos la conexión limpia también aquí para asegurar la persistencia
            $database = $this->getFirebaseDatabase();
            $docRef = $database->collection('Vacantes')->document($id);
            $snapshot = $docRef->snapshot();

            if ($snapshot->exists()) {
                $data = $snapshot->data();
                $currentStatus = isset($data['is_approved']) ? $data['is_approved'] : false;
                
                $docRef->set([
                    'is_approved' => !$currentStatus
                ], ['merge' => true]);

                return back()->with('success', 'Estado de la vacante actualizado correctamente.');
            }

            return back()->with('error', 'La vacante seleccionada no existe.');

        } catch (\Exception $e) {
            Log::error("Error al actualizar vacante: " . $e->getMessage());
            return back()->with('error', 'No se pudo actualizar la vacante: ' . $e->getMessage());
        }
    }
}
