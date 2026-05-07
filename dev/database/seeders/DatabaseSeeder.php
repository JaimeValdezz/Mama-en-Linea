<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Esto crea el usuario para que el login local funcione
        User::create([
            'nombre_completo' => 'Admin',
            'telefono' => '6188387171', 
            'password' => Hash::make('Caminosdelsol_120'),
            'rol' => 'admin',
        ]);
    }
}
