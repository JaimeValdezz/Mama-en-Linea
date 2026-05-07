<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Forzamos el borrado si existe antes de crearla para evitar bloqueos
        Schema::dropIfExists('vacantes');

        Schema::create('vacantes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_empresa'); 
            $table->string('titulo');
            $table->integer('sueldo');
            $table->string('lugar');
            $table->text('descripcion');
            $table->string('contacto')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->unsignedBigInteger('user_id')->nullable(); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vacantes');
    }
};
