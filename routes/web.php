<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\AnalysisIAController;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    Route::get('/usuarios', function () {
        return Inertia::render('Usuarios');
    })->name('usuarios');
    Route::get('/register', function () {
        return Inertia::render('Register');
    })->name('register');
    Route::get('/profesores', function () {
        return Inertia::render('Profesores');
    })->name('profesores');
    Route::get('/analysis-ia', function () {
        return Inertia::render('AyudaIA');
    })->name('analysis-ia');
    Route::get('/reports', function () {
        return Inertia::render('Reportes');
    })->name('reports');
    Route::get('/students', function () {
        return Inertia::render('Estudiantes');
    })->name('students');
    Route::get('/courses', function () {
        return Inertia::render('Cursos');
    })->name('courses');
    // Auth 
    Route::get('/authenticated-user', [UserController::class, 'authenticatedUser']);
    // Rutas profesores
    Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');
    Route::post('/teachers', [TeacherController::class, 'store'])->name('teachers.store');
    Route::put('/teachers/{teacher}', [TeacherController::class, 'update'])->name('teachers.update');
    Route::delete('/teachers/{teacher}', [TeacherController::class, 'destroy'])->name('teachers.destroy');

    // Rutas cursos
    Route::get('/course', [CourseController::class, 'index'])->name('course.index');
    Route::post('/course', [CourseController::class, 'store'])->name('course.store');
    Route::put('/course/{course}', [CourseController::class, 'update'])->name('course.update');
    Route::delete('/course/{course}', [CourseController::class, 'destroy'])->name('course.destroy');
    Route::get('/cursos/all', [CourseController::class, 'all'])->name('cursos.all');

    // Rutas estudiantes
    Route::get('/estudiantes', [StudentController::class, 'index'])->name('estudiantes.index');
    Route::post('/estudiantes', [StudentController::class, 'store'])->name('estudiantes.store');
    Route::put('/estudiantes/{student}', [StudentController::class, 'update'])->name('estudiantes.update');
    Route::delete('/estudiantes/{student}', [StudentController::class, 'destroy'])->name('estudiantes.destroy');
});