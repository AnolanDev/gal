<?php

use App\Http\Controllers\Academic\StudentController;
use App\Http\Controllers\Academic\TeacherController;
use App\Http\Controllers\Academic\GradeController;
use App\Http\Controllers\Academic\SubjectController;
use Illuminate\Support\Facades\Route;

// Student Management Routes
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Students
    Route::resource('students', StudentController::class);
    Route::get('students/{student}/academic-summary', [StudentController::class, 'academicSummary'])
        ->name('students.academic-summary');
    Route::post('students/{student}/transfer', [StudentController::class, 'transfer'])
        ->name('students.transfer');
    
    // Teachers
    Route::resource('teachers', TeacherController::class);
    Route::get('teachers/{teacher}/schedule', [TeacherController::class, 'schedule'])
        ->name('teachers.schedule');
    Route::get('teachers/{teacher}/students', [TeacherController::class, 'students'])
        ->name('teachers.students');
    
    // Grades
    Route::resource('grades', GradeController::class);
    Route::get('grades/{grade}/students', [GradeController::class, 'students'])
        ->name('grades.students');
    Route::get('grades/{grade}/reports', [GradeController::class, 'reports'])
        ->name('grades.reports');
    
    // Subjects
    Route::resource('subjects', SubjectController::class);
    Route::get('subjects/{subject}/grades', [SubjectController::class, 'grades'])
        ->name('subjects.grades');
    
    // Quick search endpoints
    Route::get('search/students', [StudentController::class, 'search'])
        ->name('search.students');
    Route::get('search/teachers', [TeacherController::class, 'search'])
        ->name('search.teachers');
});

// Teacher Dashboard Routes
Route::middleware(['auth', 'verified', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\Academic\TeacherDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('my-students', [\App\Http\Controllers\Academic\TeacherDashboardController::class, 'myStudents'])->name('my-students');
    Route::get('my-grades', [\App\Http\Controllers\Academic\TeacherDashboardController::class, 'myGrades'])->name('my-grades');
});

// Parent Dashboard Routes
Route::middleware(['auth', 'verified', 'role:parent'])->prefix('parent')->name('parent.')->group(function () {
    Route::get('dashboard', [StudentController::class, 'parentDashboard'])->name('dashboard');
    Route::get('children', [StudentController::class, 'parentChildren'])->name('children');
    Route::get('children/{student}', [StudentController::class, 'childProfile'])
        ->name('child.profile')
        ->middleware('can:view,student');
});