<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Elimina tablas geográficas duplicadas en español que están vacías.
     * Mantiene las tablas en inglés (countries, states, cities) que están en uso.
     */
    public function up(): void
    {
        // Primero eliminar foreign keys para poder borrar las tablas
        Schema::table('ciudades', function (Blueprint $table) {
            $table->dropForeign(['departamento_id']);
        });
        
        Schema::table('departamentos', function (Blueprint $table) {
            $table->dropForeign(['pais_id']);
        });
        
        // Ahora eliminar las tablas duplicadas en español (todas están vacías)
        Schema::dropIfExists('ciudades');       // duplicado de cities (0 registros)
        Schema::dropIfExists('departamentos');  // duplicado de states (0 registros)
        Schema::dropIfExists('paises');         // duplicado de countries (0 registros)
        
        // Nota: Se conservan countries, states, cities que contienen los datos y están en uso
    }

    /**
     * Reverse the migrations.
     * 
     * Recrea las tablas eliminadas (solo por seguridad, pero no deberían ser necesarias)
     */
    public function down(): void
    {
        // Recrear tablas básicas en caso de rollback
        Schema::create('paises', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('codigo', 3)->unique();
            $table->string('codigo_telefono', 5)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
        
        Schema::create('departamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pais_id')->constrained('paises')->onDelete('cascade');
            $table->string('nombre');
            $table->string('codigo', 10)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
        
        Schema::create('ciudades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('departamento_id')->constrained('departamentos')->onDelete('cascade');
            $table->string('nombre');
            $table->string('codigo', 10)->nullable();
            $table->boolean('es_capital')->default(false);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }
};
