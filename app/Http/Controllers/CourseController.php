<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    /**
     * Display a listing of the courses.
     */
    public function index(Request $request)
    {
        $query = Course::with(['level', 'instructor', 'reviews'])
                      ->where('status', 'active');

        // Filter by level
        if ($request->has('level') && $request->level) {
            $query->where('level_id', $request->level);
        }

        // Filter by price range
        if ($request->has('price_range')) {
            switch ($request->price_range) {
                case 'free':
                    $query->where('is_free', true);
                    break;
                case 'paid':
                    $query->where('is_free', false);
                    break;
            }
        }

        // Search by title or description
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $courses = $query->orderBy('order')->paginate(12);
        $levels = Level::all();

        return view('courses.index', compact('courses', 'levels'));
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course)
    {
        $user = Auth::user();
        
        // Load relationships
        $course->load([
            'level',
            'instructor',
            'lessons' => function($query) {
                $query->orderBy('order');
            },
            'reviews.user'
        ]);

        // Check if user has purchased this course
        $hasPurchased = $user ? $user->hasPurchasedCourse($course->id) : false;
        
        // Get user's progress if purchased
        $progress = 0;
        $completedLessons = 0;
        if ($hasPurchased && $user) {
            $progress = $user->getCourseProgress($course->id);
            $completedLessons = $user->getCompletedLessonsCount($course->id);
        }

        // Get course statistics
        $totalLessons = $course->lessons()->count();
        $averageRating = $course->average_rating;
        $totalStudents = $course->student_count;

        // Get related courses
        $relatedCourses = Course::where('level_id', $course->level_id)
                               ->where('id', '!=', $course->id)
                               ->where('status', 'active')
                               ->limit(4)
                               ->get();

        return view('courses.show', compact(
            'course',
            'hasPurchased',
            'progress',
            'completedLessons',
            'totalLessons',
            'averageRating',
            'totalStudents',
            'relatedCourses'
        ));
    }

    /**
     * Display course curriculum (lessons list).
     */
    public function curriculum(Course $course)
    {
        $user = Auth::user();
        
        // Check if user has access
        $hasAccess = $user && $user->hasPurchasedCourse($course->id);
        
        $lessons = $course->lessons()->orderBy('order')->get();
        
        // Add user progress to each lesson
        if ($hasAccess) {
            foreach ($lessons as $lesson) {
                $userLesson = $lesson->users()->where('user_id', $user->id)->first();
                $lesson->user_progress = $userLesson ? $userLesson->pivot->progress : 0;
                $lesson->user_completed = $userLesson ? $userLesson->pivot->completed : false;
            }
        }

        return view('courses.curriculum', compact('course', 'lessons', 'hasAccess'));
    }

    /**
     * Show course reviews.
     */
    public function reviews(Course $course)
    {
        $reviews = $course->reviews()
                          ->with('user')
                          ->orderBy('created_at', 'desc')
                          ->paginate(10);

        $averageRating = $course->average_rating;
        
        // Rating distribution
        $ratingDistribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $count = $course->reviews()->where('rating', $i)->count();
            $ratingDistribution[$i] = $count;
        }

        return view('courses.reviews', compact('course', 'reviews', 'averageRating', 'ratingDistribution'));
    }

    /**
     * Add a review to a course.
     */
    public function addReview(Request $request, Course $course)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5'
        ]);

        $user = Auth::user();
        
        // Check if user has purchased the course
        if (!$user->hasPurchasedCourse($course->id)) {
            return back()->with('error', 'Debes comprar el curso para poder calificarlo.');
        }

        // Check if user already reviewed this course
        $existingReview = $course->reviews()->where('user_id', $user->id)->first();
        if ($existingReview) {
            return back()->with('error', 'Ya has calificado este curso.');
        }

        $course->reviews()->create([
            'user_id' => $user->id,
            'content' => $request->content,
            'rating' => $request->rating,
            'type' => 'course_review'
        ]);

        return back()->with('success', 'Reseña agregada exitosamente.');
    }
}
