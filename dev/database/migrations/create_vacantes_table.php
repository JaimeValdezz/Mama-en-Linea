<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vacantes', function (Blueprint $table) {
            $table->id();
            // Nombres ajustados para que coincidan con tu VacanteController
            $table->string('nombre_empresa'); 
            $table->string('titulo');
            $table->integer('sueldo'); // Cambiado de 'salario' a 'sueldo'
            $table->string('lugar');   // Cambiado de 'ubicacion' a 'lugar'
            $table->text('descripcion');
            $table->string('contacto')->nullable();
            
            // Este campo es VITAL porque tu controlador lo pide en el index()
            $table->boolean('is_approved')->default(false); 

            // Quitamos user_id por ahora si tu controlador no lo está enviando en el store()
            // O déjalo si piensas agregarlo después, pero ponlo como nullable
            $table->unsignedBigInteger('user_id')->nullable(); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vacantes');
    }
};
