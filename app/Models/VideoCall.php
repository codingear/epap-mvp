<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoCall extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'teacher_id',
        'day',
        'time',
        'timezone',
        'scheduled_at',
        'started_at',
        'ended_at',
        'status',
        'type',
        'url',
        'google_meet_id',
        'google_meet_link',
        'google_meet_data',
        'meeting_notes'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'google_meet_data' => 'array'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(AvailableSchedule::class, 'schedule_id');
    }

    // Create Google Meet link (placeholder - will need actual Google Calendar API)
    public function createGoogleMeetLink()
    {
        // For now, generate a placeholder Google Meet link
        $meetingCode = $this->generateMeetingCode();
        $this->google_meet_link = "https://meet.google.com/{$meetingCode}";
        $this->google_meet_id = $meetingCode;
        $this->save();
        
        return $this->google_meet_link;
    }

    private function generateMeetingCode()
    {
        return strtolower(substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 3) . '-' . 
                         substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 4) . '-' . 
                         substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 3));
    }

    // Get formatted date and time
    public function getFormattedDateTime()
    {
        if ($this->scheduled_at) {
            return $this->scheduled_at->format('l, F j, Y \a\t g:i A');
        }
        
        return "Day {$this->day} at {$this->time}";
    }

    /**
     * Scope to filter welcome video calls
     */
    public function scopeWelcome($query)
    {
        return $query->where('type', 'welcome');
    }

    /**
     * Scope to filter by user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Check if user has a welcome call
     */
    public static function userHasWelcomeCall($userId)
    {
        return static::forUser($userId)->welcome()->exists();
    }
}
