<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;

Route::get('/', function () {
    return redirect()->route('login');
});

//Registro de estudiantes
Route::post('/student/register', [StudentController::class, 'create'])->name('student.create');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Admin routes
Route::prefix('dashboard')->middleware(['auth'])->group(function () {
    // Admin routes
    Route::prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    });

    // Teacher routes
    Route::prefix('teacher')->group(function () {
        Route::get('/', [TeacherController::class, 'index'])->name('teacher.index');
    });

    // Student routes
    Route::prefix('student')->name('student.')->middleware(['auth', 'role:student'])->group(function () {
        Route::get('/', [StudentController::class,'index'])->name('index');
    
        // Welcome route that doesn't need the profile check
        Route::get('/welcome', [StudentController::class,'welcome'])->name('welcome');
        Route::post('profile/update', [StudentController::class, 'update'])->name('profile.update');
        Route::get('calendar', [StudentController::class, 'calendar'])->name('calendar');
        Route::post('calendar/create', [StudentController::class, 'calendar_create'])->name('calendar.create');

        // Student dashboard
        Route::get('/dashboard', [App\Http\Controllers\Student\DashboardController::class, 'index'])->name('dashboard');
        
        // Courses routes
        Route::get('/courses', [App\Http\Controllers\Student\CourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/level/{id}', [App\Http\Controllers\Student\CourseController::class, 'showLevel'])->name('courses.level');
        Route::get('/courses/course/{id}', [App\Http\Controllers\Student\CourseController::class, 'showCourse'])->name('courses.course');
    });
});

// Magic Login Routes
Route::get('login/magic', [App\Http\Controllers\UserController::class, 'showMagicLoginForm'])->name('magic.login');
Route::post('/login/magic', [App\Http\Controllers\UserController::class, 'sendMagicLink'])->name('magic.link.send');
Route::get('/login/magic/{token}', [App\Http\Controllers\UserController::class, 'magicLogin'])->name('magic.login.callback');
