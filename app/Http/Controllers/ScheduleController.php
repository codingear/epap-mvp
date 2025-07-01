<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AvailableSchedule;
use App\Models\VideoCall;
use App\Services\GoogleMeetService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    protected $googleMeetService;

    public function __construct(GoogleMeetService $googleMeetService)
    {
        $this->googleMeetService = $googleMeetService;
    }

    /**
     * Display the schedule management dashboard (for teachers/admins)
     */
    public function index()
    {
        $schedules = AvailableSchedule::with('teacher')
            ->where('date', '>=', now()->format('Y-m-d'))
            ->orderBy('date')
            ->orderBy('start_time')
            ->paginate(20);

        // Get schedule blocks
        $scheduleBlocks = \App\Models\ScheduleBlock::with('creator')
            ->where('date', '>=', now()->format('Y-m-d'))
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return view('schedule.index', compact('schedules', 'scheduleBlocks'));
    }

    /**
     * Create new available time slots
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'nullable|exists:users,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'timezone' => 'required|string',
            'max_slots' => 'required|integer|min:1|max:10',
            'notes' => 'nullable|string|max:500'
        ]);

        $schedule = AvailableSchedule::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Horario disponible creado exitosamente',
            'schedule' => $schedule
        ]);
    }

    /**
     * Get available slots for a specific date (API endpoint)
     */
    public function getAvailableSlots(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));
        $timezone = $request->get('timezone', 'America/Mexico_City');

        $slots = AvailableSchedule::getAvailableSlots($date, $timezone);

        return response()->json([
            'success' => true,
            'date' => $date,
            'timezone' => $timezone,
            'slots' => $slots
        ]);
    }

    /**
     * Update schedule availability
     */
    public function update(Request $request, AvailableSchedule $schedule)
    {
        $validated = $request->validate([
            'is_available' => 'boolean',
            'max_slots' => 'integer|min:1|max:10',
            'notes' => 'nullable|string|max:500'
        ]);

        $schedule->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Horario actualizado exitosamente'
        ]);
    }

    /**
     * Delete a schedule
     */
    public function destroy(AvailableSchedule $schedule)
    {
        // Check if there are any booked appointments
        if ($schedule->booked_slots > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar un horario con citas agendadas'
            ], 422);
        }

        $schedule->delete();

        return response()->json([
            'success' => true,
            'message' => 'Horario eliminado exitosamente'
        ]);
    }

    /**
     * Bulk create schedules for multiple days/times
     */
    public function bulkCreate(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'nullable|exists:users,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'times' => 'required|array|min:1',
            'times.*' => 'required|array',
            'times.*.start_time' => 'required|date_format:H:i',
            'times.*.end_time' => 'required|date_format:H:i|after:times.*.start_time',
            'days_of_week' => 'required|array|min:1',
            'days_of_week.*' => 'required|integer|min:0|max:6', // 0=Sunday, 6=Saturday
            'timezone' => 'required|string',
            'max_slots' => 'required|integer|min:1|max:10'
        ]);

        $created = 0;
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            if (in_array($date->dayOfWeek, $validated['days_of_week'])) {
                foreach ($validated['times'] as $timeSlot) {
                    AvailableSchedule::create([
                        'teacher_id' => $validated['teacher_id'],
                        'date' => $date->format('Y-m-d'),
                        'start_time' => $timeSlot['start_time'],
                        'end_time' => $timeSlot['end_time'],
                        'timezone' => $validated['timezone'],
                        'max_slots' => $validated['max_slots'],
                        'is_available' => true
                    ]);
                    $created++;
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Se crearon {$created} horarios disponibles",
            'created_count' => $created
        ]);
    }

    /**
     * Get upcoming appointments for a teacher
     */
    public function teacherAppointments(Request $request)
    {
        $teacherId = $request->get('teacher_id', Auth::id());
        
        $appointments = VideoCall::with('user')
            ->where('teacher_id', $teacherId)
            ->where('status', '!=', 'canceled')
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at')
            ->get();

        return response()->json([
            'success' => true,
            'appointments' => $appointments
        ]);
    }

    /**
     * Get schedule statistics
     */
    public function getStatistics(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfWeek()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfWeek()->format('Y-m-d'));

        $stats = [
            'total_slots' => AvailableSchedule::whereBetween('date', [$startDate, $endDate])->sum('max_slots'),
            'booked_slots' => AvailableSchedule::whereBetween('date', [$startDate, $endDate])->sum('booked_slots'),
            'available_slots' => AvailableSchedule::whereBetween('date', [$startDate, $endDate])
                ->selectRaw('SUM(max_slots - booked_slots) as available')
                ->value('available'),
            'total_appointments' => VideoCall::whereBetween('scheduled_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->where('status', '!=', 'canceled')
                ->count(),
            'completed_appointments' => VideoCall::whereBetween('scheduled_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->where('status', 'completed')
                ->count()
        ];

        $stats['booking_rate'] = $stats['total_slots'] > 0 
            ? round(($stats['booked_slots'] / $stats['total_slots']) * 100, 2) 
            : 0;

        return response()->json([
            'success' => true,
            'period' => ['start_date' => $startDate, 'end_date' => $endDate],
            'statistics' => $stats
        ]);
    }

    /**
     * Create a schedule block
     */
    public function createBlock(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'type' => 'required|in:single_slot,time_range,full_day',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string|max:500'
        ]);

        // Convert times to proper format
        if ($validated['type'] !== 'full_day' && $validated['start_time']) {
            $validated['start_time'] = $validated['start_time'] . ':00';
        }
        if ($validated['type'] === 'time_range' && $validated['end_time']) {
            $validated['end_time'] = $validated['end_time'] . ':00';
        }

        $validated['created_by'] = Auth::id();

        $block = \App\Models\ScheduleBlock::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Horario bloqueado exitosamente',
            'block' => $block
        ]);
    }

    /**
     * Delete a schedule block
     */
    public function deleteBlock(\App\Models\ScheduleBlock $block)
    {
        $block->delete();

        return response()->json([
            'success' => true,
            'message' => 'Bloqueo eliminado exitosamente'
        ]);
    }
}
