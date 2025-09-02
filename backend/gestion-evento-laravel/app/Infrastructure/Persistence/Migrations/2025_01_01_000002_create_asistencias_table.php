<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();

            // Relación con usuarios
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Relación con eventos
            $table->foreignId('evento_id')->constrained('eventos')->onDelete('cascade');

            // Estado de asistencia
            $table->boolean('presente')->default(false);

            // Registro de hora (cuando llegó o fue marcado presente)
            $table->timestamp('hora_registro')->nullable();

            $table->timestamps();

            // Un mismo usuario no puede registrarse dos veces en el mismo evento
            $table->unique(['user_id', 'evento_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};