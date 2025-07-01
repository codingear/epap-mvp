<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'content',
        'video_url',
        'order',
        'duration',
        'status',
        'is_free'
    ];

    protected $casts = [
        'is_free' => 'boolean'
    ];

    /**
     * Get the course for this lesson
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the users who have taken this lesson
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'lesson_user')
                    ->withPivot('completed', 'progress', 'started_at', 'completed_at')
                    ->withTimestamps();
    }

    /**
     * Get the materials for this lesson
     */
    public function materials()
    {
        return $this->hasMany(LessonMaterial::class);
    }

    /**
     * Get the comments for this lesson
     */
    public function comments()
    {
        return $this->hasMany(Comment::class)->where('type', 'lesson_comment');
    }

    /**
     * Check if a user has completed this lesson
     */
    public function isCompletedByUser($userId)
    {
        return $this->users()->where('user_id', $userId)->wherePivot('completed', true)->exists();
    }

    /**
     * Check if a user can access this lesson
     */
    public function canBeAccessedByUser($userId)
    {
        // If it's free, anyone can access
        if ($this->is_free) {
            return true;
        }

        // Check if user has purchased the course
        return $this->course->students()->where('user_id', $userId)->exists();
    }

    /**
     * Get the next lesson in order
     */
    public function nextLesson()
    {
        return $this->course->lessons()
                    ->where('order', '>', $this->order)
                    ->orderBy('order')
                    ->first();
    }

    /**
     * Get the previous lesson in order
     */
    public function previousLesson()
    {
        return $this->course->lessons()
                    ->where('order', '<', $this->order)
                    ->orderByDesc('order')
                    ->first();
    }
}
