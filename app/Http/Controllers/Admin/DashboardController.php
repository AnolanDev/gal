<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Academic\Student;
use App\Models\Academic\Teacher;
use App\Models\Academic\Grade;
use App\Models\Academic\Subject;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $stats = [
            'students' => Student::where('status', 'active')->count(),
            'teachers' => Teacher::count(),
            'grades' => Grade::where('status', 'active')->count(),
            'parents' => User::role('parent')->count(),
            'subjects' => Subject::where('status', 'active')->count(),
        ];

        $recentStudents = Student::with(['grade', 'parent'])
            ->where('status', 'active')
            ->latest()
            ->limit(5)
            ->get();

        $recentActivity = [
            [
                'id' => 1,
                'description' => 'Nuevo estudiante registrado: ' . ($recentStudents->first()->full_name ?? 'N/A'),
                'time' => 'Hace 2 horas',
                'icon' => 'user-plus',
                'type' => 'student'
            ],
            [
                'id' => 2,
                'description' => 'Calificaciones actualizadas para Matemáticas',
                'time' => 'Hace 4 horas',
                'icon' => 'edit',
                'type' => 'grade'
            ],
            [
                'id' => 3,
                'description' => 'Nuevo docente asignado: María González',
                'time' => 'Ayer',
                'icon' => 'user-check',
                'type' => 'teacher'
            ],
            [
                'id' => 4,
                'description' => 'Reporte de asistencia generado',
                'time' => 'Hace 2 días',
                'icon' => 'file-text',
                'type' => 'report'
            ]
        ];

        return Inertia::render('admin/Dashboard', [
            'stats' => $stats,
            'recentStudents' => $recentStudents,
            'recentActivity' => $recentActivity,
        ]);
    }
}