<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tabla de Usuarios (Ajustada para empresas y login por teléfono)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_completo'); // Cambiado de 'name'
            $table->string('telefono')->unique(); // Cambiado de 'email'
            $table->string('password');
            $table->string('rol'); // Campo vital para saber si es 'empresa' o 'admin'
            $table->rememberToken();
            $table->timestamps();
        });

        // 2. Tabla para tokens de recuperación (Cambiado a teléfono)
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('telefono')->primary(); // Cambiado de 'email'
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // 3. Tabla de Sesiones (La dejamos igual, es estándar de Laravel)
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
