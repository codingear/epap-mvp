<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'content',
        'level_id',
        'instructor_id',
        'status',
        'order',
        'slug',
        'cover_image',
        'video_url',
        'duration',
        'type',
        'completion_points',
        'price',
        'currency',
        'is_free'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_free' => 'boolean'
    ];

    /**
     * Get the level that this course belongs to
     */
    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    /**
     * Get the instructor for this course
     */
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    /**
     * Get the users who have enrolled in this course
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'course_user')
                    ->withPivot('completed', 'progress', 'last_accessed_at')
                    ->withTimestamps();
    }

    /**
     * Get the lessons for this course
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    /**
     * Get the payments for this course
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the comments/reviews for this course
     */
    public function reviews()
    {
        return $this->hasMany(Comment::class)->where('type', 'course_review');
    }

    /**
     * Get the enrollments for this course
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Get the student count for this course
     */
    public function getStudentCountAttribute()
    {
        return $this->enrollments()->count();
    }

    /**
     * Check if a user has purchased this course
     */
    public function isPurchasedByUser($userId)
    {
        return $this->payments()
                    ->where('user_id', $userId)
                    ->where('status', 'completed')
                    ->exists();
    }

    /**
     * Get the average rating for this course
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    /**
     * Get the next available lesson for a user
     */
    public function getNextLessonForUser($userId)
    {
        $completedLessons = $this->lessons()
                                ->whereHas('users', function($query) use ($userId) {
                                    $query->where('user_id', $userId)
                                          ->where('completed', true);
                                })
                                ->pluck('order')
                                ->toArray();

        if (empty($completedLessons)) {
            return $this->lessons()->first();
        }

        $nextOrder = max($completedLessons) + 1;
        return $this->lessons()->where('order', $nextOrder)->first();
    }
}
