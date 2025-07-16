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
        $user = Auth::user();
        
        // Get all video calls for the student
        $videoCalls = VideoCall::where('user_id', $user->id)
            ->with('teacher')
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Get specific welcome call
        $welcomeCall = VideoCall::forUser($user->id)
            ->welcome()
            ->first();
            
        // Get user's enrollments and courses (if you have this relationship)
        // You can uncomment these lines when you implement course enrollment system
        // $enrollments = $user->enrollments()->with('course')->get();
        // $courses = $enrollments->pluck('course');
        
        $data = [
            'videoCalls' => $videoCalls,
            'welcomeCall' => $welcomeCall,
            'user' => $user,
            // 'enrollments' => $enrollments ?? collect(),
            // 'courses' => $courses ?? collect(),
        ];
        
        return view('student.index', $data);
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

    public function update(Request $request, ?User $user = null)
    {
        // Commented out welcome flow - users now go directly to calendar
        /*
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
        } else
        */
        if($request->type_update == 'profile_edit') {
            // Validate the input for profile editing
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'child_name' => 'nullable|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
                'phone' => 'nullable|string|max:20',
                'date_of_birth' => 'nullable|date',
                'timezone' => 'nullable|string',
                'country' => 'nullable|string|max:255',
                'state' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:255',
                'password' => 'nullable|string|min:8|confirmed',
            ]);

            // Update the authenticated user
            $currentUser = Auth::user();
            $currentUser->name = $validated['name'];
            $currentUser->child_name = $validated['child_name'];
            $currentUser->email = $validated['email'];
            $currentUser->phone = $validated['phone'];
            $currentUser->date_of_birth = $validated['date_of_birth'];
            $currentUser->timezone = $validated['timezone'];
            $currentUser->country = $validated['country'];
            $currentUser->state = $validated['state'];
            $currentUser->city = $validated['city'];
            
            // Only update password if provided
            if ($request->filled('password')) {
                $currentUser->password = Hash::make($validated['password']);
            }
            
            $currentUser->save();

            return redirect()->route('student.profile.edit')->with('success', '¡Perfil actualizado exitosamente!');
        } else {
            return redirect()->route('student.calendar');
        }
    }

    /**
     * Show the form for editing the student profile.
     */
    public function editProfile()
    {
        return view('student.profile.edit');
    }

    public function calendar_create(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Validate the request
            $validated = $request->validate([
                'day' => 'required|string',
                'time' => 'required|string',
                'timezone' => 'required|string',
                'date' => 'required|string|date'
            ]);

            // Parse the selected date and time
            $selectedDate = Carbon::parse($validated['date'], $validated['timezone']);
            $selectedTime = $validated['time'];
            $scheduledAt = Carbon::parse($validated['date'] . ' ' . $selectedTime, $validated['timezone']);

            // Check if the slot is in the past
            if ($scheduledAt->isPast()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede agendar una cita en el pasado.'
                ], 422);
            }

            // Check if user already has a scheduled video call
            $existingCall = VideoCall::where('user_id', $user->id)
                ->where('status', 'scheduled')
                ->first();

            if ($existingCall) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya tienes una videollamada agendada. Cancela la anterior para crear una nueva.'
                ], 422);
            }

            // Check if slot is still available (basic check)
            $conflictingCall = VideoCall::whereDate('scheduled_at', $selectedDate->format('Y-m-d'))
                ->whereTime('scheduled_at', $selectedTime)
                ->where('status', '!=', 'canceled')
                ->first();

            if ($conflictingCall) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este horario ya no está disponible. Por favor selecciona otro.'
                ], 422);
            }

            // Create the video call with a temporary URL
            $videoCall = VideoCall::create([
                'user_id' => $user->id,
                'day' => $validated['day'],
                'time' => $validated['time'],
                'timezone' => $validated['timezone'],
                'scheduled_at' => $scheduledAt,
                'status' => 'scheduled',
                'type' => 'welcome',
                'url' => 'pending' // Temporary URL, will be updated with actual Google Meet link
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
                // Delete the video call if Meet link creation failed
                $videoCall->delete();
                
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
        try {
            $date = $request->get('date');
            $timezone = $request->get('timezone', 'America/Mexico_City');

            // Validate inputs
            if (!$date) {
                return response()->json([
                    'success' => false,
                    'message' => 'Fecha requerida'
                ], 400);
            }

            $slots = $this->googleMeetService->getAvailableSlots($date, $timezone);

            // Filter out past slots for today
            $today = Carbon::today($timezone);
            $requestDate = Carbon::parse($date, $timezone);
            
            if ($requestDate->isSameDay($today)) {
                $now = Carbon::now($timezone);
                $slots = array_filter($slots, function($slot) use ($now) {
                    $slotTime = Carbon::parse($slot['datetime']);
                    return $slotTime->isAfter($now);
                });
                $slots = array_values($slots); // Reset array keys
            }

            return response()->json([
                'success' => true,
                'slots' => $slots,
                'date' => $date,
                'timezone' => $timezone,
                'total_slots' => count($slots)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener horarios disponibles: ' . $e->getMessage()
            ], 500);
        }
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

    /**
     * Update user's timezone
     */
    public function updateTimezone(Request $request)
    {
        try {
            $validated = $request->validate([
                'timezone' => 'required|string|max:255'
            ]);

            $user = Auth::user();
            $user->timezone = $validated['timezone'];
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Zona horaria actualizada correctamente',
                'timezone' => $validated['timezone']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la zona horaria: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if student has a welcome video call
     */
    public function hasWelcomeCall($userId = null)
    {
        $userId = $userId ?? Auth::id();
        return VideoCall::userHasWelcomeCall($userId);
    }

    /**
     * Get student's welcome call details
     */
    public function getWelcomeCall($userId = null)
    {
        $userId = $userId ?? Auth::id();
        
        return VideoCall::forUser($userId)
            ->welcome()
            ->with('teacher')
            ->first();
    }
}
