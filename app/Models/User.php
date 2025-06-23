<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Nnjeim\World\Models\Timezone;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

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
}
