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
        Schema::create('materias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Ej: "Matemáticas", "Español", "Ciencias"
            $table->string('codigo')->unique(); // Ej: "MAT", "ESP", "CIE"
            $table->text('descripcion')->nullable();
            $table->enum('nivel', ['preescolar', 'primaria', 'ambos'])->default('ambos');
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materias');
    }
};
