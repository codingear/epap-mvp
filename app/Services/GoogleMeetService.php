<?php

namespace App\Services;

use App\Models\VideoCall;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleMeetService
{
    private $clientId;
    private $clientSecret;
    private $redirectUri;
    private $accessToken;

    public function __construct()
    {
        $this->clientId = config('services.google.client_id');
        $this->clientSecret = config('services.google.client_secret');
        $this->redirectUri = config('services.google.redirect_uri');
    }

    /**
     * Create a Google Calendar event with Meet link
     */
    public function createMeetingEvent($videoCall)
    {
        try {
            // For development, we'll create a placeholder Meet link
            // In production, you would integrate with Google Calendar API
            
            $meetingCode = $this->generateMeetingCode();
            $meetLink = "https://meet.google.com/{$meetingCode}";
            
            // Update the video call with the Google Meet information
            $videoCall->update([
                'google_meet_id' => $meetingCode,
                'google_meet_link' => $meetLink,
                'google_meet_data' => [
                    'created_at' => now()->toISOString(),
                    'meeting_type' => 'welcome_call',
                    'duration_minutes' => 30
                ],
                'url' => $meetLink
            ]);

            Log::info('Google Meet link created', [
                'video_call_id' => $videoCall->id,
                'meet_link' => $meetLink
            ]);

            return [
                'success' => true,
                'meet_link' => $meetLink,
                'meeting_id' => $meetingCode
            ];

        } catch (\Exception $e) {
            Log::error('Failed to create Google Meet link', [
                'error' => $e->getMessage(),
                'video_call_id' => $videoCall->id ?? null
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Generate a realistic Google Meet code
     */
    private function generateMeetingCode()
    {
        $consonants = 'bcdfghjklmnpqrstvwxyz';
        $vowels = 'aeiou';
        
        $part1 = '';
        $part2 = '';
        $part3 = '';
        
        // Generate first part (3 letters)
        for ($i = 0; $i < 3; $i++) {
            if ($i % 2 === 0) {
                $part1 .= $consonants[rand(0, strlen($consonants) - 1)];
            } else {
                $part1 .= $vowels[rand(0, strlen($vowels) - 1)];
            }
        }
        
        // Generate second part (4 letters)
        for ($i = 0; $i < 4; $i++) {
            if ($i % 2 === 0) {
                $part2 .= $consonants[rand(0, strlen($consonants) - 1)];
            } else {
                $part2 .= $vowels[rand(0, strlen($vowels) - 1)];
            }
        }
        
        // Generate third part (3 letters)
        for ($i = 0; $i < 3; $i++) {
            if ($i % 2 === 0) {
                $part3 .= $consonants[rand(0, strlen($consonants) - 1)];
            } else {
                $part3 .= $vowels[rand(0, strlen($vowels) - 1)];
            }
        }
        
        return $part1 . '-' . $part2 . '-' . $part3;
    }

    /**
     * Get available time slots for scheduling
     */
    public function getAvailableSlots($date, $timezone = 'America/Mexico_City')
    {
        $targetDate = Carbon::parse($date);
        
        // Check if it's a weekday (Monday = 1, Friday = 5)
        if ($targetDate->dayOfWeek < 1 || $targetDate->dayOfWeek > 5) {
            return []; // No slots available on weekends
        }
        
        // Check if the date is in the past
        if ($targetDate->isBefore(Carbon::today())) {
            return []; // No slots available for past dates
        }
        
        // Working hours: 9 AM to 7 PM (Monday to Friday)
        $workingHours = [
            '09:00', '10:00', '11:00', '12:00',
            '13:00', '14:00', '15:00', '16:00',
            '17:00', '18:00', '19:00'
        ];

        // Get existing appointments for this date to check availability
        $bookedSlots = VideoCall::whereDate('scheduled_at', $targetDate->format('Y-m-d'))
            ->where('status', '!=', 'canceled')
            ->pluck('scheduled_at')
            ->map(function($datetime) {
                return Carbon::parse($datetime)->format('H:i');
            })
            ->toArray();

        $slots = [];
        foreach ($workingHours as $hour) {
            $slotDateTime = Carbon::parse($targetDate->format('Y-m-d') . ' ' . $hour, $timezone);
            $isBooked = in_array($hour, $bookedSlots);
            
            $slots[] = [
                'time' => $hour,
                'datetime' => $slotDateTime->toISOString(),
                'available' => !$isBooked,
                'timezone' => $timezone,
                'is_past' => $slotDateTime->isPast()
            ];
        }
        
        return $slots;
    }

    /**
     * Send meeting invitation (placeholder)
     */
    public function sendMeetingInvitation($videoCall)
    {
        // In a real implementation, this would send calendar invitations
        // For now, we'll just log the action
        
        Log::info('Meeting invitation sent', [
            'video_call_id' => $videoCall->id,
            'student_email' => $videoCall->user->email,
            'meeting_link' => $videoCall->google_meet_link
        ]);
        
        return true;
    }

    /**
     * Cancel a meeting
     */
    public function cancelMeeting($videoCall)
    {
        try {
            $videoCall->update([
                'status' => 'canceled',
                'google_meet_data' => array_merge($videoCall->google_meet_data ?? [], [
                    'canceled_at' => now()->toISOString()
                ])
            ]);

            Log::info('Meeting canceled', [
                'video_call_id' => $videoCall->id
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to cancel meeting', [
                'error' => $e->getMessage(),
                'video_call_id' => $videoCall->id
            ]);
            
            return false;
        }
    }
}
