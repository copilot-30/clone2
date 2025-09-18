<?php

namespace App\Http\Controllers;

use App\Patient;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail; // Add Mail facade
use Illuminate\Support\Str; // Add Str facade for token generation
use Illuminate\Support\Facades\DB; // For database interaction to store token
use Carbon\Carbon; // For timestamp
use App\PasswordReset;
use App\Events\AuditableEvent; // Correct placement

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'PATIENT', // Default to patient role
            'is_active' => true,
        ]);

  

        // Dispatch audit event
        event(new AuditableEvent($user->id, 'user_registered', [
            'user_id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
        ]));

        // Automatically log in the user after registration
        Auth::login($user);

        // Redirect based on the user's role
        switch ($user->role) {
            case 'ADMIN':
                return redirect('/admin/dashboard')->with('success', 'Admin registration successful!');
            case 'DOCTOR':
                return redirect('/doctor/dashboard')->with('success', 'Doctor registration successful!');
            case 'PATIENT':
                return redirect('/patient/dashboard')->with('success', 'Registration successful!');
            default:
                return redirect('/dashboard')->with('success', 'Registration successful!');
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            // Dispatch audit event for failed login attempt
            event(new AuditableEvent(null, 'login_failed', [
                'email_attempted' => $request->email,
                'reason' => 'Invalid credentials',
            ]));
            return redirect()->back()->withInput($request->only('email'))->withErrors(['email' => 'Invalid credentials']);
        }

        $user = $request->user();
        
        // Dispatch audit event for successful login
        event(new AuditableEvent($user->id, 'login_success', [
            'user_id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
        ]));

        if ($user -> is_active == false) {
            Auth::logout();
            return redirect('/login')->with('error', 'Your account is inactive. Please contact the administrator.');
        }

        // Redirect based on the user's role
        switch ($user->role) {
            case 'ADMIN':
                return redirect('/admin/dashboard');
            case 'DOCTOR':
                return redirect('/doctor/dashboard');
            case 'PATIENT':
                return redirect('/patient/dashboard');
            default:
                return redirect('/dashboard');
        }
    }
public function logout(Request $request)
{
    // Auth::guard("role:".Auth::user()->role)->logout();

    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/'); // Redirect to home or login page
}

public function sendPasswordResetEmail(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email|exists:users',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $user = User::where('email', $request->email)->first();

    // Delete any existing password reset tokens for this email
    PasswordReset::where('email', $request->email)->delete();

    // Generate a new token
    $token = Str::random(60);

    // Store the token in the database
    PasswordReset::create([
        'email' => $request->email,
        'token' => $token,
        'created_at' => Carbon::now()
    ]);

    // Send the password reset email
    Mail::send('emails.password_reset', ['token' => $token, 'email' => $request->email], function ($message) use ($request) {
        $message->to($request->email);
        $message->subject('Reset Your Password');
    });

    return redirect()->back()->with('status', 'Password reset link sent to your email!');
}
    public function showResetForm(Request $request, $token = null)
    {
        

        PasswordReset::where('token', $token)->where('email', $request->email)->where('created_at', '>', Carbon::now()->subMinutes(config('auth.passwords.users.expire')))->firstOrFail();

        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $passwordReset = PasswordReset::where('email', $request->email)
                                      ->where('token', $request->token)
                                      ->first();

        if (!$passwordReset) {
            return redirect()->back()->withErrors(['email' => 'Invalid token or email.'])->withInput();
        }

        // Check if token has expired (e.g., 60 minutes)
        if (Carbon::parse($passwordReset->created_at)->addMinutes(config('auth.passwords.users.expire'))->isPast()) {
            $passwordReset->delete(); // Delete expired token
            return redirect()->back()->withErrors(['email' => 'Password reset token has expired. Please request a new one.'])->withInput();
        }

        $user = User::where('email', $request->email)->where('is_active', true)->first();

        if (!$user) {
            return redirect()->back()->withErrors(['email' => 'User not found.'])->withInput();
        }

        $user->password = Hash::make($request->password);
        $user->save();

        $passwordReset->delete(); // Delete used token

        return redirect('/login')->with('status', 'Your password has been reset!');
    }
}