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
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id();
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('documento_identidad')->unique();
            $table->date('fecha_nacimiento');
            $table->enum('genero', ['M', 'F']);
            $table->foreignId('grado_id')->constrained('grados');
            $table->foreignId('padre_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('direccion')->nullable();
            $table->string('telefono_emergencia')->nullable();
            $table->text('observaciones_medicas')->nullable();
            $table->boolean('activo')->default(true);
            $table->date('fecha_ingreso')->default(now());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudiantes');
    }
};
