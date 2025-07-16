<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'course_id',
        'level_id',
        'payment_id',
        'status',
        'amount',
    ];

    /**
     * Get the user for this enrollment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the course for this enrollment
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the level for this enrollment
     */
    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    /**
     * Get the payment for this enrollment
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
