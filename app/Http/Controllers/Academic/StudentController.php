<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Models\Academic\Student;
use App\Models\Academic\Grade;
use App\Http\Requests\Academic\StoreStudentRequest;
use App\Http\Requests\Academic\UpdateStudentRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Student::class, 'student');
    }

    public function index(Request $request): Response
    {
        $query = Student::with(['grade', 'parent'])
                       ->active()
                       ->latest();

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('identification_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('grade_id')) {
            $query->where('grade_id', $request->input('grade_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Apply role-based filtering
        if ($request->user()->hasRole('teacher')) {
            $teacher = $request->user()->teacher;
            if ($teacher) {
                $gradeIds = $teacher->grades()->pluck('grades.id');
                $query->whereIn('grade_id', $gradeIds);
            }
        } elseif ($request->user()->hasRole('parent')) {
            $query->where('parent_user_id', $request->user()->id);
        }

        $students = $query->paginate(15)->withQueryString();

        return Inertia::render('Academic/Students/Index', [
            'students' => $students,
            'grades' => Grade::active()->get(),
            'filters' => $request->only(['search', 'grade_id', 'status']),
            'can' => [
                'create' => $request->user()->can('create', Student::class),
                'export' => $request->user()->can('export', Student::class),
            ],
        ]);
    }

    public function show(Student $student): Response
    {
        $student->load([
            'grade',
            'parent',
            'attendances' => fn($q) => $q->latest()->limit(10),
            'gradeReports' => fn($q) => $q->with('subject')->latest()->limit(10),
            'observations' => fn($q) => $q->with('teacher')->latest()->limit(5),
        ]);

        return Inertia::render('Academic/Students/Show', [
            'student' => $student,
            'recentGrades' => $student->getRecentGrades(10),
            'attendanceRate' => $student->attendance_rate,
            'can' => [
                'edit' => auth()->user()->can('update', $student),
                'delete' => auth()->user()->can('delete', $student),
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Academic/Students/Create', [
            'grades' => Grade::active()->get(),
            'parents' => $this->getAvailableParents(),
        ]);
    }

    public function store(StoreStudentRequest $request)
    {
        $student = Student::create($request->validated());

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Estudiante creado exitosamente.');
    }

    public function edit(Student $student): Response
    {
        return Inertia::render('Academic/Students/Edit', [
            'student' => $student->load('grade', 'parent'),
            'grades' => Grade::active()->get(),
            'parents' => $this->getAvailableParents(),
        ]);
    }

    public function update(UpdateStudentRequest $request, Student $student)
    {
        $student->update($request->validated());

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Estudiante actualizado exitosamente.');
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()
            ->route('students.index')
            ->with('success', 'Estudiante eliminado exitosamente.');
    }

    public function parentDashboard()
    {
        $user = auth()->user();
        $students = $user->children()->with('grade')->get();
        
        $recentGrades = collect();
        $recentCommunications = collect();
        $pendingTasks = 0;
        
        if ($students->isNotEmpty()) {
            // Get recent grades for all children
            $recentGrades = \App\Models\Evaluation\GradeReport::whereIn('student_id', $students->pluck('id'))
                ->with(['subject', 'student'])
                ->latest()
                ->limit(10)
                ->get();
            
            // Mock communications - replace with actual model when implemented
            $recentCommunications = collect([
                [
                    'id' => 1,
                    'title' => 'Reunión de padres',
                    'excerpt' => 'Se realizará el próximo viernes...',
                    'date' => now()->subDays(2)->format('M d'),
                    'sender' => 'Coordinación'
                ]
            ]);
            
            $pendingTasks = 3; // Mock data
        }
        
        return Inertia::render('parent/Dashboard', [
            'students' => $students,
            'recentGrades' => $recentGrades,
            'recentCommunications' => $recentCommunications,
            'pendingTasks' => $pendingTasks,
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        
        $students = Student::with(['grade', 'parent'])
                          ->where('first_name', 'like', "%{$query}%")
                          ->orWhere('last_name', 'like', "%{$query}%")
                          ->orWhere('code', 'like', "%{$query}%")
                          ->orWhere('identification_number', 'like', "%{$query}%")
                          ->limit(10)
                          ->get();
        
        return response()->json($students);
    }

    public function transfer(Request $request, Student $student)
    {
        $request->validate([
            'new_grade_id' => 'required|exists:grades,id',
            'reason' => 'required|string|max:500',
        ]);

        $newGrade = Grade::findOrFail($request->new_grade_id);
        
        $studentService = app(\App\Services\Academic\StudentService::class);
        $studentService->transferStudent($student, $newGrade, $request->reason);

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Estudiante transferido exitosamente.');
    }

    public function academicSummary(Student $student)
    {
        $this->authorize('view', $student);
        
        $studentService = app(\App\Services\Academic\StudentService::class);
        $summary = $studentService->getStudentAcademicSummary($student);
        
        return response()->json($summary);
    }

    private function getAvailableParents()
    {
        return \App\Models\User::role('parent')
                              ->get(['id', 'name', 'email']);
    }
}