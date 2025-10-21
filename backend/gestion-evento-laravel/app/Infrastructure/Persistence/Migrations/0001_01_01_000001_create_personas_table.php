<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('tipoDocumento');
            $table->string('numeroDocumento');
            $table->string('telefono');
            $table->string('direccion');
            $table->string('pais');
            $table->string('religion');
            $table->string('correoElectronico');
            $table->string('correoInstitucional');
            $table->string('fotoPerfil')->nullable();
            $table->date('fechaNacimiento');

            $table->foreignId('user_id')
                ->nullable()
                ->unique() // ✅ Hace que user_id sea único
                ->constrained('users')
                ->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
