<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'phone_code',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    // Relaciones
    public function states()
    {
        return $this->hasMany(State::class);
    }

    public function cities()
    {
        return $this->hasManyThrough(City::class, State::class);
    }

    public function estudiantes()
    {
        return $this->hasMany(Estudiante::class, 'birth_country_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeOrderedByName($query)
    {
        return $query->orderBy('name');
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return $this->name . ' (' . $this->code . ')';
    }
}