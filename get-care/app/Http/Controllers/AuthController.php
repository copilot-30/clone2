<?php

namespace App\Http\Controllers;

use App\Patient;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
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

    // Logic to send password reset email (e.g., using Laravel's built-in password broker)
    // For demonstration, we'll just redirect with a success message.
    // In a real application, you would use Password::sendResetLink($request->only('email'));

    return redirect()->back()->with('status', 'Password reset link sent to your email!');
}
}