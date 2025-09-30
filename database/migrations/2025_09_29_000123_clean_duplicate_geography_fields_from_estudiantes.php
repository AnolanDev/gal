<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('estudiantes', function (Blueprint $table) {
            // Verificar si existen los campos antiguos antes de eliminarlos
            if (Schema::hasColumn('estudiantes', 'pais_nacimiento_id')) {
                // Migrar datos de campos antiguos a nuevos si los nuevos están vacíos
                DB::statement("
                    UPDATE estudiantes 
                    SET 
                        birth_country_id = pais_nacimiento_id,
                        birth_state_id = departamento_nacimiento_id,
                        birth_city_id = ciudad_nacimiento_id
                    WHERE 
                        birth_country_id IS NULL 
                        AND pais_nacimiento_id IS NOT NULL
                ");
                
                // Eliminar foreign keys de campos antiguos
                try {
                    $table->dropForeign(['pais_nacimiento_id']);
                } catch (Exception $e) {
                    // Ignorar si no existe la foreign key
                }
                
                try {
                    $table->dropForeign(['departamento_nacimiento_id']);
                } catch (Exception $e) {
                    // Ignorar si no existe la foreign key
                }
                
                try {
                    $table->dropForeign(['ciudad_nacimiento_id']);
                } catch (Exception $e) {
                    // Ignorar si no existe la foreign key
                }
                
                // Eliminar columnas antiguas
                $table->dropColumn([
                    'pais_nacimiento_id',
                    'departamento_nacimiento_id', 
                    'ciudad_nacimiento_id'
                ]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estudiantes', function (Blueprint $table) {
            // Restaurar campos antiguos si es necesario
            $table->foreignId('pais_nacimiento_id')->nullable()->constrained('countries');
            $table->foreignId('departamento_nacimiento_id')->nullable()->constrained('states');
            $table->foreignId('ciudad_nacimiento_id')->nullable()->constrained('cities');
            
            // Migrar datos de vuelta
            DB::statement("
                UPDATE estudiantes 
                SET 
                    pais_nacimiento_id = birth_country_id,
                    departamento_nacimiento_id = birth_state_id,
                    ciudad_nacimiento_id = birth_city_id
                WHERE 
                    pais_nacimiento_id IS NULL 
                    AND birth_country_id IS NOT NULL
            ");
        });
    }
};