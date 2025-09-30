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
            // Agregar columnas faltantes
            $table->enum('tipo_documento', ['cedula', 'tarjeta_identidad', 'pasaporte'])->after('documento_identidad');
            $table->string('lugar_nacimiento', 150)->nullable()->after('fecha_nacimiento');
            $table->text('direccion')->change();
            $table->string('telefono', 20)->nullable()->after('direccion');
            $table->string('email', 100)->nullable()->unique()->after('telefono');
            $table->string('codigo_estudiante', 20)->unique()->after('email');
            $table->enum('estado', ['activo', 'inactivo', 'retirado'])->default('activo')->after('codigo_estudiante');
            $table->string('foto')->nullable()->after('estado');
            $table->text('observaciones')->nullable()->after('foto');
            
            // Información médica
            $table->enum('tipo_sangre', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])->nullable()->after('observaciones');
            $table->text('alergias')->nullable()->after('tipo_sangre');
            $table->text('medicamentos')->nullable()->after('alergias');
            $table->text('condiciones_medicas')->nullable()->after('medicamentos');
            
            // Contacto de emergencia
            $table->string('contacto_emergencia_nombre', 100)->after('condiciones_medicas');
            $table->string('contacto_emergencia_telefono', 20)->after('contacto_emergencia_nombre');
            $table->string('contacto_emergencia_relacion', 50)->after('contacto_emergencia_telefono');
            
            // Modificar género para que coincida con nuestro enum
            $table->enum('genero', ['masculino', 'femenino'])->change();
            
            // Remover la columna activo (usaremos estado)
            $table->dropColumn('activo');
            
            // Renombrar telefono_emergencia por consistency
            $table->dropColumn('telefono_emergencia');
            
            // Renombrar observaciones_medicas
            $table->dropColumn('observaciones_medicas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estudiantes', function (Blueprint $table) {
            $table->dropColumn([
                'tipo_documento',
                'lugar_nacimiento',
                'telefono',
                'email',
                'codigo_estudiante',
                'estado',
                'foto',
                'observaciones',
                'tipo_sangre',
                'alergias',
                'medicamentos',
                'condiciones_medicas',
                'contacto_emergencia_nombre',
                'contacto_emergencia_telefono',
                'contacto_emergencia_relacion'
            ]);
            
            $table->boolean('activo')->default(true);
            $table->string('telefono_emergencia')->nullable();
            $table->text('observaciones_medicas')->nullable();
            $table->enum('genero', ['M', 'F'])->change();
        });
    }
};
