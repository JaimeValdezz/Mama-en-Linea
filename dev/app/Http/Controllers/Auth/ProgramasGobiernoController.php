<?php

namespace App\Http\Controllers\Auth; // <--- OJO: Debe incluir \Auth

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProgramasGobiernoController extends Controller
{
    public function index()
    {
        $programas = [
            ['nombre' => 'Secretaría de Bienestar', 'descripcion' => 'Pensión para adultos mayores y discapacidad.', 'contacto' => '800 639 4264'],
            ['nombre' => 'SEED Durango', 'descripcion' => 'Becas escolares y uniformes gratuitos.', 'contacto' => '618 137 6000'],
            ['nombre' => 'DIF Estatal', 'descripcion' => 'Asistencia social y programas alimentarios.', 'contacto' => '618 137 9101'],
        ];

        // Apunta a resources/views/auth/apoyos-gubernamentales.blade.php
        return view('auth.apoyos-gubernamentales', compact('programas'));
    }
}