<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vacante extends Model
{
    // Esto permite que el controlador guarde los datos en Postgres
    protected $fillable = [
        'nombre_empresa', 'titulo', 'sueldo', 'lugar', 'descripcion', 'contacto', 'is_approved'
    ];
}
