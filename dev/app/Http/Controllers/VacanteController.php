<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Google\Cloud\Firestore\FirestoreClient;

class VacanteController extends Controller
{
    /**
     * Parche maestro para conectar Firestore en Railway
     */
    private function getFirestore()
    {
        // 1. Obtenemos el JSON crudo (con barras invertidas extra de Railway)
        $jsonRaw = env('FIREBASE_CREDENTIALS_JSON');

        if (!$jsonRaw) {
            Log::error("Error: FIREBASE_CREDENTIALS_JSON no está en el ENV de Railway.");
            throw new \Exception('Configuración de base de datos ausente.');
        }

        // 2. LIMPIEZA PROFUNDA: Arregla el formato JSON corrupto detectado en el Raw Editor
        $jsonClean = str_replace(['\\n', '\\"'], ["\n", '"'], $jsonRaw);
        
        $credentials = json_decode($jsonClean, true);

        // Validamos que el JSON sea válido después de la limpieza
        if (json_last_error() !== JSON_ERROR_NONE) {
             throw new \Exception('Error al procesar JSON de Firebase: ' . json_last_error_msg());
        }

        // 3. Verificación de la private_key (crucial para Google Auth)
        if (isset($credentials['private_key'])) {
            $credentials['private_key'] = str_replace(['\\n', "\n"], "\n", $credentials['private_key']);
        }

        return new FirestoreClient([
            'projectId' => $credentials['project_id'] ?? 'proyectointegrador-43071',
            'keyFile'   => $credentials,
            'transport' => 'rest', // 🚀 Mantiene la app estable en Railway
        ]);
    }

    public function index()
    {
        $vacantes = [];

        try {
            $firestore = $this->getFirestore();

            // Consulta las vacantes de Firestore
            $documents = $firestore->collection('Vacantes')->documents();

            foreach ($documents as $document) {
                if ($document->exists()) {
                    $data = $document->data();
                    $aprobada = $data['is_approved'] ?? false;

                    // Filtramos solo las vacantes que el Admin ya aprobó
                    if ($aprobada === true || $aprobada === 'true') {
                        $vacantes[] = array_merge(['id' => $document->id()], $data);
                    }
                }
            }

            return view('auth.vacantes', compact('vacantes'));

        } catch (\Exception $e) {
            // Este log lo verás en la pestaña "View Logs" si algo falla
            Log::error("Firestore error en index: " . $e->getMessage());
            
            return view('auth.vacantes', ['vacantes' => []])
                ->withErrors(['error' => 'Error de conexión con las vacantes.']);
        }
    }

    public function store(Request $request)
    {
        // Validación básica antes de enviar a Firestore
        $request->validate([
            'titulo' => 'required|string',
            'sueldo' => 'required|numeric',
        ]);

        try {
            $firestore = $this->getFirestore();

            $firestore->collection('Vacantes')->add([
                'nombre_empresa' => $request->nombre_empresa,
                'titulo'         => $request->titulo,
                'sueldo'         => (int)$request->sueldo,
                'lugar'          => $request->lugar,
                'descripcion'    => $request->descripcion,
                'contacto'       => $request->contacto,
                'is_approved'    => false, // Toda vacante nueva requiere aprobación del Admin
                'created_at'     => now()->toIso8601String()
            ]);

            return redirect()->route('vacantes.index')
                ->with('success', '¡Vacante enviada! Aparecerá cuando el administrador la apruebe.');

        } catch (\Exception $e) {
            Log::error("Firestore error en store: " . $e->getMessage());
            return back()->with('error', 'Error al publicar la vacante: ' . $e->getMessage());
        }
    }
}
