<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EstudianteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            // ========================================
            // INFORMACIÓN BÁSICA
            // ========================================
            'id' => $this->id,
            'codigo_estudiante' => $this->codigo_estudiante,
            'nombres' => $this->nombres,
            'apellidos' => $this->apellidos,
            'nombre_completo' => $this->nombre_completo,
            'tipo_documento' => $this->tipo_documento,
            'tipo_documento_texto' => $this->getTipoDocumentoTexto(),
            'documento_identidad' => $this->documento_identidad,
            'genero' => $this->genero,
            'genero_texto' => ucfirst($this->genero),
            'fecha_nacimiento' => $this->fecha_nacimiento?->format('Y-m-d'),
            'fecha_nacimiento_formato' => $this->fecha_nacimiento?->format('d/m/Y'),
            'edad' => $this->edad,

            // ========================================
            // GEOGRAFÍA (LUGAR DE NACIMIENTO)
            // ========================================
            'birth_country_id' => $this->birth_country_id,
            'birth_state_id' => $this->birth_state_id,
            'birth_city_id' => $this->birth_city_id,
            'lugar_nacimiento_completo' => $this->getLugarNacimientoCompleto(),
            
            // Relaciones geográficas
            'birth_country' => $this->whenLoaded('birthCountry', function () {
                return [
                    'id' => $this->birthCountry->id,
                    'name' => $this->birthCountry->name,
                    'code' => $this->birthCountry->code,
                ];
            }),
            'birth_state' => $this->whenLoaded('birthState', function () {
                return [
                    'id' => $this->birthState->id,
                    'name' => $this->birthState->name,
                    'code' => $this->birthState->code,
                ];
            }),
            'birth_city' => $this->whenLoaded('birthCity', function () {
                return [
                    'id' => $this->birthCity->id,
                    'name' => $this->birthCity->name,
                    'is_capital' => $this->birthCity->is_capital,
                ];
            }),

            // ========================================
            // INFORMACIÓN DE CONTACTO
            // ========================================
            'direccion' => $this->direccion,
            'telefono' => $this->telefono,
            'email' => $this->email,

            // Contacto de emergencia
            'contacto_emergencia_nombre' => $this->contacto_emergencia_nombre,
            'contacto_emergencia_telefono' => $this->contacto_emergencia_telefono,
            'contacto_emergencia_relacion' => $this->contacto_emergencia_relacion,

            // ========================================
            // INFORMACIÓN DE PADRES
            // ========================================
            
            // Padre
            'padre_nombres' => $this->padre_nombres,
            'padre_apellidos' => $this->padre_apellidos,
            'padre_nombre_completo' => $this->padre_nombre_completo,
            'padre_tipo_documento' => $this->padre_tipo_documento,
            'padre_documento' => $this->padre_documento,
            'padre_telefono' => $this->padre_telefono,
            'padre_email' => $this->padre_email,
            'padre_ocupacion' => $this->padre_ocupacion,
            'padre_lugar_trabajo' => $this->padre_lugar_trabajo,
            'padre_autorizado_recoger' => $this->padre_autorizado_recoger,
            'tiene_padre_completo' => $this->tienePadreCompleto(),

            // Madre
            'madre_nombres' => $this->madre_nombres,
            'madre_apellidos' => $this->madre_apellidos,
            'madre_nombre_completo' => $this->madre_nombre_completo,
            'madre_tipo_documento' => $this->madre_tipo_documento,
            'madre_documento' => $this->madre_documento,
            'madre_telefono' => $this->madre_telefono,
            'madre_email' => $this->madre_email,
            'madre_ocupacion' => $this->madre_ocupacion,
            'madre_lugar_trabajo' => $this->madre_lugar_trabajo,
            'madre_autorizada_recoger' => $this->madre_autorizada_recoger,
            'tiene_madre_completa' => $this->tieneMadreCompleta(),

            // Acudiente adicional
            'tiene_acudiente_adicional' => $this->tiene_acudiente_adicional,
            'acudiente_nombres' => $this->acudiente_nombres,
            'acudiente_apellidos' => $this->acudiente_apellidos,
            'acudiente_nombre_completo' => $this->acudiente_nombre_completo,
            'acudiente_tipo_documento' => $this->acudiente_tipo_documento,
            'acudiente_documento' => $this->acudiente_documento,
            'acudiente_parentesco' => $this->acudiente_parentesco,
            'acudiente_telefono' => $this->acudiente_telefono,
            'acudiente_email' => $this->acudiente_email,

            // ========================================
            // INFORMACIÓN ACADÉMICA
            // ========================================
            'grado_id' => $this->grado_id,
            'fecha_ingreso' => $this->fecha_ingreso?->format('Y-m-d'),
            'fecha_ingreso_formatted' => $this->fecha_ingreso?->format('d/m/Y'),
            'estado' => $this->estado,
            'estado_texto' => ucfirst($this->estado),
            'estado_badge' => $this->getEstadoBadge(),

            // Antecedentes académicos
            'es_estudiante_nuevo' => $this->es_estudiante_nuevo,
            'colegio_procedencia' => $this->colegio_procedencia,
            'ultimo_grado_cursado' => $this->ultimo_grado_cursado,
            'ano_finalizacion' => $this->ano_finalizacion,
            'tiene_certificados_pendientes' => $this->tiene_certificados_pendientes,
            'observaciones_academicas' => $this->observaciones_academicas,

            // Relación con grado
            'grado' => $this->whenLoaded('grado', function () {
                return [
                    'id' => $this->grado->id,
                    'nombre' => $this->grado->nombre,
                    'descripcion' => $this->grado->descripcion ?? null,
                ];
            }),

            // ========================================
            // INFORMACIÓN MÉDICA
            // ========================================
            'tipo_sangre' => $this->tipo_sangre,
            'eps' => $this->eps,
            'numero_afiliacion_eps' => $this->numero_afiliacion_eps,
            'alergias' => $this->alergias,
            'medicamentos' => $this->medicamentos,
            'condiciones_medicas' => $this->condiciones_medicas,
            'restricciones_fisicas' => $this->restricciones_fisicas,

            // ========================================
            // ARCHIVOS Y OBSERVACIONES
            // ========================================
            'foto' => $this->foto,
            'foto_url' => $this->foto_url,
            'observaciones' => $this->observaciones,

            // ========================================
            // ESTADÍSTICAS Y RELACIONES
            // ========================================
            
            // Asistencias
            'total_asistencias' => $this->whenLoaded('asistencias', function () {
                return $this->asistencias->count();
            }),
            'asistencias_recientes' => $this->whenLoaded('asistencias', function () {
                return $this->asistencias->take(5)->map(function ($asistencia) {
                    return [
                        'id' => $asistencia->id,
                        'fecha' => $asistencia->fecha->format('d/m/Y'),
                        'estado' => $asistencia->estado,
                        'observaciones' => $asistencia->observaciones,
                    ];
                });
            }),

            // Calificaciones
            'total_calificaciones' => $this->whenLoaded('calificaciones', function () {
                return $this->calificaciones->count();
            }),
            'promedio_general' => $this->whenLoaded('calificaciones', function () {
                return round($this->calificaciones->avg('nota') ?? 0, 2);
            }),
            'calificaciones_recientes' => $this->whenLoaded('calificaciones', function () {
                return $this->calificaciones->take(5)->map(function ($calificacion) {
                    return [
                        'id' => $calificacion->id,
                        'materia' => $calificacion->materia->nombre ?? 'Sin materia',
                        'nota' => $calificacion->nota,
                        'fecha' => $calificacion->created_at->format('d/m/Y'),
                        'observaciones' => $calificacion->observaciones,
                    ];
                });
            }),

            // ========================================
            // METADATOS DEL SISTEMA
            // ========================================
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_at_formato' => $this->created_at->format('d/m/Y H:i'),
            'updated_at_formato' => $this->updated_at->format('d/m/Y H:i'),

            // Tiempo en la institución
            'dias_en_institucion' => $this->fecha_ingreso 
                ? now()->diffInDays($this->fecha_ingreso)
                : null,
            'anos_en_institucion' => $this->fecha_ingreso 
                ? round(now()->diffInYears($this->fecha_ingreso, true), 1)
                : null,
        ];
    }

    /**
     * Get texto del tipo de documento
     */
    private function getTipoDocumentoTexto(): string
    {
        return match($this->tipo_documento) {
            'registro_civil' => 'Registro Civil',
            'tarjeta_identidad' => 'Tarjeta de Identidad',
            'cedula' => 'Cédula',
            'pasaporte' => 'Pasaporte',
            default => 'No especificado'
        };
    }

    /**
     * Get badge configuration for estado
     */
    private function getEstadoBadge(): array
    {
        return match($this->estado) {
            'activo' => [
                'color' => 'green',
                'text' => 'Activo',
                'class' => 'bg-green-100 text-green-800 border-green-200',
                'icon' => '✓'
            ],
            'inactivo' => [
                'color' => 'yellow',
                'text' => 'Inactivo',
                'class' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                'icon' => '⏸'
            ],
            'retirado' => [
                'color' => 'red',
                'text' => 'Retirado',
                'class' => 'bg-red-100 text-red-800 border-red-200',
                'icon' => '✗'
            ],
            default => [
                'color' => 'gray',
                'text' => 'No especificado',
                'class' => 'bg-gray-100 text-gray-800 border-gray-200',
                'icon' => '?'
            ]
        };
    }

    /**
     * Determina si el estudiante tiene información completa
     */
    public function informacionCompleta(): array
    {
        $campos_basicos = [
            'nombres', 'apellidos', 'documento_identidad', 'fecha_nacimiento',
            'direccion', 'contacto_emergencia_nombre', 'contacto_emergencia_telefono'
        ];

        $campos_opcionales = [
            'telefono', 'email', 'foto', 'alergias', 'medicamentos'
        ];

        $completos_basicos = 0;
        $completos_opcionales = 0;

        foreach ($campos_basicos as $campo) {
            if (!empty($this->$campo)) {
                $completos_basicos++;
            }
        }

        foreach ($campos_opcionales as $campo) {
            if (!empty($this->$campo)) {
                $completos_opcionales++;
            }
        }

        return [
            'basicos_completados' => $completos_basicos,
            'basicos_total' => count($campos_basicos),
            'basicos_porcentaje' => round(($completos_basicos / count($campos_basicos)) * 100),
            'opcionales_completados' => $completos_opcionales,
            'opcionales_total' => count($campos_opcionales),
            'opcionales_porcentaje' => round(($completos_opcionales / count($campos_opcionales)) * 100),
            'general_porcentaje' => round((($completos_basicos + $completos_opcionales) / (count($campos_basicos) + count($campos_opcionales))) * 100),
            'tiene_al_menos_un_padre' => $this->tieneAlMenosUnPadre(),
        ];
    }
}