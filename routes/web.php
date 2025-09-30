<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\GeografiaController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Estudiantes routes
    Route::resource('estudiantes', EstudianteController::class);
    Route::get('/estudiantes/{estudiante}/profile', [EstudianteController::class, 'profile'])->name('estudiantes.profile');
    Route::patch('/estudiantes/{estudiante}/status', [EstudianteController::class, 'updateStatus'])->name('estudiantes.update-status');
    Route::patch('/estudiantes/bulk-status', [EstudianteController::class, 'bulkUpdateStatus'])->name('estudiantes.bulk-update-status');
    Route::get('/api/estudiantes/search', [EstudianteController::class, 'search'])->name('estudiantes.search');
    
    // API routes for geografia (cascading selects)
    Route::prefix('api/geografia')->group(function () {
        Route::get('/countries', [GeografiaController::class, 'getCountries']);
        Route::get('/countries/{country}/states', [GeografiaController::class, 'getStates']);
        Route::get('/countries/{countryId}/states-by-id', [GeografiaController::class, 'getStatesByCountryId']);
        Route::get('/states/{state}/cities', [GeografiaController::class, 'getCities']);
        Route::get('/states/{stateId}/cities-by-id', [GeografiaController::class, 'getCitiesByStateId']);
        Route::get('/search', [GeografiaController::class, 'searchPlaces']);
        Route::get('/full-location/{cityId}', [GeografiaController::class, 'getFullLocation']);
    });
});

require __DIR__.'/auth.php';
