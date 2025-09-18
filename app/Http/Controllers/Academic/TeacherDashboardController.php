<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Models\Academic\Student;
use App\Models\Academic\Teacher;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TeacherDashboardController extends Controller
{
    public function dashboard(Request $request): Response
    {
        $user = $request->user();
        $teacher = $user->teacher;

        if (!$teacher) {
            return Inertia::render('teacher/Dashboard', [
                'todayClasses' => [],
                'recentActivity' => [],
                'myStudents' => 0,
                'myGrades' => 0,
                'mySubjects' => 0,
            ]);
        }

        // Get teacher's students count
        $gradeIds = $teacher->grades()->pluck('grades.id');
        $studentsCount = Student::whereIn('grade_id', $gradeIds)->where('status', 'active')->count();

        // Get teacher's grades and subjects count
        $gradesCount = $teacher->grades()->count();
        $subjectsCount = $teacher->subjects()->count();

        // Mock today's classes - replace with real data when schedule system is implemented
        $todayClasses = [
            [
                'id' => 1,
                'subject' => 'Matemáticas',
                'grade' => 'Quinto A',
                'time' => '08:00 - 09:00',
                'classroom' => '201',
                'status' => 'completed'
            ],
            [
                'id' => 2,
                'subject' => 'Ciencias',
                'grade' => 'Quinto B',
                'time' => '10:00 - 11:00',
                'classroom' => '203',
                'status' => 'in-progress'
            ],
            [
                'id' => 3,
                'subject' => 'Matemáticas',
                'grade' => 'Sexto A',
                'time' => '14:00 - 15:00',
                'classroom' => '201',
                'status' => 'pending'
            ]
        ];

        // Mock recent activity
        $recentActivity = [
            [
                'id' => 1,
                'description' => 'Calificaciones registradas para Matemáticas - Quinto A',
                'time' => 'Hace 30 minutos',
                'icon' => 'edit'
            ],
            [
                'id' => 2,
                'description' => 'Asistencia tomada para Ciencias - Quinto B',
                'time' => 'Hace 2 horas',
                'icon' => 'users'
            ],
            [
                'id' => 3,
                'description' => 'Observación agregada para estudiante Juan Pérez',
                'time' => 'Ayer',
                'icon' => 'message-circle'
            ]
        ];

        return Inertia::render('teacher/Dashboard', [
            'todayClasses' => $todayClasses,
            'recentActivity' => $recentActivity,
            'myStudents' => $studentsCount,
            'myGrades' => $gradesCount,
            'mySubjects' => $subjectsCount,
        ]);
    }

    public function myStudents(Request $request): Response
    {
        $user = $request->user();
        $teacher = $user->teacher;

        if (!$teacher) {
            return redirect()->route('teacher.dashboard');
        }

        $gradeIds = $teacher->grades()->pluck('grades.id');
        
        $students = Student::with(['grade', 'parent'])
            ->whereIn('grade_id', $gradeIds)
            ->where('status', 'active')
            ->paginate(15);

        return Inertia::render('Academic/Students/Index', [
            'students' => $students,
            'grades' => $teacher->grades,
            'filters' => $request->only(['search', 'grade_id', 'status']),
            'can' => [
                'create' => false, // Teachers can't create students
                'export' => $request->user()->can('export', Student::class),
            ],
        ]);
    }

    public function myGrades(Request $request): Response
    {
        $user = $request->user();
        $teacher = $user->teacher;

        if (!$teacher) {
            return redirect()->route('teacher.dashboard');
        }

        $grades = $teacher->grades()->with('students')->get();

        return Inertia::render('Academic/Grades/Index', [
            'grades' => $grades,
            'can' => [
                'create' => false, // Teachers can't create grades
                'edit' => false,
            ],
        ]);
    }
}