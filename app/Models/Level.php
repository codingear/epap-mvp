<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name', 
        'description',
        'parent_id',
        'price',
        'cover_image',
        'instructor_id',
        'status',
        'order',
        'slug',
    ];

    /**
     * Get the parent level
     */
    public function parent()
    {
        return $this->belongsTo(Level::class, 'parent_id');
    }

    /**
     * Get the sublevels
     */
    public function sublevels()
    {
        return $this->hasMany(Level::class, 'parent_id');
    }

    /**
     * Get the instructor for this level
     */
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    /**
     * Get the courses for this level
     */
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
