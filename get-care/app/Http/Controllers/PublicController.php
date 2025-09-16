<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;
use Google\Service\Calendar\EventAttendee;
use Google\Service\Calendar\ConferenceData;
use Google\Service\Calendar\CreateConferenceRequest;
use Google\Service\Calendar\ConferenceSolutionKey;
use Socialite; // Import Socialite
use Auth;

class PublicController extends Controller
{
    public function landingPage()
    {
        return view('public.landing-page');
    }

    public function loginPage()
    {
        return view('public.login-page');
    }

    public function registerPage()
    {
        return view('public.register-page');
    }

    public function accountRecoveryPage()
    {
        return view('public.account-recovery');
    }

    public function privacyPolicyPage()
    {
        return view('privacy_policy');
    }

      /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->scopes([
            'https://www.googleapis.com/auth/calendar.events',
            'https://www.googleapis.com/auth/userinfo.email',
        ])->with(['access_type' => 'offline', 'prompt' => 'consent'])->redirect();
    }

    /**
     * Obtain the user's information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            
            $user = Socialite::driver('google')->user();

            if (!$user) {
                // Log and redirect if user is null, instead of dd()
                \Log::error('Socialite Google user is null after callback.');
                return redirect('/')->with('error', 'Google authentication failed. User data not received.');
            }

            $authUser = Auth::user(); // Get the currently authenticated user (patient)

            if (!$authUser){
                \Log::warning('Unauthorized access to Google callback. No authenticated user.');
                return redirect('/')->with('error', 'Authentication required to link Google account.');
            }


            if ($authUser-> role == 'PATIENT') {
                // Find the patient record associated with the authenticated user
                $patient = $authUser->patient;
                if ($patient) {
                    // Store Google access token and refresh token for the patient
                    // Ensure your 'patients' table has 'google_access_token' and 'google_refresh_token' columns
                    $patient->google_access_token = $user->token;
                    $patient->google_refresh_token = $user->refreshToken; // Will be null if access_type 'offline' not used or already consented
                    $patient->save();

                    return redirect()->route('patient.select-appointment-type', ['doctor_id' => session('doctor_id_for_google_auth')])->with('success', 'Google account linked successfully!');
                } else {
                    return redirect()->route('patient.dashboard')->with('error', 'Patient profile not found.');
                }

            }else if ($authUser-> role == 'DOCTOR') {
                $doctor = $authUser->doctor;
                if ($doctor) {
                    // Store Google access token and refresh token for the doctor
                    // Ensure your 'doctors' table has 'google_access_token' and 'google_refresh_token' columns
                    $doctor->google_access_token = $user->token;
                    $doctor->google_refresh_token = $user->refreshToken; // Will be null if access_type 'offline' not used or already consented
                    $doctor->save();

                    return redirect()->route('doctor.dashboard')->with('success', 'Google account linked successfully!');
                } else {
                    return redirect()->route('doctor.dashboard')->with('error', 'Doctor profile not found.');
                }

            }
            
        } catch (\Exception $e) {
            \Log::error('Google OAuth failed: ' . $e->getMessage());
            \Log::error('Google OAuth callback error: ' . $e->getMessage(), ['exception' => $e]);
            return redirect('/')->with('error', 'Failed to link Google account. Please try again.');
        }
    }
}