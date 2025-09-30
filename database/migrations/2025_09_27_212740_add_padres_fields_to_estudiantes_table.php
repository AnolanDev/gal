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
            // Lugar de nacimiento con cascada
            $table->foreignId('pais_nacimiento_id')->nullable()->constrained('paises')->after('fecha_nacimiento');
            $table->foreignId('departamento_nacimiento_id')->nullable()->constrained('departamentos')->after('pais_nacimiento_id');
            $table->foreignId('ciudad_nacimiento_id')->nullable()->constrained('ciudades')->after('departamento_nacimiento_id');
            $table->dropColumn('lugar_nacimiento');
            
            // Información del PADRE
            $table->string('padre_nombres', 100)->nullable()->after('contacto_emergencia_relacion');
            $table->string('padre_apellidos', 100)->nullable()->after('padre_nombres');
            $table->enum('padre_tipo_documento', ['cedula', 'cedula_extranjeria', 'pasaporte'])->nullable()->after('padre_apellidos');
            $table->string('padre_documento', 20)->nullable()->after('padre_tipo_documento');
            $table->string('padre_telefono', 20)->nullable()->after('padre_documento');
            $table->string('padre_email', 100)->nullable()->after('padre_telefono');
            $table->string('padre_ocupacion', 100)->nullable()->after('padre_email');
            $table->string('padre_lugar_trabajo', 150)->nullable()->after('padre_ocupacion');
            $table->boolean('padre_autorizado_recoger')->default(true)->after('padre_lugar_trabajo');
            
            // Información de la MADRE
            $table->string('madre_nombres', 100)->nullable()->after('padre_autorizado_recoger');
            $table->string('madre_apellidos', 100)->nullable()->after('madre_nombres');
            $table->enum('madre_tipo_documento', ['cedula', 'cedula_extranjeria', 'pasaporte'])->nullable()->after('madre_apellidos');
            $table->string('madre_documento', 20)->nullable()->after('madre_tipo_documento');
            $table->string('madre_telefono', 20)->nullable()->after('madre_documento');
            $table->string('madre_email', 100)->nullable()->after('madre_telefono');
            $table->string('madre_ocupacion', 100)->nullable()->after('madre_email');
            $table->string('madre_lugar_trabajo', 150)->nullable()->after('madre_ocupacion');
            $table->boolean('madre_autorizada_recoger')->default(true)->after('madre_lugar_trabajo');
            
            // ACUDIENTE ADICIONAL
            $table->boolean('tiene_acudiente_adicional')->default(false)->after('madre_autorizada_recoger');
            $table->string('acudiente_nombres', 100)->nullable()->after('tiene_acudiente_adicional');
            $table->string('acudiente_apellidos', 100)->nullable()->after('acudiente_nombres');
            $table->enum('acudiente_tipo_documento', ['cedula', 'cedula_extranjeria', 'pasaporte'])->nullable()->after('acudiente_apellidos');
            $table->string('acudiente_documento', 20)->nullable()->after('acudiente_tipo_documento');
            $table->enum('acudiente_parentesco', ['abuelo', 'abuela', 'tio', 'tia', 'hermano', 'hermana', 'otro'])->nullable()->after('acudiente_documento');
            $table->string('acudiente_telefono', 20)->nullable()->after('acudiente_parentesco');
            $table->string('acudiente_email', 100)->nullable()->after('acudiente_telefono');
            
            // INFORMACIÓN MÉDICA ADICIONAL
            $table->string('eps', 100)->nullable()->after('tipo_sangre');
            $table->string('numero_afiliacion_eps', 50)->nullable()->after('eps');
            $table->text('restricciones_fisicas')->nullable()->after('condiciones_medicas');
            
            // ANTECEDENTES ACADÉMICOS
            $table->boolean('es_estudiante_nuevo')->default(true)->after('observaciones');
            $table->string('colegio_procedencia', 150)->nullable()->after('es_estudiante_nuevo');
            $table->string('ultimo_grado_cursado', 50)->nullable()->after('colegio_procedencia');
            $table->year('ano_finalizacion')->nullable()->after('ultimo_grado_cursado');
            $table->boolean('tiene_certificados_pendientes')->default(false)->after('ano_finalizacion');
            $table->text('observaciones_academicas')->nullable()->after('tiene_certificados_pendientes');
            
            // Remover padre_id ya que ahora manejamos la información completa
            $table->dropForeign(['padre_id']);
            $table->dropColumn('padre_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estudiantes', function (Blueprint $table) {
            // Restaurar lugar_nacimiento y padre_id
            $table->string('lugar_nacimiento', 150)->nullable();
            $table->foreignId('padre_id')->nullable()->constrained('users');
            
            // Remover campos de lugares
            $table->dropForeign(['pais_nacimiento_id']);
            $table->dropForeign(['departamento_nacimiento_id']);
            $table->dropForeign(['ciudad_nacimiento_id']);
            $table->dropColumn([
                'pais_nacimiento_id', 'departamento_nacimiento_id', 'ciudad_nacimiento_id',
                // Padre
                'padre_nombres', 'padre_apellidos', 'padre_tipo_documento', 'padre_documento',
                'padre_telefono', 'padre_email', 'padre_ocupacion', 'padre_lugar_trabajo', 'padre_autorizado_recoger',
                // Madre
                'madre_nombres', 'madre_apellidos', 'madre_tipo_documento', 'madre_documento',
                'madre_telefono', 'madre_email', 'madre_ocupacion', 'madre_lugar_trabajo', 'madre_autorizada_recoger',
                // Acudiente
                'tiene_acudiente_adicional', 'acudiente_nombres', 'acudiente_apellidos', 'acudiente_tipo_documento',
                'acudiente_documento', 'acudiente_parentesco', 'acudiente_telefono', 'acudiente_email',
                // Médico
                'eps', 'numero_afiliacion_eps', 'restricciones_fisicas',
                // Académico
                'es_estudiante_nuevo', 'colegio_procedencia', 'ultimo_grado_cursado', 'ano_finalizacion',
                'tiene_certificados_pendientes', 'observaciones_academicas'
            ]);
        });
    }
};
