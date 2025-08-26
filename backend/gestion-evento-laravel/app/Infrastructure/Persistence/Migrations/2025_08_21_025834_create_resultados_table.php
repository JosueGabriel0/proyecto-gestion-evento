<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('resultados', function (Blueprint $table) {
            $table->id();
            
            // Relación con ponencia
            $table->foreignId('ponencia_id')
                  ->constrained('ponencias')
                  ->onDelete('cascade');
            
            $table->decimal('puntaje_final', 5, 2)->nullable();
            $table->integer('posicion')->nullable(); // ranking
            
            $table->timestamp('generado_en')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resultados');
    }
};