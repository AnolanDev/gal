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
        // Países
        Schema::create('paises', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('codigo_iso', 3)->unique(); // CO, VE, PA, etc.
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // Departamentos/Estados
        Schema::create('departamentos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('codigo', 10)->nullable(); // Código DANE para Colombia
            $table->foreignId('pais_id')->constrained('paises')->onDelete('cascade');
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->index(['pais_id', 'activo']);
        });

        // Ciudades/Municipios
        Schema::create('ciudades', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('codigo', 10)->nullable(); // Código DANE para Colombia
            $table->foreignId('departamento_id')->constrained('departamentos')->onDelete('cascade');
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->index(['departamento_id', 'activo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ciudades');
        Schema::dropIfExists('departamentos');
        Schema::dropIfExists('paises');
    }
};
