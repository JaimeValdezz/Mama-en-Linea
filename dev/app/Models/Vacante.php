<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacante extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_empresa',
        'titulo',
        'sueldo',
        'lugar',
        'descripcion',
        'contacto',
        'is_approved'
    ];
}
