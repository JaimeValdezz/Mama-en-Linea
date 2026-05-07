<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Models\Vacante; // <--- Importante: Usaremos el modelo local

class VacanteController extends Controller
{
    public function index()
    {
        $vacantes = [];
        try {
            // BUSQUEDA EN SQL: Traemos solo las vacantes aprobadas de la base de datos local
            $vacantes = Vacante::where('is_approved', true)
                                ->orderBy('created_at', 'desc')
                                ->get();

            return view('auth.vacantes', compact('vacantes'));
            
        } catch (\Exception $e) {
            Log::error("Error de conexión Base de Datos Local: " . $e->getMessage());
            return view('auth.vacantes', ['vacantes' => []])
                ->withErrors(['error' => 'No se pudieron cargar las vacantes.']);
        }
    }

    public function create()
    {
        // Verificamos la sesión que creamos en el LoginController
        if (!Session::has('firebase_user') || Session::get('firebase_user.rol') !== 'empresa') {
            return redirect()->route('login')->with('error', 'Solo las empresas pueden publicar vacantes.');
        }
        return view('auth.publicar');
    }

    public function store(Request $request)
    {
        // Validación básica
        $request->validate([
            'titulo' => 'required',
            'sueldo' => 'required|numeric',
        ]);

        try {
            // GUARDAR EN SQL: Insertamos directamente en el archivo SQLite de Railway
            Vacante::create([
                'nombre_empresa' => $request->nombre_empresa,
                'titulo'         => $request->titulo,
                'sueldo'         => (int)$request->sueldo,
                'lugar'          => $request->lugar,
                'descripcion'    => $request->descripcion,
                'contacto'       => $request->contacto,
                'is_approved'    => false, // Se guarda oculto para aprobación del admin
            ]);

            return redirect()->route('vacantes.index')->with('success', '¡Vacante publicada con éxito! Espera la aprobación del administrador.');

        } catch (\Exception $e) {
            Log::error("Error al guardar vacante local: " . $e->getMessage());
            return back()->with('error', 'Error al guardar: ' . $e->getMessage());
        }
    }
}
