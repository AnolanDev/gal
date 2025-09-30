<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Estudiante extends Model
{
    use HasFactory;
    
    protected $table = 'estudiantes';
    
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        // Información básica
        'codigo_estudiante',
        'nombres',
        'apellidos',
        'tipo_documento',
        'documento_identidad',
        'genero',
        'fecha_nacimiento',
        
        // Geografía (lugar de nacimiento)
        'birth_country_id',
        'birth_state_id',
        'birth_city_id',
        
        // Información de contacto
        'direccion',
        'telefono',
        'email',
        
        // Contacto de emergencia
        'contacto_emergencia_nombre',
        'contacto_emergencia_telefono',
        'contacto_emergencia_relacion',
        
        // Información del PADRE
        'padre_nombres',
        'padre_apellidos',
        'padre_tipo_documento',
        'padre_documento',
        'padre_telefono',
        'padre_email',
        'padre_ocupacion',
        'padre_lugar_trabajo',
        'padre_autorizado_recoger',
        
        // Información de la MADRE
        'madre_nombres',
        'madre_apellidos',
        'madre_tipo_documento',
        'madre_documento',
        'madre_telefono',
        'madre_email',
        'madre_ocupacion',
        'madre_lugar_trabajo',
        'madre_autorizada_recoger',
        
        // Acudiente adicional
        'tiene_acudiente_adicional',
        'acudiente_nombres',
        'acudiente_apellidos',
        'acudiente_tipo_documento',
        'acudiente_documento',
        'acudiente_parentesco',
        'acudiente_telefono',
        'acudiente_email',
        
        // Información académica
        'grado_id',
        'fecha_ingreso',
        'estado',
        
        // Antecedentes académicos
        'es_estudiante_nuevo',
        'colegio_procedencia',
        'ultimo_grado_cursado',
        'ano_finalizacion',
        'tiene_certificados_pendientes',
        'observaciones_academicas',
        
        // Información médica
        'tipo_sangre',
        'eps',
        'numero_afiliacion_eps',
        'alergias',
        'medicamentos',
        'condiciones_medicas',
        'restricciones_fisicas',
        
        // Archivos y observaciones
        'foto',
        'observaciones',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_ingreso' => 'date',
        'padre_autorizado_recoger' => 'boolean',
        'madre_autorizada_recoger' => 'boolean',
        'tiene_acudiente_adicional' => 'boolean',
        'es_estudiante_nuevo' => 'boolean',
        'tiene_certificados_pendientes' => 'boolean',
        'ano_finalizacion' => 'integer',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [];

    // ===================================
    // RELACIONES
    // ===================================

    /**
     * Relación con el grado académico
     */
    public function grado(): BelongsTo
    {
        return $this->belongsTo(Grado::class);
    }

    /**
     * Relación con el país de nacimiento
     */
    public function birthCountry(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'birth_country_id');
    }

    /**
     * Relación con el estado/departamento de nacimiento
     */
    public function birthState(): BelongsTo
    {
        return $this->belongsTo(State::class, 'birth_state_id');
    }

    /**
     * Relación con la ciudad de nacimiento
     */
    public function birthCity(): BelongsTo
    {
        return $this->belongsTo(City::class, 'birth_city_id');
    }

    /**
     * Relación con las asistencias
     */
    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class);
    }

    /**
     * Relación con las calificaciones
     */
    public function calificaciones(): HasMany
    {
        return $this->hasMany(Calificacion::class);
    }

    // ===================================
    // ACCESSORS & MUTATORS
    // ===================================

    /**
     * Accessor para el nombre completo
     */
    protected function nombreCompleto(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->nombres} {$this->apellidos}",
        );
    }

    /**
     * Accessor para la edad
     */
    protected function edad(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->fecha_nacimiento 
                ? \Carbon\Carbon::parse($this->fecha_nacimiento)->age 
                : null,
        );
    }

    /**
     * Accessor para el nombre completo del padre
     */
    protected function padreNombreCompleto(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->padre_nombres && $this->padre_apellidos 
                ? "{$this->padre_nombres} {$this->padre_apellidos}" 
                : null,
        );
    }

    /**
     * Accessor para el nombre completo de la madre
     */
    protected function madreNombreCompleto(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->madre_nombres && $this->madre_apellidos 
                ? "{$this->madre_nombres} {$this->madre_apellidos}" 
                : null,
        );
    }

    /**
     * Accessor para el nombre completo del acudiente
     */
    protected function acudienteNombreCompleto(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->acudiente_nombres && $this->acudiente_apellidos 
                ? "{$this->acudiente_nombres} {$this->acudiente_apellidos}" 
                : null,
        );
    }

    /**
     * Accessor para la URL de la foto
     */
    protected function fotoUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->foto 
                ? asset('storage/estudiantes/fotos/' . $this->foto) 
                : null,
        );
    }

    // ===================================
    // SCOPES
    // ===================================

    /**
     * Scope para estudiantes activos
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    /**
     * Scope para estudiantes inactivos
     */
    public function scopeInactivos($query)
    {
        return $query->where('estado', 'inactivo');
    }

    /**
     * Scope para estudiantes retirados
     */
    public function scopeRetirados($query)
    {
        return $query->where('estado', 'retirado');
    }

    /**
     * Scope para filtrar por grado
     */
    public function scopePorGrado($query, $gradoId)
    {
        return $query->where('grado_id', $gradoId);
    }

    /**
     * Scope para búsqueda general
     */
    public function scopeBuscar($query, $termino)
    {
        return $query->where(function ($q) use ($termino) {
            $q->where('nombres', 'like', "%{$termino}%")
              ->orWhere('apellidos', 'like', "%{$termino}%")
              ->orWhere('codigo_estudiante', 'like', "%{$termino}%")
              ->orWhere('documento_identidad', 'like', "%{$termino}%")
              ->orWhereRaw("CONCAT(nombres, ' ', apellidos) like ?", ["%{$termino}%"]);
        });
    }

    /**
     * Scope para estudiantes nuevos
     */
    public function scopeEstudiantesNuevos($query)
    {
        return $query->where('es_estudiante_nuevo', true);
    }

    /**
     * Scope para estudiantes transferidos
     */
    public function scopeEstudiantesTransferidos($query)
    {
        return $query->where('es_estudiante_nuevo', false);
    }

    // ===================================
    // MÉTODOS UTILITARIOS
    // ===================================

    /**
     * Verifica si el estudiante tiene información completa de padre
     */
    public function tienePadreCompleto(): bool
    {
        return !empty($this->padre_nombres) && 
               !empty($this->padre_apellidos) && 
               !empty($this->padre_documento) && 
               !empty($this->padre_telefono);
    }

    /**
     * Verifica si el estudiante tiene información completa de madre
     */
    public function tieneMadreCompleta(): bool
    {
        return !empty($this->madre_nombres) && 
               !empty($this->madre_apellidos) && 
               !empty($this->madre_documento) && 
               !empty($this->madre_telefono);
    }

    /**
     * Verifica si el estudiante tiene al menos un padre registrado
     */
    public function tieneAlMenosUnPadre(): bool
    {
        return $this->tienePadreCompleto() || $this->tieneMadreCompleta();
    }

    /**
     * Obtiene la información de lugar de nacimiento completa
     */
    public function getLugarNacimientoCompleto(): ?string
    {
        if (!$this->birthCity) {
            return null;
        }

        $lugar = $this->birthCity->name;
        
        if ($this->birthState) {
            $lugar .= ', ' . $this->birthState->name;
        }
        
        if ($this->birthCountry) {
            $lugar .= ', ' . $this->birthCountry->name;
        }

        return $lugar;
    }

    /**
     * Genera el siguiente código de estudiante disponible
     */
    public static function generateNextCodigo(): string
    {
        $year = now()->year;
        $lastStudent = static::where('codigo_estudiante', 'like', $year . '%')
            ->orderBy('codigo_estudiante', 'desc')
            ->first();

        if (!$lastStudent) {
            return $year . '001';
        }

        $lastNumber = (int) substr($lastStudent->codigo_estudiante, -3);
        $nextNumber = $lastNumber + 1;

        return $year . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Boot del modelo
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generar código de estudiante si no se proporciona
        static::creating(function ($estudiante) {
            if (empty($estudiante->codigo_estudiante)) {
                $estudiante->codigo_estudiante = static::generateNextCodigo();
            }
        });
    }
}