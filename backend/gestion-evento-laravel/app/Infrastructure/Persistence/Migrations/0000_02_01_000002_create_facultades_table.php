<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
       
        // Tabla Facultades
        Schema::create('facultades', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->string('codigo', 20)->unique();
            $table->string('foto', 255)->nullable();
            $table->unsignedBigInteger('filial_id');
            $table->foreign('filial_id')->references('id')->on('filiales')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facultades');
    }
};