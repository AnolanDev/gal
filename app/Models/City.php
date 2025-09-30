<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'state_id',
        'name',
        'code',
        'is_capital',
        'active'
    ];

    protected $casts = [
        'is_capital' => 'boolean',
        'active' => 'boolean',
    ];

    // Relaciones
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function country()
    {
        return $this->hasOneThrough(
            Country::class,
            State::class,
            'id',           // Foreign key en states table
            'id',           // Foreign key en countries table
            'state_id',     // Local key en cities table
            'country_id'    // Local key en states table
        );
    }

    public function estudiantes()
    {
        return $this->hasMany(Estudiante::class, 'birth_city_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeByState($query, $stateId)
    {
        return $query->where('state_id', $stateId);
    }

    public function scopeCapitals($query)
    {
        return $query->where('is_capital', true);
    }

    public function scopeOrderedByName($query)
    {
        return $query->orderBy('name');
    }

    public function scopeOrderedByCapitalFirst($query)
    {
        return $query->orderBy('is_capital', 'desc')->orderBy('name');
    }

    // Accessors
    public function getFullNameAttribute()
    {
        $name = $this->name;
        if ($this->is_capital) {
            $name .= ' (Capital)';
        }
        if ($this->code) {
            $name .= ' (' . $this->code . ')';
        }
        return $name;
    }
}