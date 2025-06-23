<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Nnjeim\World\World;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\VideoCall;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $videoCalls = VideoCall::where('user_id', Auth::id())->get();
        return view('student.index', compact('videoCalls'));
    }

    public function welcome()
    {
        $action =  World::countries();

        if ($action->success) {
            $countries = $action->data;
        }
        return view('student.welcome', compact('countries'));
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
        $user = Auth::user();
        
        $videoCall = new VideoCall();
        $videoCall->user_id = $user->id;
        $videoCall->day = $request->day;
        $videoCall->time = $request->time;
        $videoCall->timezone = $request->timezone;
        $videoCall->url = 'https://meet.google.com/rnn-yucx-wyv';
        $videoCall->save();
    }

    public function calendar()
    {
        $days = [];
        $today = now();
        
        for ($i = 0; $i < 7; $i++) {
            $day = $today->copy()->addDays($i);
            $days[] = [
                'date' => $day->format('Y-m-d'),
                'day' => $day->format('d'),
                'day_name' => $day->format('l'),
                'month' => $day->format('F'),
            ];
        }
        return view('student.calendar', compact('days'));
    }
}
