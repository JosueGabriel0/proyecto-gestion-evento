<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tabla Escuelas
        Schema::create('escuelas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->string('codigo', 20)->unique();
            $table->string('foto', 255)->nullable();
            $table->unsignedBigInteger('facultad_id');
            $table->foreign('facultad_id')->references('id')->on('facultades')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('escuelas');
    }
};