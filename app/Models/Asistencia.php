<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;

    protected $table = 'asistencias';

    protected $fillable = [
        'estudiante_id',
        'fecha',
        'estado',
        'observaciones',
        'justificacion'
    ];

    protected $casts = [
        'fecha' => 'date'
    ];

    // Relaciones
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }

    // Scopes
    public function scopePresentes($query)
    {
        return $query->where('estado', 'presente');
    }

    public function scopeAusentes($query)
    {
        return $query->where('estado', 'ausente');
    }

    public function scopeTardanzas($query)
    {
        return $query->where('estado', 'tardanza');
    }
}
