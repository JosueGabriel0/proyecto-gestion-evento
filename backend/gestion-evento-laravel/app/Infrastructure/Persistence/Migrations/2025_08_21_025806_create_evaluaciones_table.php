<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('evaluaciones', function (Blueprint $table) {
            $table->id();
            
            // Relación con ponencias
            $table->foreignId('ponencia_id')
                  ->constrained('ponencias')
                  ->onDelete('cascade');
            
            // Relación con jurados
            $table->foreignId('jurado_id')
                  ->constrained('jurados')
                  ->onDelete('cascade');
            
            $table->string('criterio', 100)->nullable(); // opcional si se evalúan varios criterios
            $table->decimal('puntaje', 5, 2); // ejemplo: 95.50
            $table->text('observaciones')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluaciones');
    }
};