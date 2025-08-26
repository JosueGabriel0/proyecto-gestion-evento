<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ponencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')
                ->constrained('alumnos')
                ->onDelete('cascade');
            $table->string('titulo', 200);
            $table->text('descripcion')->nullable();
            $table->string('area', 100)->nullable();
            $table->dateTime('horario')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ponencias');
    }
};
