<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'rating' => 'nullable|integer|min:1|max:5',
            'type' => 'required|in:course_review,lesson_comment,teacher_review',
            'course_id' => 'nullable|exists:courses,id',
            'lesson_id' => 'nullable|exists:lessons,id',
            'teacher_id' => 'nullable|exists:users,id'
        ]);

        $user = Auth::user();

        // Validation based on comment type
        if ($request->type === 'course_review') {
            if (!$request->course_id) {
                return back()->with('error', 'ID del curso es requerido para reseñas de curso.');
            }
            
            $course = Course::find($request->course_id);
            if (!$user->hasPurchasedCourse($course->id)) {
                return back()->with('error', 'Debes comprar el curso para poder calificarlo.');
            }
        }

        if ($request->type === 'teacher_review') {
            if (!$request->teacher_id) {
                return back()->with('error', 'ID del maestro es requerido para reseñas de maestro.');
            }
        }

        Comment::create([
            'user_id' => $user->id,
            'course_id' => $request->course_id,
            'lesson_id' => $request->lesson_id,
            'teacher_id' => $request->teacher_id,
            'content' => $request->content,
            'rating' => $request->rating,
            'type' => $request->type
        ]);

        return back()->with('success', 'Comentario agregado exitosamente.');
    }

    public function destroy(Comment $comment)
    {
        $user = Auth::user();
        
        // Users can only delete their own comments or admins can delete any
        if ($comment->user_id !== $user->id && !$user->hasRole('admin')) {
            abort(403, 'No tienes permisos para eliminar este comentario.');
        }

        $comment->delete();

        return back()->with('success', 'Comentario eliminado exitosamente.');
    }

    public function teacherReviews(User $teacher)
    {
        $reviews = Comment::where('teacher_id', $teacher->id)
                         ->where('type', 'teacher_review')
                         ->with('user')
                         ->orderBy('created_at', 'desc')
                         ->paginate(10);

        $averageRating = Comment::where('teacher_id', $teacher->id)
                              ->where('type', 'teacher_review')
                              ->avg('rating');

        return view('teachers.reviews', compact('teacher', 'reviews', 'averageRating'));
    }

    public function courseReviews(Course $course)
    {
        $reviews = Comment::where('course_id', $course->id)
                         ->where('type', 'course_review')
                         ->with('user')
                         ->orderBy('created_at', 'desc')
                         ->paginate(10);

        return view('courses.reviews', compact('course', 'reviews'));
    }
}
