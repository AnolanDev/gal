<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    use HasFactory;

    protected $table = 'materias';

    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'creditos',
        'horas_semanales',
        'grado_id',
        'activa'
    ];

    protected $casts = [
        'activa' => 'boolean',
        'creditos' => 'integer',
        'horas_semanales' => 'integer'
    ];

    // Relaciones
    public function grado()
    {
        return $this->belongsTo(Grado::class);
    }

    public function calificaciones()
    {
        return $this->hasMany(Calificacion::class);
    }

    // Scopes
    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }

    public function scopePorGrado($query, $gradoId)
    {
        return $query->where('grado_id', $gradoId);
    }
}
