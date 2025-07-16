<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\MaterialController;

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
    Route::prefix('admin')->middleware(['role:admin'])->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.index');
        
        // User Management Routes
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
        
        // Payment Management Routes
        Route::get('/payments', [AdminController::class, 'payments'])->name('admin.payments');
        Route::get('/payments/create', [AdminController::class, 'createPayment'])->name('admin.payments.create');
        Route::post('/payments', [AdminController::class, 'storePayment'])->name('admin.payments.store');
        Route::get('/payments/{payment}/edit', [AdminController::class, 'editPayment'])->name('admin.payments.edit');
        Route::put('/payments/{payment}', [AdminController::class, 'updatePayment'])->name('admin.payments.update');
        Route::delete('/payments/{payment}', [AdminController::class, 'destroyPayment'])->name('admin.payments.destroy');
        Route::patch('/payments/{payment}/status', [AdminController::class, 'updatePaymentStatus'])->name('admin.payments.updateStatus');
        
        // Enrollment Management Routes
        Route::get('/enrollments', [AdminController::class, 'enrollments'])->name('admin.enrollments');
        Route::get('/enrollments/create', [AdminController::class, 'createEnrollment'])->name('admin.enrollments.create');
        Route::post('/enrollments', [AdminController::class, 'storeEnrollment'])->name('admin.enrollments.store');
        Route::get('/enrollments/{enrollment}/edit', [AdminController::class, 'editEnrollment'])->name('admin.enrollments.edit');
        Route::put('/enrollments/{enrollment}', [AdminController::class, 'updateEnrollment'])->name('admin.enrollments.update');
        Route::delete('/enrollments/{enrollment}', [AdminController::class, 'destroyEnrollment'])->name('admin.enrollments.destroy');
        Route::patch('/enrollments/{enrollment}/status', [AdminController::class, 'updateEnrollmentStatus'])->name('admin.enrollments.updateStatus');
        
        // Course Management Routes
        Route::get('/courses', [AdminController::class, 'courses'])->name('admin.courses.index');
        Route::get('/courses/create', [AdminController::class, 'createCourse'])->name('admin.courses.create');
        Route::post('/courses', [AdminController::class, 'storeCourse'])->name('admin.courses.store');
        Route::get('/courses/{course}/edit', [AdminController::class, 'editCourse'])->name('admin.courses.edit');
        Route::put('/courses/{course}', [AdminController::class, 'updateCourse'])->name('admin.courses.update');
        Route::delete('/courses/{course}', [AdminController::class, 'destroyCourse'])->name('admin.courses.destroy');
        
        // Course Lessons Management
        Route::get('/courses/{course}/lessons', [AdminController::class, 'courseLessons'])->name('admin.courses.lessons');
        Route::get('/courses/{course}/lessons/create', [AdminController::class, 'createLesson'])->name('admin.courses.lessons.create');
        Route::post('/courses/{course}/lessons', [AdminController::class, 'storeLesson'])->name('admin.courses.lessons.store');
        Route::get('/lessons/{lesson}/edit', [AdminController::class, 'editLesson'])->name('admin.lessons.edit');
        Route::put('/lessons/{lesson}', [AdminController::class, 'updateLesson'])->name('admin.lessons.update');
        Route::delete('/lessons/{lesson}', [AdminController::class, 'destroyLesson'])->name('admin.lessons.destroy');
        
        // Levels Management
        Route::get('/levels', [AdminController::class, 'levels'])->name('admin.levels');
        Route::post('/levels', [AdminController::class, 'storeLevel'])->name('admin.levels.store');
        Route::put('/levels/{level}', [AdminController::class, 'updateLevel'])->name('admin.levels.update');
        Route::delete('/levels/{level}', [AdminController::class, 'destroyLevel'])->name('admin.levels.destroy');
    });

    // Teacher routes
    Route::prefix('teacher')->group(function () {
        Route::get('/', [TeacherController::class, 'index'])->name('teacher.index');
    });

    // Student routes
    Route::prefix('student')->name('student.')->middleware(['auth', 'role:student'])->group(function () {
        // Routes that use the profile completion middleware for conditional access
        Route::middleware('student.profile.complete')->group(function() {
            Route::get('/', [StudentController::class,'index'])->name('index');
            Route::get('/courses', [App\Http\Controllers\Student\CourseController::class, 'index'])->name('courses.index');
            Route::get('/courses/level/{id}', [App\Http\Controllers\Student\CourseController::class, 'showLevel'])->name('courses.level');
            Route::get('/courses/course/{id}', [App\Http\Controllers\Student\CourseController::class, 'showCourse'])->name('courses.course');
        
            // Routes that are always accessible regardless of profile completion
            Route::get('welcome', [StudentController::class,'welcome'])->name('welcome');
            Route::post('profile/update', [StudentController::class, 'update'])->name('profile.update');
            Route::get('profile/edit', [StudentController::class, 'editProfile'])->name('profile.edit');
            Route::get('calendar', [StudentController::class, 'calendar'])->name('calendar');
            Route::post('calendar/create', [StudentController::class, 'calendar_create'])->name('calendar.create');
            Route::post('update/timezone', [StudentController::class, 'updateTimezone'])->name('update.timezone');
            Route::delete('video-calls/{videoCall}/cancel', [StudentController::class, 'cancelVideoCall'])->name('video-calls.cancel');
        });
    });
});

