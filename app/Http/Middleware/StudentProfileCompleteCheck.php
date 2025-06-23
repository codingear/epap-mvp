<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\VideoCall;

class StudentProfileCompleteCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Check if the student has completed their profile
        $profileComplete = $user->country && $user->state && $user->city;
        
        // Check if the student has a video call record
        $hasVideoCall = VideoCall::where('user_id', $user->id)->exists();
        
        // If profile is incomplete or no video call record, redirect to welcome page
        if (!$profileComplete || !$hasVideoCall) {
            return redirect()->route('student.calendar')->with('error', 'Please complete your profile and have a video call before accessing the calendar.');
        } else {
            return redirect()->route('student.index');
        }

        // If all checks pass, proceed to the original destination
        return $next($request);
    }
}
