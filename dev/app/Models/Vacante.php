<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacante extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descripcion',
        'empresa',
        'ubicacion',
        'salario',
        'user_id'
    ];
}
