<?php

namespace App\Models\Academic;

use App\Models\Evaluation\GradeReport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'area',
        'is_main_subject',
        'hours_per_week',
        'academic_level',
        'status',
        'color',
    ];

    protected $casts = [
        'is_main_subject' => 'boolean',
        'hours_per_week' => 'integer',
    ];

    protected $appends = [
        'display_name',
    ];

    // Relationships
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'teacher_subject')
                    ->withPivot('grade_id', 'academic_period_id')
                    ->withTimestamps();
    }

    public function grades(): BelongsToMany
    {
        return $this->belongsToMany(Grade::class, 'grade_subject')
                    ->withPivot('hours_per_week', 'academic_period_id')
                    ->withTimestamps();
    }

    public function gradeReports(): HasMany
    {
        return $this->hasMany(GradeReport::class);
    }

    // Accessors
    public function getDisplayNameAttribute(): string
    {
        return $this->code ? "{$this->name} ({$this->code})" : $this->name;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeMainSubjects($query)
    {
        return $query->where('is_main_subject', true);
    }

    public function scopeByArea($query, $area)
    {
        return $query->where('area', $area);
    }

    public function scopeByAcademicLevel($query, $level)
    {
        return $query->where('academic_level', $level);
    }

    // Helper Methods
    public function getTeacherForGrade(Grade $grade): ?Teacher
    {
        return $this->teachers()
                   ->wherePivot('grade_id', $grade->id)
                   ->first();
    }

    public function getGradeAverage(Grade $grade, $academicPeriod = null): float
    {
        $query = $this->gradeReports()
                     ->whereHas('student', function ($q) use ($grade) {
                         $q->where('grade_id', $grade->id);
                     });

        if ($academicPeriod) {
            $query->where('academic_period_id', $academicPeriod);
        }

        return $query->avg('numerical_grade') ?? 0.0;
    }

    public function getStudentCount(Grade $grade = null): int
    {
        if ($grade) {
            return $grade->students()->active()->count();
        }

        return $this->grades()
                   ->withCount(['students' => function ($query) {
                       $query->active();
                   }])
                   ->get()
                   ->sum('students_count');
    }

    public static function getSubjectAreas(): array
    {
        return [
            'mathematics' => 'Matemáticas',
            'language' => 'Lenguaje',
            'science' => 'Ciencias',
            'social_studies' => 'Sociales',
            'arts' => 'Artes',
            'physical_education' => 'Educación Física',
            'english' => 'Inglés',
            'religion' => 'Religión',
            'ethics' => 'Ética',
            'technology' => 'Tecnología',
        ];
    }

    public static function getAcademicLevels(): array
    {
        return [
            'preschool' => 'Preescolar',
            'elementary' => 'Básica Primaria',
            'middle' => 'Básica Secundaria',
            'high' => 'Media',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($subject) {
            if (empty($subject->code)) {
                $subject->code = $subject->generateSubjectCode();
            }
        });
    }

    private function generateSubjectCode(): string
    {
        // Generate a code based on the subject name and area
        $nameCode = strtoupper(substr($this->name, 0, 3));
        $areaCode = strtoupper(substr($this->area, 0, 2));
        
        $lastSubject = static::where('code', 'like', $nameCode . $areaCode . '%')
                            ->orderBy('code', 'desc')
                            ->first();
        
        if (!$lastSubject) {
            return $nameCode . $areaCode . '01';
        }
        
        $lastNumber = (int) substr($lastSubject->code, -2);
        return $nameCode . $areaCode . str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT);
    }
}