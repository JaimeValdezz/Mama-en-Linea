<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Vacante; // <--- Necesitas crear este modelo

class VacanteController extends Controller
{
    public function index()
    {
        try {
            // BUSQUEDA EN SQL: Solo traemos las vacantes aprobadas
            // Es mucho más rápido que Firestore
            $vacantes = Vacante::where('is_approved', true)
                                ->orderBy('created_at', 'desc')
                                ->get();

            return view('auth.vacantes', compact('vacantes'));

        } catch (\Exception $e) {
            Log::error("Error en Vacante index: " . $e->getMessage());
            
            return view('auth.vacantes', ['vacantes' => []])
                ->withErrors(['error' => 'Error al cargar las vacantes locales.']);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string',
            'sueldo' => 'required|numeric',
        ]);

        try {
            // GUARDAR EN SQL:
            Vacante::create([
                'nombre_empresa' => $request->nombre_empresa,
                'titulo'         => $request->titulo,
                'sueldo'         => (int)$request->sueldo,
                'lugar'          => $request->lugar,
                'descripcion'    => $request->descripcion,
                'contacto'       => $request->contacto,
                'is_approved'    => false, // Sigue requiriendo aprobación
            ]);

            return redirect()->route('vacantes.index')
                ->with('success', '¡Vacante enviada! Aparecerá cuando el administrador la apruebe.');

        } catch (\Exception $e) {
            Log::error("Error en Vacante store: " . $e->getMessage());
            return back()->with('error', 'Error al guardar la vacante: ' . $e->getMessage());
        }
    }
}
