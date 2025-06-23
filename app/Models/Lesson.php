<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'description',
        'section_id',
        'content',
        'type',
        'video_url',
        'duration',
        'order',
    ];

    /**
     * Get the section for this lesson
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
    }
    
    /**
     * Get the course through the section
     */
    public function course()
    {
        return $this->section->course();
    }
}
