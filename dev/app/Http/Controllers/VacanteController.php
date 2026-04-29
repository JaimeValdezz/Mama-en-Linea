<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Google\Cloud\Firestore\FirestoreClient;

class VacanteController extends Controller
{
    private function getFirestore()
    {
        // 1. Cargamos el JSON desde las variables de entorno de Railway
        $json = env('FIREBASE_CREDENTIALS_JSON');
        $credentials = json_decode($json, true);

        // 💡 MEJORA DEL PARCHE CRÍTICO: 
        // Usamos un array en str_replace para limpiar tanto el texto literal "\n" 
        // como posibles dobles escapes que causan el "invalid_grant".
        if (isset($credentials['private_key'])) {
            $credentials['private_key'] = str_replace(['\\n', "\n"], "\n", $credentials['private_key']);
        }

        return new FirestoreClient([
            'projectId' => $credentials['project_id'] ?? 'proyectointegrador-43071',
            'keyFile'   => $credentials,
            'transport' => 'rest', // 🚀 Vital para evitar el "Application failed to respond"
        ]);
    }

    public function index()
    {
        $vacantes = [];

        try {
            $firestore = $this->getFirestore();

            // Esto obtiene los documentos de la colección
            $documents = $firestore->collection('Vacantes')->documents();

            foreach ($documents as $document) {
                if ($document->exists()) {
                    $data = $document->data();
                    $aprobada = $data['is_approved'] ?? false;

                    // Verificación flexible de booleano para evitar errores de tipo de dato
                    if ($aprobada == true || $aprobada == 'true') {
                        $vacantes[] = array_merge(['id' => $document->id()], $data);
                    }
                }
            }

            // Asegúrate de que la vista exista en resources/views/auth/vacantes.blade.php
            return view('auth.vacantes', compact('vacantes'));

        } catch (\Exception $e) {
            // Logeamos el error exacto para verlo en los Deploy Logs de Railway
            Log::error("Firestore error en index: " . $e->getMessage());
            return view('auth.vacantes', ['vacantes' => []])
                ->with('error', 'Error al cargar vacantes: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $firestore = $this->getFirestore();

            $firestore->collection('Vacantes')->add([
                'nombre_empresa' => $request->nombre_empresa,
                'titulo'         => $request->titulo,
                'sueldo'         => (int)$request->sueldo,
                'lugar'          => $request->lugar,
                'descripcion'    => $request->descripcion,
                'contacto'       => $request->contacto,
                'is_approved'    => false, // Siempre por aprobar al inicio
                'created_at'     => now()->toIso8601String()
            ]);

            return redirect()->route('vacantes.index')
                ->with('success', '¡Vacante publicada con éxito! Esperando aprobación.');

        } catch (\Exception $e) {
            Log::error("Firestore error en store: " . $e->getMessage());
            return back()->with('error', 'No se pudo publicar: ' . $e->getMessage());
        }
    }
}
