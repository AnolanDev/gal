<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grade extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'level',
        'section',
        'academic_year',
        'max_students',
        'classroom',
        'status',
        'description',
    ];

    protected $appends = [
        'full_name',
        'student_count',
        'available_spots',
    ];

    // Relationships
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'grade_teacher')
                    ->withPivot('is_main_teacher', 'academic_period_id')
                    ->withTimestamps();
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'grade_subject')
                    ->withPivot('hours_per_week', 'academic_period_id')
                    ->withTimestamps();
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        return $this->section ? "{$this->name}{$this->section}" : $this->name;
    }

    public function getStudentCountAttribute(): int
    {
        return $this->students()->active()->count();
    }

    public function getAvailableSpotsAttribute(): int
    {
        return max(0, $this->max_students - $this->student_count);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    public function scopeByAcademicYear($query, $year)
    {
        return $query->where('academic_year', $year);
    }

    public function scopeCurrentYear($query)
    {
        return $query->where('academic_year', date('Y'));
    }

    // Helper Methods
    public function getMainTeacher(): ?Teacher
    {
        return $this->teachers()
                   ->wherePivot('is_main_teacher', true)
                   ->first();
    }

    public function hasAvailableSpots(): bool
    {
        return $this->available_spots > 0;
    }

    public function canEnrollStudent(): bool
    {
        return $this->status === 'active' && $this->hasAvailableSpots();
    }

    public function getActiveStudents()
    {
        return $this->students()->active()->get();
    }

    public function getSubjectTeachers()
    {
        return $this->teachers()
                   ->wherePivot('is_main_teacher', false)
                   ->with('subjects')
                   ->get();
    }

    public function getAverageAge(): float
    {
        $ages = $this->students()->active()->get()->pluck('age');
        return $ages->count() > 0 ? $ages->avg() : 0;
    }

    public function getAttendanceRate(): float
    {
        $students = $this->students()->active()->get();
        if ($students->isEmpty()) return 100.0;
        
        $totalRate = $students->sum('attendance_rate');
        return round($totalRate / $students->count(), 1);
    }
}