<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class AvailableSchedule extends Model
{
    protected $fillable = [
        'teacher_id',
        'date',
        'start_time',
        'end_time',
        'timezone',
        'is_available',
        'max_slots',
        'booked_slots',
        'notes'
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_available' => 'boolean'
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function videoCallsAssigned()
    {
        return $this->hasMany(VideoCall::class, 'schedule_id');
    }

    // Get available time slots for a specific date
    public static function getAvailableSlots($date, $timezone = 'America/Mexico_City')
    {
        $slots = [];
        $targetDate = Carbon::parse($date);
        
        // Check if it's a weekday (Monday = 1, Friday = 5)
        if ($targetDate->dayOfWeek < 1 || $targetDate->dayOfWeek > 5) {
            return []; // No slots available on weekends
        }
        
        // Check if the date is in the past
        if ($targetDate->isBefore(Carbon::today())) {
            return []; // No slots available for past dates
        }
        
        // Default working hours: 9 AM to 7 PM (Monday to Friday)
        $workingHours = [
            '09:00', '10:00', '11:00', '12:00',
            '13:00', '14:00', '15:00', '16:00',
            '17:00', '18:00', '19:00'
        ];

        // Get existing appointments for this date to check availability
        $bookedSlots = \App\Models\VideoCall::whereDate('scheduled_at', $targetDate->format('Y-m-d'))
            ->where('status', '!=', 'canceled')
            ->pluck('scheduled_at')
            ->map(function($datetime) {
                return Carbon::parse($datetime)->format('H:i');
            })
            ->toArray();

        // Get any custom schedules that might override default availability
        $blockedTimes = \App\Models\ScheduleBlock::getBlockedTimesForDate($targetDate->format('Y-m-d'));

        foreach ($workingHours as $time) {
            $isBooked = in_array($time, $bookedSlots);
            $isBlocked = in_array($time, $blockedTimes);
            
            $slots[] = [
                'time' => $time,
                'available' => !$isBooked && !$isBlocked,
                'teacher_id' => null,
                'slots_remaining' => $isBooked || $isBlocked ? 0 : 1,
                'is_booked' => $isBooked,
                'is_blocked' => $isBlocked
            ];
        }

        return $slots;
    }

    // Check if a specific time slot is available
    public static function isSlotAvailable($date, $time, $timezone = 'America/Mexico_City')
    {
        $targetDate = Carbon::parse($date);
        $targetTime = Carbon::parse($time)->format('H:i');

        // Check if it's a weekday
        if ($targetDate->dayOfWeek < 1 || $targetDate->dayOfWeek > 5) {
            return false; // Not available on weekends
        }

        // Check if the date is in the past
        if ($targetDate->isBefore(Carbon::today())) {
            return false; // Not available for past dates
        }

        // Check if the time is within working hours (9 AM to 7 PM)
        $workingStart = '09:00';
        $workingEnd = '19:00';
        
        if ($targetTime < $workingStart || $targetTime > $workingEnd) {
            return false; // Outside working hours
        }

        // Check if there's already an appointment at this time
        $existingAppointment = \App\Models\VideoCall::whereDate('scheduled_at', $targetDate->format('Y-m-d'))
            ->whereTime('scheduled_at', $targetTime . ':00')
            ->where('status', '!=', 'canceled')
            ->exists();

        if ($existingAppointment) {
            return false; // Time slot is already booked
        }

        // Check if there's a custom block for this time
        $isBlocked = \App\Models\ScheduleBlock::isTimeBlocked($targetDate->format('Y-m-d'), $targetTime);

        if ($isBlocked) {
            return false; // Time slot is blocked by admin/teacher
        }

        return true; // Slot is available
    }

    // Book a slot
    public function bookSlot()
    {
        if ($this->booked_slots < $this->max_slots) {
            $this->increment('booked_slots');
            return true;
        }
        return false;
    }
}
