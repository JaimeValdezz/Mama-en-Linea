<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vacante extends Model
{
    use HasFactory;

    // Estos son los campos que permitimos guardar en SQLite
    protected $fillable = [
        'nombre_empresa', 
        'titulo', 
        'sueldo', 
        'lugar', 
        'descripcion', 
        'contacto', 
        'is_approved'
    ];

    // Esto asegura que Laravel trate el campo como booleano
    protected $casts = [
        'is_approved' => 'boolean',
    ];
}
