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
            // 1. Arreglar el enum de género para aceptar los valores que envía el formulario
            DB::statement("ALTER TABLE estudiantes MODIFY genero ENUM('masculino', 'femenino')");
            
            // 2. Eliminar conflictos de nombres de campos geográficos
            // La migración anterior ya tiene birth_country_id, birth_state_id, birth_city_id
            // Pero las validaciones esperan pais_nacimiento_id, departamento_nacimiento_id, ciudad_nacimiento_id
            // Vamos a mantener los nombres actuales y cambiar las validaciones
            
            // 3. Agregar campos faltantes que el modelo espera
            if (!Schema::hasColumn('estudiantes', 'foto')) {
                $table->string('foto')->nullable()->after('restricciones_fisicas');
            }
            
            // 4. Asegurar que el campo activo sea reemplazado por estado
            if (Schema::hasColumn('estudiantes', 'activo')) {
                // Si existe el campo activo, crear el campo estado basado en él
                if (!Schema::hasColumn('estudiantes', 'estado')) {
                    $table->enum('estado', ['activo', 'inactivo', 'retirado'])->default('activo');
                    
                    // Copiar valores de activo a estado
                    DB::statement("UPDATE estudiantes SET estado = CASE WHEN activo = 1 THEN 'activo' ELSE 'inactivo' END");
                }
                
                // Eliminar el campo activo
                $table->dropColumn('activo');
            } else {
                // Si no existe activo, solo asegurar que existe estado
                if (!Schema::hasColumn('estudiantes', 'estado')) {
                    $table->enum('estado', ['activo', 'inactivo', 'retirado'])->default('activo');
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estudiantes', function (Blueprint $table) {
            // Revertir género
            DB::statement("ALTER TABLE estudiantes MODIFY genero ENUM('M', 'F')");
            
            // Restaurar campo activo
            if (!Schema::hasColumn('estudiantes', 'activo')) {
                $table->boolean('activo')->default(true);
                // Copiar valores de estado a activo
                DB::statement("UPDATE estudiantes SET activo = CASE WHEN estado = 'activo' THEN 1 ELSE 0 END");
            }
            
            // Eliminar campos agregados
            if (Schema::hasColumn('estudiantes', 'foto')) {
                $table->dropColumn('foto');
            }
            
            if (Schema::hasColumn('estudiantes', 'estado')) {
                $table->dropColumn('estado');
            }
        });
    }
};