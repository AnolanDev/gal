<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    use HasFactory;

    protected $table = 'calificaciones';

    protected $fillable = [
        'estudiante_id',
        'materia_id',
        'periodo',
        'tipo_evaluacion',
        'nota',
        'observaciones',
        'fecha_evaluacion'
    ];

    protected $casts = [
        'fecha_evaluacion' => 'date',
        'nota' => 'decimal:2'
    ];

    // Relaciones
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }

    public function materia()
    {
        return $this->belongsTo(Materia::class);
    }
}
