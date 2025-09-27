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
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudiante_id')->constrained('estudiantes');
            $table->date('fecha');
            $table->enum('estado', ['presente', 'ausente', 'tardanza', 'excusado']);
            $table->time('hora_llegada')->nullable();
            $table->text('observaciones')->nullable();
            $table->foreignId('registrado_por')->constrained('users');
            $table->timestamps();
            
            $table->unique(['estudiante_id', 'fecha']); // Un registro por estudiante por día
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};
