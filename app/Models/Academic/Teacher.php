<?php

namespace App\Models\Academic;

use App\Models\User;
use App\Models\Evaluation\Attendance;
use App\Models\Evaluation\GradeReport;
use App\Models\Evaluation\Observation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'employee_code',
        'identification_number',
        'identification_type',
        'specialization',
        'education_level',
        'hire_date',
        'status',
        'phone',
        'emergency_contact',
        'emergency_phone',
        'salary',
        'contract_type',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'salary' => 'decimal:2',
    ];

    protected $hidden = [
        'salary',
    ];

    protected $appends = [
        'full_name',
        'years_of_service',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function grades(): BelongsToMany
    {
        return $this->belongsToMany(Grade::class, 'grade_teacher')
                    ->withPivot('is_main_teacher', 'academic_period_id')
                    ->withTimestamps();
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'teacher_subject')
                    ->withPivot('grade_id', 'academic_period_id')
                    ->withTimestamps();
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'teacher_student')
                    ->withPivot('subject_id', 'academic_period_id')
                    ->withTimestamps();
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function gradeReports(): HasMany
    {
        return $this->hasMany(GradeReport::class);
    }

    public function observations(): HasMany
    {
        return $this->hasMany(Observation::class);
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        return $this->user ? $this->user->name : 'N/A';
    }

    public function getYearsOfServiceAttribute(): int
    {
        return $this->hire_date ? $this->hire_date->diffInYears(now()) : 0;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeBySpecialization($query, $specialization)
    {
        return $query->where('specialization', $specialization);
    }

    public function scopeMainTeachers($query)
    {
        return $query->whereHas('grades', function ($q) {
            $q->where('is_main_teacher', true);
        });
    }

    // Helper Methods
    public function getTodayClasses()
    {
        // This would get today's schedule
        // Implementation depends on your scheduling system
        return collect([
            [
                'id' => 1,
                'subject' => 'Matemáticas',
                'grade' => '3°A',
                'time' => '08:00 - 09:00',
                'classroom' => '201',
                'status' => 'pending'
            ],
            [
                'id' => 2,
                'subject' => 'Ciencias',
                'grade' => '3°B',
                'time' => '10:00 - 11:00',
                'classroom' => '203',
                'status' => 'pending'
            ]
        ]);
    }

    public function getRecentActivity()
    {
        // This would get recent teacher activities
        return collect([
            [
                'id' => 1,
                'description' => 'Registró asistencia para 3°A',
                'time' => 'Hace 2 horas',
                'icon' => 'users'
            ],
            [
                'id' => 2,
                'description' => 'Calificó examen de Matemáticas',
                'time' => 'Ayer',
                'icon' => 'clipboard'
            ]
        ]);
    }

    public function getMainGrade(): ?Grade
    {
        return $this->grades()
                   ->wherePivot('is_main_teacher', true)
                   ->first();
    }

    public function canManageStudent(Student $student): bool
    {
        return $this->students()
                   ->where('student_id', $student->id)
                   ->exists() || 
               $this->getMainGrade()?->id === $student->grade_id;
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($teacher) {
            if (empty($teacher->employee_code)) {
                $teacher->employee_code = $teacher->generateEmployeeCode();
            }
        });
    }

    private function generateEmployeeCode(): string
    {
        $year = date('Y');
        $lastTeacher = static::whereYear('created_at', $year)
                            ->orderBy('employee_code', 'desc')
                            ->first();
        
        if (!$lastTeacher) {
            return 'T' . $year . '001';
        }
        
        $lastNumber = (int) substr($lastTeacher->employee_code, -3);
        return 'T' . $year . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }
}