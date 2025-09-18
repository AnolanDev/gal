<?php

namespace App\Models\Academic;

use App\Models\User;
use App\Models\Evaluation\Attendance;
use App\Models\Evaluation\GradeReport;
use App\Models\Evaluation\Observation;
use App\Models\Payment\Payment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'identification_number',
        'identification_type',
        'first_name',
        'last_name',
        'birth_date',
        'gender',
        'address',
        'phone',
        'emergency_contact',
        'emergency_phone',
        'blood_type',
        'medical_conditions',
        'grade_id',
        'parent_user_id',
        'enrollment_date',
        'status',
        'photo_path',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'enrollment_date' => 'date',
        'medical_conditions' => 'array',
    ];

    protected $appends = [
        'full_name',
        'age',
        'attendance_rate',
        'average_grade',
    ];

    // Relationships
    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_user_id');
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

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'teacher_student')
                    ->withPivot('subject_id', 'academic_period_id')
                    ->withTimestamps();
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getAgeAttribute(): int
    {
        return $this->birth_date->age;
    }

    public function getAttendanceRateAttribute(): float
    {
        $totalDays = $this->attendances()->count();
        if ($totalDays === 0) return 100.0;
        
        $presentDays = $this->attendances()->where('status', 'present')->count();
        return round(($presentDays / $totalDays) * 100, 1);
    }

    public function getAverageGradeAttribute(): string
    {
        $average = $this->gradeReports()
                       ->where('academic_period_id', $this->getCurrentAcademicPeriod())
                       ->avg('numerical_grade');
        
        return $average ? number_format($average, 1) : 'N/A';
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByGrade($query, $gradeId)
    {
        return $query->where('grade_id', $gradeId);
    }

    public function scopeByParent($query, $parentId)
    {
        return $query->where('parent_user_id', $parentId);
    }

    // Helper Methods
    public function getCurrentAcademicPeriod(): ?int
    {
        // This would get the current academic period
        // Implementation depends on your academic calendar setup
        return 1; // Placeholder
    }

    public function getRecentGrades(int $limit = 10)
    {
        return $this->gradeReports()
                   ->with(['subject', 'teacher'])
                   ->latest()
                   ->limit($limit)
                   ->get();
    }

    public function getPendingTasks(): int
    {
        // This would calculate pending homework/tasks
        // Implementation depends on your task management system
        return 0; // Placeholder
    }

    public function generateStudentCode(): string
    {
        $year = date('Y');
        $lastStudent = static::whereYear('created_at', $year)
                           ->orderBy('code', 'desc')
                           ->first();
        
        if (!$lastStudent) {
            return $year . '001';
        }
        
        $lastNumber = (int) substr($lastStudent->code, -3);
        return $year . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($student) {
            if (empty($student->code)) {
                $student->code = $student->generateStudentCode();
            }
        });
    }
}