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
        // Primero eliminar foreign keys que referencian estudiantes
        if (Schema::hasTable('asistencias')) {
            Schema::table('asistencias', function (Blueprint $table) {
                $table->dropForeign(['estudiante_id']);
            });
        }
        
        if (Schema::hasTable('calificaciones')) {
            Schema::table('calificaciones', function (Blueprint $table) {
                $table->dropForeign(['estudiante_id']);
            });
        }
        
        // Eliminar tabla existente si existe
        Schema::dropIfExists('estudiantes');
        
        // Crear tabla estudiantes desde cero con estructura limpia y definitiva
        Schema::create('estudiantes', function (Blueprint $table) {
            // IDs y códigos
            $table->id();
            $table->string('codigo_estudiante', 20)->unique();
            
            // Información personal básica
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->enum('tipo_documento', ['registro_civil', 'tarjeta_identidad', 'cedula', 'pasaporte']);
            $table->string('documento_identidad', 50)->unique();
            $table->enum('genero', ['masculino', 'femenino']);
            $table->date('fecha_nacimiento');
            
            // Geografía (lugar de nacimiento)
            $table->foreignId('birth_country_id')->nullable()->constrained('countries')->onDelete('set null');
            $table->foreignId('birth_state_id')->nullable()->constrained('states')->onDelete('set null');
            $table->foreignId('birth_city_id')->nullable()->constrained('cities')->onDelete('set null');
            
            // Información de contacto
            $table->text('direccion');
            $table->string('telefono', 20)->nullable();
            $table->string('email', 100)->nullable()->unique();
            
            // Contacto de emergencia
            $table->string('contacto_emergencia_nombre', 100);
            $table->string('contacto_emergencia_telefono', 20);
            $table->string('contacto_emergencia_relacion', 50);
            
            // Información del PADRE
            $table->string('padre_nombres', 100)->nullable();
            $table->string('padre_apellidos', 100)->nullable();
            $table->string('padre_tipo_documento', 20)->nullable();
            $table->string('padre_documento', 50)->nullable();
            $table->string('padre_telefono', 20)->nullable();
            $table->string('padre_email', 100)->nullable();
            $table->string('padre_ocupacion', 100)->nullable();
            $table->string('padre_lugar_trabajo', 150)->nullable();
            $table->boolean('padre_autorizado_recoger')->default(true);
            
            // Información de la MADRE
            $table->string('madre_nombres', 100)->nullable();
            $table->string('madre_apellidos', 100)->nullable();
            $table->string('madre_tipo_documento', 20)->nullable();
            $table->string('madre_documento', 50)->nullable();
            $table->string('madre_telefono', 20)->nullable();
            $table->string('madre_email', 100)->nullable();
            $table->string('madre_ocupacion', 100)->nullable();
            $table->string('madre_lugar_trabajo', 150)->nullable();
            $table->boolean('madre_autorizada_recoger')->default(true);
            
            // Acudiente adicional (opcional)
            $table->boolean('tiene_acudiente_adicional')->default(false);
            $table->string('acudiente_nombres', 100)->nullable();
            $table->string('acudiente_apellidos', 100)->nullable();
            $table->string('acudiente_tipo_documento', 20)->nullable();
            $table->string('acudiente_documento', 50)->nullable();
            $table->string('acudiente_parentesco', 50)->nullable();
            $table->string('acudiente_telefono', 20)->nullable();
            $table->string('acudiente_email', 100)->nullable();
            
            // Información académica
            $table->foreignId('grado_id')->constrained('grados')->onDelete('restrict');
            $table->date('fecha_ingreso');
            $table->enum('estado', ['activo', 'inactivo', 'retirado'])->default('activo');
            
            // Antecedentes académicos
            $table->boolean('es_estudiante_nuevo')->default(true);
            $table->string('colegio_procedencia', 150)->nullable();
            $table->string('ultimo_grado_cursado', 50)->nullable();
            $table->year('ano_finalizacion')->nullable();
            $table->boolean('tiene_certificados_pendientes')->default(false);
            $table->text('observaciones_academicas')->nullable();
            
            // Información médica
            $table->enum('tipo_sangre', ['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'])->nullable();
            $table->string('eps', 100)->nullable();
            $table->string('numero_afiliacion_eps', 50)->nullable();
            $table->text('alergias')->nullable();
            $table->text('medicamentos')->nullable();
            $table->text('condiciones_medicas')->nullable();
            $table->text('restricciones_fisicas')->nullable();
            
            // Archivos
            $table->string('foto')->nullable();
            
            // Observaciones generales
            $table->text('observaciones')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Índices para optimización
            $table->index(['nombres', 'apellidos'], 'idx_nombres_completos');
            $table->index(['grado_id', 'estado'], 'idx_grado_estado');
            $table->index('fecha_ingreso', 'idx_fecha_ingreso');
            $table->index(['birth_country_id', 'birth_state_id', 'birth_city_id'], 'idx_geografia');
            $table->index('codigo_estudiante', 'idx_codigo');
            $table->index('documento_identidad', 'idx_documento');
        });
        
        // Restaurar foreign keys en otras tablas
        if (Schema::hasTable('asistencias')) {
            Schema::table('asistencias', function (Blueprint $table) {
                $table->foreign('estudiante_id')->references('id')->on('estudiantes')->onDelete('cascade');
            });
        }
        
        if (Schema::hasTable('calificaciones')) {
            Schema::table('calificaciones', function (Blueprint $table) {
                $table->foreign('estudiante_id')->references('id')->on('estudiantes')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudiantes');
    }
};