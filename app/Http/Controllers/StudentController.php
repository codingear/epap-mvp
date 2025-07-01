<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Nnjeim\World\World;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\VideoCall;
use App\Models\AvailableSchedule;
use App\Services\GoogleMeetService;
use Carbon\Carbon;

class StudentController extends Controller
{
    protected $googleMeetService;

    public function __construct(GoogleMeetService $googleMeetService)
    {
        $this->googleMeetService = $googleMeetService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $videoCalls = VideoCall::where('user_id', Auth::id())
            ->with('teacher')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('student.index', compact('videoCalls'));
    }

    public function welcome()
    {
        return view('student.welcome');
    }

    public function create(Request $request)
    {
        try {
            // Validate the input
            $validated = $request->validate([
                'child_name' => 'required|string|max:255',
                'date_of_birth' => 'required|date',
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'phone' => 'regex:/^\+?[0-9]{10,15}$/',
                'password' => 'required|string|min:8',
            ]);
            
            // Create a new user
            $user = new User();
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->password = Hash::make($validated['password']);
            $user->child_name = $validated['child_name'];
            $user->date_of_birth = $validated['date_of_birth'];
            $user->phone = $validated['phone'];
            $user->remember_token = Str::random(10);
            $user->save();

            // Assign the 'student' role to the user
            $user->assignRole('student');
            
            Auth::login($user);
            
            return redirect()->route('student.welcome');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        }
    }

    public function update(Request $request, User $user)
    {
        if($request->type_update == 'welcome') {
            // Validate the input
            $validated = $request->validate([
                'country' => 'required|string',
                'state' => 'required|string',
                'city' => 'required|string',
                'timezone' => 'required|string',
            ]);

            // Update the authenticated user with the welcome information
            $currentUser = Auth::user();
            $currentUser->country = $validated['country'];
            $currentUser->state = $validated['state'];
            $currentUser->city = $validated['city'];
            $currentUser->timezone = $validated['timezone'];
            $currentUser->save();

            return redirect()->route('student.calendar');
        } else {
            return redirect()->route('student.calendar');
        }
    }

    public function calendar_create(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Validate the request
            $validated = $request->validate([
                'day' => 'required|string',
                'time' => 'required|string',
                'timezone' => 'required|string'
            ]);

            // Parse the selected day and time
            $selectedDate = Carbon::now()->addDays((int)$validated['day'] - Carbon::now()->day);
            $selectedTime = Carbon::parse($validated['time'])->format('H:i:s');
            $scheduledAt = Carbon::parse($selectedDate->format('Y-m-d') . ' ' . $selectedTime);

            // Check if slot is still available
            $isSlotAvailable = AvailableSchedule::isSlotAvailable(
                $selectedDate->format('Y-m-d'),
                $selectedTime,
                $validated['timezone']
            );

            if (!$isSlotAvailable) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este horario ya no está disponible. Por favor selecciona otro.'
                ], 422);
            }

            // Create the video call
            $videoCall = VideoCall::create([
                'user_id' => $user->id,
                'day' => $validated['day'],
                'time' => $validated['time'],
                'timezone' => $validated['timezone'],
                'scheduled_at' => $scheduledAt,
                'status' => 'scheduled',
                'type' => 'welcome'
            ]);

            // Generate Google Meet link
            $meetResult = $this->googleMeetService->createMeetingEvent($videoCall);

            if ($meetResult['success']) {
                // Send meeting invitation
                $this->googleMeetService->sendMeetingInvitation($videoCall);

                return response()->json([
                    'success' => true,
                    'message' => 'Videollamada agendada exitosamente',
                    'video_call_id' => $videoCall->id,
                    'meet_link' => $meetResult['meet_link']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear el enlace de Google Meet: ' . $meetResult['error']
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al agendar la videollamada: ' . $e->getMessage()
            ], 500);
        }
    }

    public function calendar()
    {
        $days = [];
        $today = now();
        
        // Generate next 7 days
        for ($i = 0; $i < 7; $i++) {
            $day = $today->copy()->addDays($i);
            $days[] = [
                'date' => $day->format('Y-m-d'),
                'day' => $day->format('d'),
                'day_name' => $day->format('l'),
                'month' => $day->format('F'),
            ];
        }

        // Get available time slots for today (as example)
        $availableSlots = $this->googleMeetService->getAvailableSlots($today->format('Y-m-d'));

        return view('student.calendar', compact('days', 'availableSlots'));
    }

    /**
     * Get available time slots for a specific date
     */
    public function getAvailableSlots(Request $request)
    {
        $date = $request->get('date');
        $timezone = $request->get('timezone', 'America/Mexico_City');

        $slots = $this->googleMeetService->getAvailableSlots($date, $timezone);

        return response()->json($slots);
    }

    /**
     * Cancel a scheduled video call
     */
    public function cancelVideoCall(Request $request, VideoCall $videoCall)
    {
        // Check if user owns this video call
        if ($videoCall->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para cancelar esta videollamada'
            ], 403);
        }

        // Cancel the meeting
        $canceled = $this->googleMeetService->cancelMeeting($videoCall);

        if ($canceled) {
            return response()->json([
                'success' => true,
                'message' => 'Videollamada cancelada exitosamente'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Error al cancelar la videollamada'
            ], 500);
        }
    }
}
