<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function gestion()
    {
        $vacantes = [];
        try {
            $database = Firebase::firestore()->database();
            $documents = $database->collection('Vacantes')->documents();

            foreach ($documents as $document) {
                if ($document->exists()) {
                    $vacantes[] = array_merge(['id' => $document->id()], $document->data());
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
            $database = Firebase::firestore()->database(['transport' => 'rest']);
            $docRef = $database->collection('Vacantes')->document($id);
            $snapshot = $docRef->snapshot();

            if ($snapshot->exists()) {
                // CORRECCIÓN: Obtenemos los datos primero para verificar el campo
                $data = $snapshot->data();
                
                // Si el campo 'is_approved' existe, tomamos su valor; si no, es false
                $currentStatus = isset($data['is_approved']) ? $data['is_approved'] : false;
                
                // Usamos set con ['merge' => true] para crear el campo si no existe
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
