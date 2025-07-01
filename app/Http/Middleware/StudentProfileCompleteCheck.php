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
        
        // New user (first time) - needs to complete profile
        if (!$profileComplete && !$request->routeIs('student.welcome') && !$request->routeIs('student.profile.update')) {
            return redirect()->route('student.welcome');
        }
        
        // Profile complete but no video call yet - direct to calendar
        if ($profileComplete && !$hasVideoCall && !$request->routeIs('student.calendar') && !$request->routeIs('student.calendar.create')) {
            return redirect()->route('student.calendar');
        }
        
        // If profile complete and has video call, but trying to access welcome or calendar pages
        if ($profileComplete && $hasVideoCall && ($request->routeIs('student.welcome') || $request->routeIs('student.calendar'))) {
            return redirect()->route('student.index');
        }

        // If all checks pass, proceed to the original destination
        return $next($request);
    }
}
