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
        Schema::create('calificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudiante_id')->constrained('estudiantes');
            $table->foreignId('materia_id')->constrained('materias');
            $table->enum('periodo', ['primer_periodo', 'segundo_periodo', 'tercer_periodo', 'cuarto_periodo']);
            $table->decimal('nota', 3, 1); // Nota de 0.0 a 10.0
            $table->enum('tipo_evaluacion', ['examen', 'tarea', 'proyecto', 'participacion']);
            $table->string('descripcion')->nullable();
            $table->date('fecha_evaluacion');
            $table->foreignId('docente_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calificaciones');
    }
};
