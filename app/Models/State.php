<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'name',
        'code',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    // Relaciones
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function estudiantes()
    {
        return $this->hasMany(Estudiante::class, 'birth_state_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeByCountry($query, $countryId)
    {
        return $query->where('country_id', $countryId);
    }

    public function scopeOrderedByName($query)
    {
        return $query->orderBy('name');
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return $this->name . ($this->code ? ' (' . $this->code . ')' : '');
    }
}