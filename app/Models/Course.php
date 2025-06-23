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
        'completion_points'
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
}
    {
        return $this->enrollments()->count();
    }

    /**
     * Get the enrollments for this course
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}
