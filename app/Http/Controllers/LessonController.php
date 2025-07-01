<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    public function show(Course $course, Lesson $lesson)
    {
        $user = Auth::user();
        
        // Check if user has access to this lesson
        if (!$lesson->canBeAccessedByUser($user->id)) {
            return redirect()->route('courses.show', $course)
                           ->with('error', 'Necesitas comprar este curso para acceder a esta lección.');
        }

        // Mark lesson as started if not already
        $lessonUser = $lesson->users()->where('user_id', $user->id)->first();
        if (!$lessonUser) {
            $lesson->users()->attach($user->id, [
                'started_at' => now(),
                'progress' => 0,
                'completed' => false
            ]);
        }

        // Get lesson materials
        $materials = $lesson->materials()->get();
        
        // Get lesson comments
        $comments = $lesson->comments()
                          ->with('user')
                          ->orderBy('created_at', 'desc')
                          ->get();

        // Get next and previous lessons
        $nextLesson = $lesson->nextLesson();
        $previousLesson = $lesson->previousLesson();

        // Get user's progress
        $progress = $lessonUser ? $lessonUser->pivot->progress : 0;
        $isCompleted = $lessonUser ? $lessonUser->pivot->completed : false;

        return view('lessons.show', compact(
            'course', 
            'lesson', 
            'materials', 
            'comments', 
            'nextLesson', 
            'previousLesson',
            'progress',
            'isCompleted'
        ));
    }

    public function updateProgress(Request $request, Course $course, Lesson $lesson)
    {
        $request->validate([
            'progress' => 'required|integer|min:0|max:100'
        ]);

        $user = Auth::user();
        
        if (!$lesson->canBeAccessedByUser($user->id)) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $progress = $request->progress;
        $isCompleted = $progress >= 100;

        $lesson->users()->updateExistingPivot($user->id, [
            'progress' => $progress,
            'completed' => $isCompleted,
            'completed_at' => $isCompleted ? now() : null
        ]);

        // Update course progress
        $this->updateCourseProgress($user, $course);

        return response()->json([
            'success' => true,
            'progress' => $progress,
            'completed' => $isCompleted
        ]);
    }

    public function complete(Course $course, Lesson $lesson)
    {
        $user = Auth::user();
        
        if (!$lesson->canBeAccessedByUser($user->id)) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $lesson->users()->updateExistingPivot($user->id, [
            'progress' => 100,
            'completed' => true,
            'completed_at' => now()
        ]);

        // Update course progress
        $this->updateCourseProgress($user, $course);

        $nextLesson = $lesson->nextLesson();

        return response()->json([
            'success' => true,
            'completed' => true,
            'next_lesson' => $nextLesson ? [
                'id' => $nextLesson->id,
                'title' => $nextLesson->title,
                'url' => route('lessons.show', ['course' => $course, 'lesson' => $nextLesson])
            ] : null
        ]);
    }

    private function updateCourseProgress($user, $course)
    {
        $totalLessons = $course->lessons()->count();
        $completedLessons = $user->lessons()
                                ->whereHas('course', function($query) use ($course) {
                                    $query->where('id', $course->id);
                                })
                                ->wherePivot('completed', true)
                                ->count();

        $progress = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;

        // Update course_user pivot table if exists
        $course->students()->updateExistingPivot($user->id, [
            'progress' => $progress,
            'completed' => $progress >= 100,
            'last_accessed_at' => now()
        ]);
    }

    public function addComment(Request $request, Course $course, Lesson $lesson)
    {
        $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        $user = Auth::user();
        
        if (!$lesson->canBeAccessedByUser($user->id)) {
            return back()->with('error', 'No tienes acceso a esta lección.');
        }

        Comment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'lesson_id' => $lesson->id,
            'content' => $request->content,
            'type' => 'lesson_comment'
        ]);

        return back()->with('success', 'Comentario agregado exitosamente.');
    }
}
