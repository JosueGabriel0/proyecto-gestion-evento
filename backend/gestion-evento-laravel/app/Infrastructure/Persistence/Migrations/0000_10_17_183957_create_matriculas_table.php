<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('matriculas', function (Blueprint $table) {
            $table->id();
            
            $table->string('modo_contrato');
            $table->string('modalida_estudio');
            $table->string('ciclo');
            $table->string('grupo');

            // 🔹 Información adicional (si aplica)
            $table->date('fecha_matricula')->default(now());
            $table->string('estado')->default('activo'); // activo | cancelado | finalizado

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matriculas');
    }
};