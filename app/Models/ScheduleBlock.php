<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class ScheduleBlock extends Model
{
    protected $fillable = [
        'date',
        'start_time',
        'end_time',
        'reason',
        'notes',
        'type',
        'created_by'
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i'
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Check if a specific time is blocked
    public static function isTimeBlocked($date, $time)
    {
        $targetDate = Carbon::parse($date)->format('Y-m-d');
        $targetTime = Carbon::parse($time)->format('H:i:s');

        return self::where('date', $targetDate)
            ->where(function($query) use ($targetTime) {
                $query->where('type', 'full_day')
                    ->orWhere(function($q) use ($targetTime) {
                        $q->where('type', 'single_slot')
                          ->where('start_time', $targetTime);
                    })
                    ->orWhere(function($q) use ($targetTime) {
                        $q->where('type', 'time_range')
                          ->where('start_time', '<=', $targetTime)
                          ->where('end_time', '>=', $targetTime);
                    });
            })
            ->exists();
    }

    // Get all blocked time slots for a date
    public static function getBlockedTimesForDate($date)
    {
        $targetDate = Carbon::parse($date)->format('Y-m-d');
        $blocks = self::where('date', $targetDate)->get();
        $blockedTimes = [];

        foreach ($blocks as $block) {
            switch ($block->type) {
                case 'full_day':
                    // Block entire day
                    $workingHours = [
                        '09:00', '10:00', '11:00', '12:00',
                        '13:00', '14:00', '15:00', '16:00',
                        '17:00', '18:00', '19:00'
                    ];
                    $blockedTimes = array_merge($blockedTimes, $workingHours);
                    break;
                    
                case 'single_slot':
                    $blockedTimes[] = $block->start_time->format('H:i');
                    break;
                    
                case 'time_range':
                    $start = Carbon::parse($block->start_time);
                    $end = Carbon::parse($block->end_time);
                    
                    while ($start->lte($end)) {
                        $blockedTimes[] = $start->format('H:i');
                        $start->addHour();
                    }
                    break;
            }
        }

        return array_unique($blockedTimes);
    }
}
