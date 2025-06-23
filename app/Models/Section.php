<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'course_id',
        'order',
    ];

    /**
     * Get the course for this section
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the lessons for this section
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }
}
