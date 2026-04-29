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
        $credentials = json_decode(env('FIREBASE_CREDENTIALS_JSON'), true);

        return new FirestoreClient([
            'keyFile' => $credentials,
            'transport' => 'rest',
        ]);
    }

    public function gestion()
    {
        $vacantes = [];

        try {
            $firestore = $this->getFirestore();

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
mira hermano ese es el admincontroller
