<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();
        dd($user);
        if ($user->hasRole('admin')) {
            //return redirect()->route('admin.index');
        }

        if ($user->hasRole('teacher')) {
            return redirect()->route('teacher.index');
        }

        if ($user->hasRole('student')) {
            return redirect()->route('student.index');
        }

        // If no specific role or other cases, proceed with the request
        return $next($request);
    }
}
