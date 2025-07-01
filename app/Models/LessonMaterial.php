<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class LessonMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'title',
        'description',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'type',
        'downloadable'
    ];

    protected $casts = [
        'downloadable' => 'boolean',
        'file_size' => 'integer'
    ];

    /**
     * Get the lesson that owns this material
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Get the download URL for this material
     */
    public function getDownloadUrlAttribute()
    {
        return route('materials.download', $this->id);
    }

    /**
     * Get the file size in human readable format
     */
    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
