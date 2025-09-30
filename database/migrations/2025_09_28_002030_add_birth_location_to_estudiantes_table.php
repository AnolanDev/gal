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
        Schema::table('estudiantes', function (Blueprint $table) {
            // Eliminar el campo lugar_nacimiento antiguo si existe
            if (Schema::hasColumn('estudiantes', 'lugar_nacimiento')) {
                $table->dropColumn('lugar_nacimiento');
            }
            
            // Agregar nuevos campos de ubicación
            $table->foreignId('birth_country_id')->nullable()->constrained('countries');
            $table->foreignId('birth_state_id')->nullable()->constrained('states');
            $table->foreignId('birth_city_id')->nullable()->constrained('cities');
            
            // Índices para búsquedas rápidas
            $table->index(['birth_country_id', 'birth_state_id', 'birth_city_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estudiantes', function (Blueprint $table) {
            // Eliminar índices
            $table->dropIndex(['birth_country_id', 'birth_state_id', 'birth_city_id']);
            
            // Eliminar foreign keys
            $table->dropForeign(['birth_country_id']);
            $table->dropForeign(['birth_state_id']);
            $table->dropForeign(['birth_city_id']);
            
            // Eliminar columnas
            $table->dropColumn(['birth_country_id', 'birth_state_id', 'birth_city_id']);
            
            // Restaurar campo original si es necesario
            $table->string('lugar_nacimiento')->nullable();
        });
    }
};
