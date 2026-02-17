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

            $table->string('nombre');

            // Relación 1 a 1 con eventos
            $table->foreignId('evento_id')->constrained('eventos')->onDelete('cascade');

            // Relación con categorías
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('restrict');

            // Campos específicos
            $table->string('ponente', 255);
            $table->string('institucion', 255)->nullable();
            $table->string('archivo_presentacion', 255)->nullable();
            $table->string('foto', 255)->nullable();
            $table->string('codigo_qr', 255)->unique();

            $table->timestamps();

            $table->unique('evento_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ponencias');
    }
};