<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ponencias', function (Blueprint $table) {
            $table->id();

            // Relación 1 a 1 con eventos (cada ponencia es un evento)
            $table->foreignId('evento_id')->constrained('eventos')->onDelete('cascade');

            // Relación con categorías
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('restrict');

            // Campos específicos de ponencia
            $table->string('ponente', 255); // nombre del ponente
            $table->string('institucion', 255)->nullable(); // universidad o empresa del ponente
            $table->string('archivo_presentacion', 255)->nullable(); // opcional, ruta del archivo (PDF, PPT, etc.)
            $table->string('foto', 255)->nullable();

            $table->string('codigo_qr', 255)->unique();

            $table->timestamps();

            // Garantiza que un evento solo pueda tener una ponencia asociada
            $table->unique('evento_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ponencias');
    }
};
