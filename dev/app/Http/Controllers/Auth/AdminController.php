<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Google\Cloud\Firestore\FirestoreClient;

class AdminController extends Controller
{
    private function getFirestore()
    {
        $json = env('FIREBASE_CREDENTIALS_JSON');
        $credentials = json_decode($json, true);

        // 💡 PARCHE VITAL: Limpia la llave para evitar el invalid_grant y errores de pila
        if (isset($credentials['private_key'])) {
            $credentials['private_key'] = str_replace(['\\n', "\n"], "\n", $credentials['private_key']);
        }

        return new FirestoreClient([
            'projectId' => $credentials['project_id'] ?? 'proyectointegrador-43071',
            'keyFile'   => $credentials,
            'transport' => 'rest', // 💡 EVITA ERRORES DE gRPC Y DESBORDAMIENTO DE PILA
        ]);
    }

    public function gestion()
    {
        $vacantes = [];

        try {
            $firestore = $this->getFirestore();

            // Obtenemos todas las vacantes para que el admin las gestione
            $documents = $firestore->collection('Vacantes')->documents();

            foreach ($documents as $document) {
                if ($document->exists()) {
                    $vacantes[] = array_merge(
                        ['id' => $document->id()],
                        $document->data()
                    );
                }
            }

            $vacantes = collect($vacantes);

            return view('auth.admin-gestion', compact('vacantes'));

        } catch (\Exception $e) {
            Log::error("Error en Admin Firestore: " . $e->getMessage());
            // Si falla, mandamos una colección vacía para que la vista no truene
            return view('auth.admin-gestion', ['vacantes' => collect([])]);
        }
    }

    public function toggle($id)
    {
        try {
            $firestore = $this->getFirestore();

            $docRef = $firestore->collection('Vacantes')->document($id);
            $snapshot = $docRef->snapshot();

            if ($snapshot->exists()) {
                $data = $snapshot->data();
                $currentStatus = isset($data['is_approved']) ? $data['is_approved'] : false;

                // Cambiamos el estado (si era true pasa a false y viceversa)
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
