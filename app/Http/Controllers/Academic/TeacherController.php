<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Models\Academic\Teacher;
use App\Models\Academic\Grade;
use App\Models\Academic\Subject;
use App\Http\Requests\Academic\StoreTeacherRequest;
use App\Http\Requests\Academic\UpdateTeacherRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Teacher::class, 'teacher');
    }

    public function index(Request $request): Response
    {
        $query = Teacher::with(['user', 'grades', 'subjects'])
                       ->latest();

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('identification_number', 'like', "%{$search}%");
        }

        if ($request->filled('subject_id')) {
            $query->whereHas('subjects', function ($q) use ($request) {
                $q->where('subjects.id', $request->input('subject_id'));
            });
        }

        $teachers = $query->paginate(15)->withQueryString();

        return Inertia::render('Academic/Teachers/Index', [
            'teachers' => $teachers,
            'subjects' => Subject::active()->get(),
            'filters' => $request->only(['search', 'subject_id']),
            'can' => [
                'create' => $request->user()->can('create', Teacher::class),
                'export' => $request->user()->can('export', Teacher::class),
            ],
        ]);
    }

    public function show(Teacher $teacher): Response
    {
        $teacher->load([
            'user',
            'grades.students',
            'subjects',
        ]);

        return Inertia::render('Academic/Teachers/Show', [
            'teacher' => $teacher,
            'can' => [
                'edit' => auth()->user()->can('update', $teacher),
                'delete' => auth()->user()->can('delete', $teacher),
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Academic/Teachers/Create', [
            'grades' => Grade::active()->get(),
            'subjects' => Subject::active()->get(),
        ]);
    }

    public function store(StoreTeacherRequest $request)
    {
        $teacher = Teacher::create($request->validated());

        // Sync relationships if provided
        if ($request->filled('grade_ids')) {
            $teacher->grades()->sync($request->input('grade_ids'));
        }

        if ($request->filled('subject_ids')) {
            $teacher->subjects()->sync($request->input('subject_ids'));
        }

        return redirect()
            ->route('teachers.show', $teacher)
            ->with('success', 'Docente creado exitosamente.');
    }

    public function edit(Teacher $teacher): Response
    {
        return Inertia::render('Academic/Teachers/Edit', [
            'teacher' => $teacher->load(['grades', 'subjects']),
            'grades' => Grade::active()->get(),
            'subjects' => Subject::active()->get(),
        ]);
    }

    public function update(UpdateTeacherRequest $request, Teacher $teacher)
    {
        $teacher->update($request->validated());

        // Sync relationships if provided
        if ($request->filled('grade_ids')) {
            $teacher->grades()->sync($request->input('grade_ids'));
        }

        if ($request->filled('subject_ids')) {
            $teacher->subjects()->sync($request->input('subject_ids'));
        }

        return redirect()
            ->route('teachers.show', $teacher)
            ->with('success', 'Docente actualizado exitosamente.');
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->delete();

        return redirect()
            ->route('teachers.index')
            ->with('success', 'Docente eliminado exitosamente.');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        
        $teachers = Teacher::with(['user'])
                          ->whereHas('user', function ($q) use ($query) {
                              $q->where('name', 'like', "%{$query}%")
                                ->orWhere('email', 'like', "%{$query}%");
                          })
                          ->orWhere('identification_number', 'like', "%{$query}%")
                          ->limit(10)
                          ->get();
        
        return response()->json($teachers);
    }
}