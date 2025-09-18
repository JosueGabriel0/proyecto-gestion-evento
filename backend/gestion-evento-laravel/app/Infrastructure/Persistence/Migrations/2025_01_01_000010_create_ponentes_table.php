<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ponentes', function (Blueprint $table) {
            $table->id();
            $table->text('biografia')->nullable();

            // Relación con usuarios
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            // Relación con ponencias (1 ponencia → muchos ponentes)
            $table->foreignId('ponencia_id')
                ->constrained('ponencias')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ponentes');
    }
};