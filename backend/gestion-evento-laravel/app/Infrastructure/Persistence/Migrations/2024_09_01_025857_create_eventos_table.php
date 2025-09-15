<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 255);
            $table->text('descripcion')->nullable();

            // Relación con tipos_evento
            $table->foreignId('tipo_evento_id')
                ->constrained('tipos_evento')
                ->restrictOnDelete();

            $table->dateTime('fecha');
            $table->string('lugar', 255)->nullable();

            // Coordenadas
            $table->decimal('latitud', 10, 8)->nullable();
            $table->decimal('longitud', 11, 8)->nullable();

            // Foto del evento (ruta o URL)
            $table->string('foto', 255)->nullable();
            $table->string('codigo_qr', 255)->unique();

            $table->foreignId('periodo_id')
                ->nullable()
                ->constrained('periodos')
                ->onDelete('set null');
                
            $table->unsignedBigInteger('escuela_id');
            $table->foreign('escuela_id')->references('id')->on('escuelas')->onDelete('cascade');


            $table->timestamps(); // created_at y updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};
