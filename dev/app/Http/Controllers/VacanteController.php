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
        // 1. Cargamos el JSON crudo
        $jsonRaw = env('FIREBASE_CREDENTIALS_JSON');

        // 2. PARCHE CRÍTICO PARA RAILWAY:
        // Limpiamos las barras invertidas y escapes que Railway mete automáticamente
        $jsonClean = str_replace(['\\n', '\\"'], ["\n", '"'], $jsonRaw);
        
        $credentials = json_decode($jsonClean, true);

        // 3. Verificación extra de la llave privada
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

                    // Verificación flexible de booleano
                    if ($aprobada == true || $aprobada == 'true') {
                        $vacantes[] = array_merge(['id' => $document->id()], $data);
                    }
                }
            }

            return view('auth.vacantes', compact('vacantes'));

        } catch (\Exception $e) {
            // Logeamos el error exacto para verlo en Railway
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
                'is_approved'    => false, 
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
