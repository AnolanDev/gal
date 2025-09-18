<?php

namespace App\Services\Academic;

use App\Models\Academic\Student;
use App\Models\Academic\Grade;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class StudentService
{
    public function createStudent(array $data): Student
    {
        return DB::transaction(function () use ($data) {
            // Validate grade capacity
            $grade = Grade::findOrFail($data['grade_id']);
            
            if (!$grade->canEnrollStudent()) {
                throw new \Exception('El grado seleccionado no tiene cupos disponibles.');
            }

            // Handle photo upload if provided
            if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
                $data['photo_path'] = $this->uploadStudentPhoto($data['photo']);
            }

            // Create student
            $student = Student::create($data);

            // Log the creation
            activity()
                ->causedBy(auth()->user())
                ->performedOn($student)
                ->log('Estudiante creado');

            // Send welcome notification to parent
            $this->sendWelcomeNotification($student);

            return $student;
        });
    }

    public function updateStudent(Student $student, array $data): Student
    {
        return DB::transaction(function () use ($student, $data) {
            $originalGradeId = $student->grade_id;

            // Handle photo upload if provided
            if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
                // Delete old photo
                if ($student->photo_path) {
                    Storage::disk('public')->delete($student->photo_path);
                }
                
                $data['photo_path'] = $this->uploadStudentPhoto($data['photo']);
            }

            // Update student
            $student->update($data);

            // If grade changed, validate new grade capacity
            if (isset($data['grade_id']) && $data['grade_id'] !== $originalGradeId) {
                $newGrade = Grade::findOrFail($data['grade_id']);
                
                if (!$newGrade->canEnrollStudent()) {
                    throw new \Exception('El nuevo grado no tiene cupos disponibles.');
                }

                // Log grade change
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($student)
                    ->withProperties([
                        'old_grade' => $originalGradeId,
                        'new_grade' => $data['grade_id']
                    ])
                    ->log('Grado cambiado');
            }

            // Log the update
            activity()
                ->causedBy(auth()->user())
                ->performedOn($student)
                ->log('Estudiante actualizado');

            return $student->fresh();
        });
    }

    public function deleteStudent(Student $student): bool
    {
        return DB::transaction(function () use ($student) {
            // Check if student has active academic records
            if ($this->hasActiveAcademicRecords($student)) {
                throw new \Exception('No se puede eliminar un estudiante con registros académicos activos.');
            }

            // Delete photo if exists
            if ($student->photo_path) {
                Storage::disk('public')->delete($student->photo_path);
            }

            // Log the deletion
            activity()
                ->causedBy(auth()->user())
                ->performedOn($student)
                ->log('Estudiante eliminado');

            return $student->delete();
        });
    }

    public function transferStudent(Student $student, Grade $newGrade, string $reason = ''): Student
    {
        return DB::transaction(function () use ($student, $newGrade, $reason) {
            if (!$newGrade->canEnrollStudent()) {
                throw new \Exception('El grado de destino no tiene cupos disponibles.');
            }

            $oldGradeId = $student->grade_id;
            $student->update(['grade_id' => $newGrade->id]);

            // Log the transfer
            activity()
                ->causedBy(auth()->user())
                ->performedOn($student)
                ->withProperties([
                    'old_grade_id' => $oldGradeId,
                    'new_grade_id' => $newGrade->id,
                    'reason' => $reason
                ])
                ->log('Estudiante transferido de grado');

            return $student->fresh();
        });
    }

    public function getStudentsByGrade(Grade $grade): Collection
    {
        return $grade->students()
                    ->active()
                    ->with(['parent'])
                    ->orderBy('last_name')
                    ->orderBy('first_name')
                    ->get();
    }

    public function getStudentAcademicSummary(Student $student, int $academicPeriodId = null): array
    {
        $academicPeriodId = $academicPeriodId ?? $student->getCurrentAcademicPeriod();

        return [
            'attendance_rate' => $student->attendance_rate,
            'average_grade' => $student->average_grade,
            'recent_grades' => $student->getRecentGrades(10),
            'pending_tasks' => $student->getPendingTasks(),
            'observations_count' => $student->observations()
                                          ->where('academic_period_id', $academicPeriodId)
                                          ->count(),
            'positive_observations' => $student->observations()
                                             ->where('academic_period_id', $academicPeriodId)
                                             ->where('type', 'positive')
                                             ->count(),
            'negative_observations' => $student->observations()
                                             ->where('academic_period_id', $academicPeriodId)
                                             ->where('type', 'negative')
                                             ->count(),
        ];
    }

    public function searchStudents(string $query, array $filters = []): Collection
    {
        $searchQuery = Student::with(['grade', 'parent'])
                             ->where(function ($q) use ($query) {
                                 $q->where('first_name', 'like', "%{$query}%")
                                   ->orWhere('last_name', 'like', "%{$query}%")
                                   ->orWhere('code', 'like', "%{$query}%")
                                   ->orWhere('identification_number', 'like', "%{$query}%");
                             });

        // Apply filters
        if (isset($filters['grade_id'])) {
            $searchQuery->where('grade_id', $filters['grade_id']);
        }

        if (isset($filters['status'])) {
            $searchQuery->where('status', $filters['status']);
        }

        return $searchQuery->limit(50)->get();
    }

    private function uploadStudentPhoto(UploadedFile $photo): string
    {
        $filename = 'student_' . uniqid() . '.' . $photo->getClientOriginalExtension();
        return $photo->storeAs('students/photos', $filename, 'public');
    }

    private function hasActiveAcademicRecords(Student $student): bool
    {
        return $student->gradeReports()->exists() ||
               $student->attendances()->exists() ||
               $student->observations()->exists();
    }

    private function sendWelcomeNotification(Student $student): void
    {
        // Here you would implement notification logic
        // For example, sending an email to the parent
        
        // Example:
        // Mail::to($student->parent->email)->send(new WelcomeStudentMail($student));
        
        // Or using Laravel's notification system:
        // $student->parent->notify(new StudentEnrolledNotification($student));
    }
}