// Schedule management routes (for teachers and admins)
Route::prefix('schedule')->middleware(['auth', 'role:teacher|admin'])->name('schedule.')->group(function () {
    Route::get('/', [App\Http\Controllers\ScheduleController::class, 'index'])->name('index');
    Route::post('/', [App\Http\Controllers\ScheduleController::class, 'store'])->name('store');
    Route::put('/{schedule}', [App\Http\Controllers\ScheduleController::class, 'update'])->name('update');
    Route::delete('/{schedule}', [App\Http\Controllers\ScheduleController::class, 'destroy'])->name('destroy');
    Route::post('/bulk-create', [App\Http\Controllers\ScheduleController::class, 'bulkCreate'])->name('bulk-create');
    Route::get('/statistics', [App\Http\Controllers\ScheduleController::class, 'getStatistics'])->name('statistics');
    Route::get('/teacher-appointments', [App\Http\Controllers\ScheduleController::class, 'teacherAppointments'])->name('teacher-appointments');
    
    // Schedule blocks management
    Route::post('/block', [App\Http\Controllers\ScheduleController::class, 'createBlock'])->name('create-block');
    Route::delete('/blocks/{block}', [App\Http\Controllers\ScheduleController::class, 'deleteBlock'])->name('delete-block');
});

// Magic Login Routes
Route::get('login/magic', [App\Http\Controllers\UserController::class, 'showMagicLoginForm'])->name('magic.login');
Route::post('/login/magic', [App\Http\Controllers\UserController::class, 'sendMagicLink'])->name('magic.link.send');
Route::get('/login/magic/{token}', [App\Http\Controllers\UserController::class, 'magicLogin'])->name('magic.login.callback');
Route::get('/login/magic/{token}', [App\Http\Controllers\UserController::class, 'magicLogin'])->name('magic.login.callback');

// Course and LMS routes
Route::middleware(['auth'])->group(function () {
    // Public course browsing
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
    Route::get('/courses/{course}/curriculum', [CourseController::class, 'curriculum'])->name('courses.curriculum');
    Route::get('/courses/{course}/reviews', [CourseController::class, 'reviews'])->name('courses.reviews');
    
    // Course reviews
    Route::post('/courses/{course}/review', [CourseController::class, 'addReview'])->name('courses.review');
    
    // Payment routes
    Route::get('/courses/{course}/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::post('/courses/{course}/payment', [PaymentController::class, 'processPayment'])->name('payment.process');
    Route::get('/payment/{payment}/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/history', [PaymentController::class, 'history'])->name('payment.history');
    
    // Lesson routes (requires course purchase)
    Route::get('/courses/{course}/lessons/{lesson}', [LessonController::class, 'show'])->name('lessons.show');
    Route::post('/courses/{course}/lessons/{lesson}/progress', [LessonController::class, 'updateProgress'])->name('lessons.progress');
    Route::post('/courses/{course}/lessons/{lesson}/complete', [LessonController::class, 'complete'])->name('lessons.complete');
    Route::post('/courses/{course}/lessons/{lesson}/comment', [LessonController::class, 'addComment'])->name('lessons.comment');
    
    // Material download routes
    Route::get('/materials/{material}/download', [MaterialController::class, 'download'])->name('materials.download');
    
    // Admin/Teacher material management
    Route::post('/materials/upload', [MaterialController::class, 'upload'])->name('materials.upload')->middleware('role:admin|teacher');
    Route::delete('/materials/{material}', [MaterialController::class, 'destroy'])->name('materials.destroy')->middleware('role:admin|teacher');
    
    // Comment management
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::get('/teachers/{teacher}/reviews', [CommentController::class, 'teacherReviews'])->name('teachers.reviews');
});

// Webhook for payment notifications (no auth required)
Route::post('/payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');

// Temporary debug route - remove after fixing the issue
Route::get('/debug-student-status', function() {
    if (!Auth::check()) {
        return response()->json(['error' => 'Not authenticated']);
    }
    
    $user = Auth::user();
    $profileComplete = $user->country && $user->state && $user->city;
    $hasWelcomeCall = \App\Models\VideoCall::userHasWelcomeCall($user->id);
    $welcomeCall = \App\Models\VideoCall::forUser($user->id)->welcome()->first();
    
    return response()->json([
        'user_id' => $user->id,
        'name' => $user->name,
        'country' => $user->country,
        'state' => $user->state, 
        'city' => $user->city,
        'profile_complete' => $profileComplete,
        'has_welcome_call' => $hasWelcomeCall,
        'welcome_call' => $welcomeCall,
        'all_video_calls' => \App\Models\VideoCall::where('user_id', $user->id)->get()
    ]);
})->middleware('auth')->name('debug.student.status');
