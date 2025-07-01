<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Nnjeim\World\Models\Timezone;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'country',
        'state',
        'city',
        'timezone',
        'child_name',
        'date_of_birth',
        'phone',
        'magic_token',
        'magic_token_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getTimeZoneUserAttribute(): string
    {
        $timezone = Timezone::where('id', $this->timezone)->first();
        return $timezone ? $timezone->name : '';
    }
    
    /**
     * Get the user's primary role.
     *
     * @return string|null
     */
    public function getRoleAttribute(): ?string
    {
        // Get the first role assigned to the user
        $role = $this->roles->first();
        return $role ? $role->name : null;
    }

    public function getTimeZoneNameAttribute(): string
    {
        // Get the timezone name from the Timezone model
        $timezone = Timezone::find($this->timezone);
        return $timezone ? $timezone->name : '';
    }

    /**
     * Check if user has access to a level
     */
    public function hasLevel($levelId)
    {
        return $this->enrollments()->whereHas('level', function($query) use ($levelId) {
            $query->where('id', $levelId);
        })->exists();
    }

    /**
     * Check if user has access to a course
     */
    public function hasAccess($courseId)
    {
        // Check direct course enrollment
        $directAccess = $this->enrollments()->whereHas('course', function($query) use ($courseId) {
            $query->where('id', $courseId);
        })->exists();
        
        if ($directAccess) {
            return true;
        }
        
        // Check level-based access (if user bought the whole level)
        $course = Course::find($courseId);
        if ($course) {
            return $this->hasLevel($course->level_id);
        }
        
        return false;
    }

    /**
     * Get user enrollments
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Get the user's payments
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the lessons the user has taken
     */
    public function lessons()
    {
        return $this->belongsToMany(Lesson::class, 'lesson_user')
                    ->withPivot('completed', 'progress', 'started_at', 'completed_at')
                    ->withTimestamps();
    }

    /**
     * Get the user's comments
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the courses the user teaches
     */
    public function teachingCourses()
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    /**
     * Get purchased courses through payments
     */
    public function purchasedCourses()
    {
        return $this->belongsToMany(Course::class, 'payments')
                    ->where('payments.status', 'completed')
                    ->distinct();
    }

    /**
     * Check if user has purchased a specific course
     */
    public function hasPurchasedCourse($courseId)
    {
        return $this->payments()
                    ->where('course_id', $courseId)
                    ->where('status', 'completed')
                    ->exists();
    }

    /**
     * Get completed lessons count for a course
     */
    public function getCompletedLessonsCount($courseId)
    {
        return $this->lessons()
                    ->whereHas('course', function($query) use ($courseId) {
                        $query->where('id', $courseId);
                    })
                    ->wherePivot('completed', true)
                    ->count();
    }

    /**
     * Get course progress percentage
     */
    public function getCourseProgress($courseId)
    {
        $course = Course::find($courseId);
        if (!$course) {
            return 0;
        }

        $totalLessons = $course->lessons()->count();
        if ($totalLessons === 0) {
            return 0;
        }

        $completedLessons = $this->getCompletedLessonsCount($courseId);
        return round(($completedLessons / $totalLessons) * 100, 2);
    }
}
