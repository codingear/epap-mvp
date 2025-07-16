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
        
        // Check if the student has a welcome video call record
        $hasWelcomeCall = VideoCall::userHasWelcomeCall($user->id);
        
        // Debug: Allow access to debug route
        if ($request->routeIs('debug.student.status')) {
            return $next($request);
        }
        
        // If user doesn't have a welcome call yet - direct to calendar to schedule one
        if (!$hasWelcomeCall && !$request->routeIs('student.welcome') && !$request->routeIs('student.profile.update') && !$request->routeIs('student.calendar') && !$request->routeIs('student.calendar.create') && !$request->routeIs('student.profile.edit')) {
            return redirect()->route('student.calendar');
        }
        
        // If user has welcome video call, but trying to access welcome or calendar pages
        // Redirect to main dashboard where they can see their welcome call, courses, and progress
        if ($hasWelcomeCall && ($request->routeIs('student.welcome') || $request->routeIs('student.calendar'))) {
            return redirect()->route('student.index');
        }

        // If all checks pass, proceed to the original destination
        return $next($request);
    }
}
