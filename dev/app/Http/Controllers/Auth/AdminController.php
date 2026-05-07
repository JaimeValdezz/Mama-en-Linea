<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vacante; // <--- Usamos el modelo de Postgres
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function gestion()
    {
        try {
            // Traemos todas las vacantes de Postgres, sin importar si están aprobadas o no
            $vacantes = Vacante::orderBy('created_at', 'desc')->get();

            return view('auth.admin-gestion', compact('vacantes'));
            
        } catch (\Exception $e) {
            Log::error("Error en Admin (Gestion Postgres): " . $e->getMessage());
            return view('auth.admin-gestion', ['vacantes' => collect([])])
                ->withErrors(['conexion' => 'Error de conexión con la base de datos de Postgres.']);
        }
    }

    public function toggle($id)
    {
        try {
            // Buscamos la vacante en Postgres por su ID
            $vacante = Vacante::findOrFail($id);
            
            // Cambiamos el estado (si es true pasa a false, y viceversa)
            $vacante->is_approved = !$vacante->is_approved;
            $vacante->save();

            return back()->with('success', 'Estado de la vacante actualizado correctamente.');

        } catch (\Exception $e) {
            Log::error("Error al actualizar vacante en Postgres: " . $e->getMessage());
            return back()->with('error', 'No se pudo actualizar la vacante.');
        }
    }
}
