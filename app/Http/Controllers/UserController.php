<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\MagicLoginLink;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Show the magic login request form
     */
    public function showMagicLoginForm()
    {
        return view('auth.magic-login');
    }

    /**
     * Send magic login link to user's email
     */
    public function sendMagicLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'No encontramos un usuario con ese correo electrónico.',
        ]);

        $user = User::where('email', $request->email)->first();
        
        // Generate a random token
        $token = Str::random(60);
        
        // Store token and expiration time (1 hour from now)
        $user->magic_token = $token;
        $user->magic_token_expires_at = Carbon::now()->addHour();
        $user->save();
        
        // Send email with magic link
        Mail::to($user->email)->send(new MagicLoginLink($user, $token));
        
        return back()->with('status', '¡Te hemos enviado un enlace mágico! Revisa tu correo electrónico.');
    }
    
    /**
     * Handle the magic login request
     */
    public function magicLogin($token)
    {
        // Find user with this token
        $user = User::where('magic_token', $token)
                    ->where('magic_token_expires_at', '>', Carbon::now())
                    ->first();
        
        if (!$user) {
            return redirect()->route('magic.login')
                ->with('error', 'El enlace mágico no es válido o ha caducado. Por favor, solicita uno nuevo.');
        }
        
        // Clear the token and expiration
        $user->magic_token = null;
        $user->magic_token_expires_at = null;
        $user->save();
        
        // Login the user
        Auth::login($user);
        
        return redirect()->intended('/dashboard');
    }
}
