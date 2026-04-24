<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class VacanteController extends Controller
{
    public function index()
    {
        $vacantes = [];
        try {
            $database = Firebase::firestore()->database();
            // IMPORTANTE: Asegúrate que en Firebase la colección se llame 'Vacantes' con V mayúscula o minúscula según tu consola
            $documents = $database->collection('Vacantes')->documents();

            foreach ($documents as $document) {
                if ($document->exists()) {
                    $data = $document->data();
                    $aprobada = $data['is_approved'] ?? false;

                    // Si está aprobada, la agregamos a la lista
                    if ($aprobada == true || $aprobada == 'true') {
                        $vacantes[] = array_merge(['id' => $document->id()], $data);
                    }
                }
            }
            return view('auth.vacantes', compact('vacantes'));
            
        } catch (\Exception $e) {
            Log::error("Error de conexión Firestore: " . $e->getMessage());
            return view('auth.vacantes',['vacantes' => []]);
        }
    }

    public function create()
    {
        if (!Session::has('firebase_user') || Session::get('firebase_user.rol') !== 'empresa') {
            return redirect()->route('login')->with('error', 'Solo las empresas pueden publicar vacantes.');
        }
        return view('auth.publicar');
    }

    public function store(Request $request)
{
    // COMENTAMOS ESTO PARA QUE NO TE MANDE AL LOGIN MIENTRAS PROBAMOS
    /*
    if (!Session::has('firebase_user')) {
        return redirect()->route('login');
    }
    */

    try {
        $database = Firebase::firestore()->database(['transport' => 'rest']);
        
        // Guardamos los datos que vienen del formulario
        $database->collection('Vacantes')->add([
            'nombre_empresa' => $request->nombre_empresa,
            'titulo'         => $request->titulo,
            'sueldo'         => (int)$request->sueldo,
            'lugar'          => $request->lugar,
            'descripcion'    => $request->descripcion,
            'contacto'       => $request->contacto,
            'is_approved'    => false, // Se guarda oculto para que el admin lo apruebe
            'created_at'     => now()->toIso8601String()
        ]);

        // Si llega aquí, es que SÍ se guardó en Firebase
        return redirect()->route('vacantes.index')->with('success', '¡Vacante publicada con éxito!');

    } catch (\Exception $e) {
        // Si hay un error de Firebase, aquí te dirá cuál es
        return back()->with('error', 'Error al guardar: ' . $e->getMessage());
    }
}

    // --- AGREGAMOS EL LOGIN DE EMPRESA (Si no lo tenías aquí) ---
    public function loginEmpresa(Request $request) {
        // Tu lógica de login de 10 dígitos aquí
    }
}