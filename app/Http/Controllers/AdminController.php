<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use App\Models\Level;
use App\Models\Lesson;
use App\Models\LessonMaterial;
use App\Models\Payment;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_courses' => Course::count(),
            'total_levels' => Level::count(),
            'total_students' => User::role('student')->count(),
            'total_teachers' => User::role('teacher')->count(),
            'total_payments' => Payment::count(),
            'total_enrollments' => Enrollment::count(),
            'completed_payments' => Payment::where('status', 'completed')->count(),
            'active_enrollments' => Enrollment::where('status', 'active')->count(),
            'recent_users' => User::latest()->take(5)->get(),
            'recent_courses' => Course::with('level', 'instructor')->latest()->take(5)->get(),
            'recent_payments' => Payment::with(['user', 'course'])->latest()->take(5)->get(),
            'recent_enrollments' => Enrollment::with(['user', 'course'])->latest()->take(5)->get(),
        ];

        return view('admin.index', compact('stats'));
    }

    /* USER MANAGEMENT */

    /**
     * Display users list
     */
    public function users(Request $request)
    {
        $query = User::with('roles');
        
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->role) {
            $query->role($request->role);
        }

        $users = $query->paginate(15);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show create user form
     */
    public function createUser()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store new user
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'phone' => 'nullable|string',
            'country' => 'nullable|string',
            'state' => 'nullable|string',
            'city' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
        ]);

        $user->assignRole($request->role);

        return redirect()->route('admin.users')->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Show edit user form
     */
    public function editUser(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update user
     */
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'phone' => 'nullable|string',
            'country' => 'nullable|string',
            'state' => 'nullable|string',
            'city' => 'nullable|string',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
        ];

        if ($request->password) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);
        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users')->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Delete user
     */
    public function destroyUser(User $user)
    {
        // Prevent deleting own account
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users')->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'Usuario eliminado exitosamente.');
    }

    /* COURSE MANAGEMENT */

    /**
     * Display courses list
     */
    public function courses(Request $request)
    {
        $query = Course::with('level', 'instructor', 'lessons');

        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->level) {
            $query->where('level_id', $request->level);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $courses = $query->paginate(15);
        $levels = Level::all();
        $instructors = User::role('teacher')->get();

        return view('admin.courses.index', compact('courses', 'levels', 'instructors'));
    }

    /**
     * Show create course form
     */
    public function createCourse()
    {
        $levels = Level::all();
        $instructors = User::role('teacher')->get();
        return view('admin.courses.create', compact('levels', 'instructors'));
    }

    /**
     * Store new course
     */
    public function storeCourse(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'nullable|string',
            'level_id' => 'required|exists:levels,id',
            'instructor_id' => 'required|exists:users,id',
            'status' => 'required|in:draft,active,inactive',
            'price' => 'nullable|numeric|min:0',
            'is_free' => 'boolean',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video_url' => 'nullable|url',
            'duration' => 'nullable|integer|min:1',
        ]);

        $courseData = $request->except(['cover_image']);
        $courseData['slug'] = Str::slug($request->title);
        $courseData['order'] = Course::where('level_id', $request->level_id)->count() + 1;
        $courseData['is_free'] = $request->has('is_free');

        if ($request->hasFile('cover_image')) {
            $courseData['cover_image'] = $request->file('cover_image')->store('courses', 'public');
        }

        Course::create($courseData);

        return redirect()->route('admin.courses.index')->with('success', 'Curso creado exitosamente.');
    }

    /**
     * Show edit course form
     */
    public function editCourse(Course $course)
    {
        $levels = Level::all();
        $instructors = User::role('teacher')->get();
        return view('admin.courses.edit', compact('course', 'levels', 'instructors'));
    }

    /**
     * Update course
     */
    public function updateCourse(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'nullable|string',
            'level_id' => 'required|exists:levels,id',
            'instructor_id' => 'required|exists:users,id',
            'status' => 'required|in:draft,active,inactive',
            'price' => 'nullable|numeric|min:0',
            'is_free' => 'boolean',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video_url' => 'nullable|url',
            'duration' => 'nullable|integer|min:1',
        ]);

        $courseData = $request->except(['cover_image']);
        $courseData['slug'] = Str::slug($request->title);
        $courseData['is_free'] = $request->has('is_free');

        if ($request->hasFile('cover_image')) {
            // Delete old image
            if ($course->cover_image) {
                Storage::disk('public')->delete($course->cover_image);
            }
            $courseData['cover_image'] = $request->file('cover_image')->store('courses', 'public');
        }

        $course->update($courseData);

        return redirect()->route('admin.courses.index')->with('success', 'Curso actualizado exitosamente.');
    }

    /**
     * Delete course
     */
    public function destroyCourse(Course $course)
    {
        // Delete cover image
        if ($course->cover_image) {
            Storage::disk('public')->delete($course->cover_image);
        }

        $course->delete();

        return redirect()->route('admin.courses.index')->with('success', 'Curso eliminado exitosamente.');
    }

    /* LESSON MANAGEMENT */

    /**
     * Display course lessons
     */
    public function courseLessons(Course $course)
    {
        $lessons = $course->lessons()->with('materials')->orderBy('order')->get();
        return view('admin.courses.lessons', compact('course', 'lessons'));
    }

    /**
     * Show create lesson form
     */
    public function createLesson(Course $course)
    {
        return view('admin.lessons.create', compact('course'));
    }

    /**
     * Store new lesson
     */
    public function storeLesson(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'required|string',
            'video_url' => 'nullable|url',
            'duration' => 'nullable|integer|min:1',
            'is_free' => 'boolean',
            'materials.*' => 'nullable|file|max:10240', // 10MB max
        ]);

        $lessonData = $request->except(['materials']);
        $lessonData['course_id'] = $course->id;
        $lessonData['order'] = $course->lessons()->count() + 1;
        $lessonData['is_free'] = $request->has('is_free');

        $lesson = Lesson::create($lessonData);

        // Handle file uploads
        if ($request->hasFile('materials')) {
            foreach ($request->file('materials') as $file) {
                $path = $file->store('lesson-materials', 'public');
                
                LessonMaterial::create([
                    'lesson_id' => $lesson->id,
                    'name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ]);
            }
        }

        return redirect()->route('admin.courses.lessons', $course)->with('success', 'Lección creada exitosamente.');
    }

    /**
     * Show edit lesson form
     */
    public function editLesson(Lesson $lesson)
    {
        return view('admin.lessons.edit', compact('lesson'));
    }

    /**
     * Update lesson
     */
    public function updateLesson(Request $request, Lesson $lesson)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'required|string',
            'video_url' => 'nullable|url',
            'duration' => 'nullable|integer|min:1',
            'is_free' => 'boolean',
            'materials.*' => 'nullable|file|max:10240',
        ]);

        $lessonData = $request->except(['materials']);
        $lessonData['is_free'] = $request->has('is_free');

        $lesson->update($lessonData);

        // Handle new file uploads
        if ($request->hasFile('materials')) {
            foreach ($request->file('materials') as $file) {
                $path = $file->store('lesson-materials', 'public');
                
                LessonMaterial::create([
                    'lesson_id' => $lesson->id,
                    'name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ]);
            }
        }

        return redirect()->route('admin.courses.lessons', $lesson->course)->with('success', 'Lección actualizada exitosamente.');
    }

    /**
     * Delete lesson
     */
    public function destroyLesson(Lesson $lesson)
    {
        $course = $lesson->course;
        
        // Delete associated materials
        foreach ($lesson->materials as $material) {
            Storage::disk('public')->delete($material->file_path);
            $material->delete();
        }

        $lesson->delete();

        return redirect()->route('admin.courses.lessons', $course)->with('success', 'Lección eliminada exitosamente.');
    }

    /* LEVEL MANAGEMENT */

    /**
     * Display levels
     */
    public function levels()
    {
        $levels = Level::withCount('courses')->get();
        return view('admin.levels.index', compact('levels'));
    }

    /**
     * Store new level
     */
    public function storeLevel(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:levels',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $levelData = $request->except(['cover_image']);
        $levelData['order'] = Level::count() + 1;

        if ($request->hasFile('cover_image')) {
            $levelData['cover_image'] = $request->file('cover_image')->store('levels', 'public');
        }

        Level::create($levelData);

        return redirect()->route('admin.levels')->with('success', 'Nivel creado exitosamente.');
    }

    /**
     * Update level
     */
    public function updateLevel(Request $request, Level $level)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('levels')->ignore($level->id),
            ],
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $levelData = $request->except(['cover_image']);

        if ($request->hasFile('cover_image')) {
            // Delete old image
            if ($level->cover_image) {
                Storage::disk('public')->delete($level->cover_image);
            }
            $levelData['cover_image'] = $request->file('cover_image')->store('levels', 'public');
        }

        $level->update($levelData);

        return redirect()->route('admin.levels')->with('success', 'Nivel actualizado exitosamente.');
    }

    /**
     * Delete level
     */
    public function destroyLevel(Level $level)
    {
        // Check if level has courses
        if ($level->courses()->count() > 0) {
            return redirect()->route('admin.levels')->with('error', 'No se puede eliminar un nivel que tiene cursos asignados.');
        }

        // Delete cover image
        if ($level->cover_image) {
            Storage::disk('public')->delete($level->cover_image);
        }

        $level->delete();

        return redirect()->route('admin.levels')->with('success', 'Nivel eliminado exitosamente.');
    }

    /* PAYMENT MANAGEMENT */

    /**
     * Display payments list
     */
    public function payments(Request $request)
    {
        $query = Payment::with(['user', 'course']);
        
        if ($request->search) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            })->orWhereHas('course', function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }

        $payments = $query->latest()->paginate(15);
        $statuses = ['pending', 'completed', 'failed', 'refunded'];
        $paymentMethods = ['card', 'bank_transfer', 'cash'];

        return view('admin.payments.index', compact('payments', 'statuses', 'paymentMethods'));
    }

    /**
     * Show create payment form
     */
    public function createPayment()
    {
        $users = User::orderBy('name')->get();
        $courses = Course::orderBy('title')->get();
        
        return view('admin.payments.create', compact('users', 'courses'));
    }

    /**
     * Store new payment
     */
    public function storePayment(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'payment_method' => 'required|string',
            'status' => 'required|string',
        ]);

        $payment = Payment::create($request->all());

        return redirect()->route('admin.payments')->with('success', 'Pago creado exitosamente.');
    }

    /**
     * Show edit payment form
     */
    public function editPayment(Payment $payment)
    {
        $users = User::orderBy('name')->get();
        $courses = Course::orderBy('title')->get();
        
        return view('admin.payments.edit', compact('payment', 'users', 'courses'));
    }

    /**
     * Update payment
     */
    public function updatePayment(Request $request, Payment $payment)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'payment_method' => 'required|string',
            'status' => 'required|string',
        ]);

        $payment->update($request->all());

        return redirect()->route('admin.payments')->with('success', 'Pago actualizado exitosamente.');
    }

    /**
     * Delete payment
     */
    public function destroyPayment(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('admin.payments')->with('success', 'Pago eliminado exitosamente.');
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|string|in:pending,completed,failed,refunded'
        ]);

        $payment->update(['status' => $request->status]);

        return redirect()->route('admin.payments')->with('success', 'Estado del pago actualizado exitosamente.');
    }

    /* ENROLLMENT MANAGEMENT */

    /**
     * Display enrollments list
     */
    public function enrollments(Request $request)
    {
        $query = Enrollment::with(['user', 'course', 'level', 'payment']);
        
        if ($request->search) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            })->orWhereHas('course', function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->course_id) {
            $query->where('course_id', $request->course_id);
        }

        $enrollments = $query->latest()->paginate(15);
        $statuses = ['active', 'completed', 'cancelled', 'suspended'];
        $courses = Course::orderBy('title')->get();

        return view('admin.enrollments.index', compact('enrollments', 'statuses', 'courses'));
    }

    /**
     * Show create enrollment form
     */
    public function createEnrollment()
    {
        $users = User::orderBy('name')->get();
        $courses = Course::with('level')->orderBy('title')->get();
        $levels = Level::orderBy('name')->get();
        $payments = Payment::where('status', 'completed')->with(['user', 'course'])->get();
        
        return view('admin.enrollments.create', compact('users', 'courses', 'levels', 'payments'));
    }

    /**
     * Store new enrollment
     */
    public function storeEnrollment(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'level_id' => 'nullable|exists:levels,id',
            'payment_id' => 'nullable|exists:payments,id',
            'status' => 'required|string',
            'amount' => 'nullable|numeric|min:0',
        ]);

        // Check if enrollment already exists
        $existingEnrollment = Enrollment::where('user_id', $request->user_id)
                                       ->where('course_id', $request->course_id)
                                       ->first();

        if ($existingEnrollment) {
            return redirect()->back()->withErrors(['error' => 'El usuario ya está inscrito en este curso.']);
        }

        $enrollment = Enrollment::create($request->all());

        return redirect()->route('admin.enrollments')->with('success', 'Inscripción creada exitosamente.');
    }

    /**
     * Show edit enrollment form
     */
    public function editEnrollment(Enrollment $enrollment)
    {
        $users = User::orderBy('name')->get();
        $courses = Course::with('level')->orderBy('title')->get();
        $levels = Level::orderBy('name')->get();
        $payments = Payment::where('status', 'completed')->with(['user', 'course'])->get();
        
        return view('admin.enrollments.edit', compact('enrollment', 'users', 'courses', 'levels', 'payments'));
    }

    /**
     * Update enrollment
     */
    public function updateEnrollment(Request $request, Enrollment $enrollment)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'level_id' => 'nullable|exists:levels,id',
            'payment_id' => 'nullable|exists:payments,id',
            'status' => 'required|string',
            'amount' => 'nullable|numeric|min:0',
        ]);

        // Check if enrollment already exists (excluding current)
        $existingEnrollment = Enrollment::where('user_id', $request->user_id)
                                       ->where('course_id', $request->course_id)
                                       ->where('id', '!=', $enrollment->id)
                                       ->first();

        if ($existingEnrollment) {
            return redirect()->back()->withErrors(['error' => 'El usuario ya está inscrito en este curso.']);
        }

        $enrollment->update($request->all());

        return redirect()->route('admin.enrollments')->with('success', 'Inscripción actualizada exitosamente.');
    }

    /**
     * Delete enrollment
     */
    public function destroyEnrollment(Enrollment $enrollment)
    {
        $enrollment->delete();
        return redirect()->route('admin.enrollments')->with('success', 'Inscripción eliminada exitosamente.');
    }

    /**
     * Update enrollment status
     */
    public function updateEnrollmentStatus(Request $request, Enrollment $enrollment)
    {
        $request->validate([
            'status' => 'required|string|in:active,completed,cancelled,suspended'
        ]);

        $enrollment->update(['status' => $request->status]);

        return redirect()->route('admin.enrollments')->with('success', 'Estado de la inscripción actualizado exitosamente.');
    }
}
