<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Patient;
use App\Appointment; // Import the Appointment model
use App\PatientNote; // Import the PatientNote model
use App\Doctor; // Import the Doctor model
use App\AttendingPhysician; // Import the AttendingPhysician model
use App\DoctorAvailability; // Import the DoctorAvailability model
use App\Clinic; // Import the Clinic model
use Carbon\Carbon; // For date/time calculations
use Illuminate\Support\Str; // For generating unique IDs

// Google API Client Library imports
use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;
use Google\Service\Calendar\EventAttendee;
use Google\Service\Calendar\ConferenceData;
use Google\Service\Calendar\CreateConferenceRequest;
use Google\Service\Calendar\ConferenceSolutionKey;
use Socialite; // Import Socialite

class PatientController extends Controller
{
    public function showProfileForm()
    {
        $user = Auth::user();
        $patient = $user->patient ?? new \App\Patient(); // Retrieve existing patient data or create a new Patient instance

        return view('patient.patient-details-form', compact('patient'));
    }

    public function storeProfile(Request $request)
    {
        $user = Auth::user();

        // Validate the incoming request data
        $validatedData = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'middleName' => 'nullable|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'address' => 'required|string',
            'sex' => 'required|string|in:Male,Female,Other',
            'civilStatus' => 'required|string|max:255',
            'dateOfBirth' => 'required|date',
            'mobileNumber' => 'required|string|max:20',
            'bloodType' => 'nullable|string|max:10',
            'philhealthNo' => 'nullable|string|max:255',
            'knownMedicalCondition' => 'nullable|string',
            'allergies' => 'nullable|string',
            'previousSurgeries' => 'nullable|string',
            'familyHistory' => 'nullable|string',
            'medication' => 'nullable|string',
            'supplements' => 'nullable|string',
        ]);

        // Create or update the patient profile
        $patient = Patient::updateOrCreate(
            ['user_id' => $user->id],
            [
                'first_name' => $validatedData['firstName'],
                'last_name' => $validatedData['lastName'],
                'middle_name' => $validatedData['middleName'],
                'suffix' => $validatedData['suffix'],
                'address' => $validatedData['address'],
                'sex' => $validatedData['sex'],
                'civil_status' => $validatedData['civilStatus'],
                'date_of_birth' => $validatedData['dateOfBirth'],
                'primary_mobile' => $validatedData['mobileNumber'],
                'blood_type' => $validatedData['bloodType'],
                'philhealth_no' => $validatedData['philhealthNo'],
                'medical_conditions' => $validatedData['knownMedicalCondition'],
                'allergies' => $validatedData['allergies'],
                'surgeries' => $validatedData['previousSurgeries'],
                'family_history' => $validatedData['familyHistory'],
                'medications' => $validatedData['medication'],
                'supplements' => $validatedData['supplements'],
                // 'age' and 'tag' might be derived or set separately if needed
            ]
        );

        return redirect(route('patient.dashboard'))->with('success', 'Patient profile saved successfully!');
    }

    public function dashboard()
    { 
        $user = Auth::user();
        $patient = $user->patient; // Assuming a one-to-one relationship between User and Patient
 
        if (!$patient) {
            // Redirect to profile completion if patient profile is missing
            return redirect()->route('patient-details')->with('error', 'Please complete your patient profile.');
        }

        // Fetch upcoming appointments for the authenticated patient
        $upcomingAppointments = Appointment::where('patient_id', $patient->id)
                                         ->where('appointment_datetime', '>=', now()) // Use 'appointment_date'
                                         ->orderBy('appointment_datetime') // Order by 'appointment_date'
                                         ->get();

        // Fetch recent notes for the authenticated patient
        $recentNotes = PatientNote::where('patient_id', $patient->id)
                                -> where('visibility', 'shared')
                                  ->orderBy('created_at', 'desc')
                                  ->take(5) // Get latest 5 notes
                                  ->get();

        return view('patient.dashboard', compact('upcomingAppointments', 'recentNotes'));
    }

    public function chat()
    {
        return view('patient.chat-interface');
    }

    public function aiConsult()
    {
        return view('patient.ai-consult-chat');
    }

    public function showDoctorSelectionForm()
    {
        $user = Auth::user();
        $patient = $user->patient; // Patient will be lazily loaded here

        if (!$patient) {
            return redirect()->route('patient-details')->with('error', 'Please complete your patient profile before booking an appointment.');
        }

        // Fetch the patient's current attending physician, if any
        $attendingPhysician = $patient->attendingPhysician;

        // If an attending physician is set, directly proceed to selecting appointment type for that doctor
        if ($attendingPhysician) {
            return redirect()->route('patient.select-appointment-type', ['doctor_id' => $attendingPhysician->doctor_id]);
        }

        // Otherwise, show the doctor selection form for active doctors
        $doctors = Doctor::all(); // Filter active doctors
        return view('patient.select-doctor', compact('doctors'));
    }

    public function storeAttendingPhysician(Request $request)
    {
        $user = Auth::user();
        $patient = $user->patient;

        if (!$patient) {
            return back()->with('error', 'Patient profile not found.');
        }

        $validatedData = $request->validate([
            'doctor_id' => 'required|uuid|exists:doctor_profiles,id',
        ]);

        AttendingPhysician::updateOrCreate(
            ['patient_id' => $patient->id], // Find by patient_id
            [
                'doctor_id' => $validatedData['doctor_id'],
                'start_date' => now(), // Update start date (or keep original if already set)
                // 'end_date' => null, // Ensure it's current
            ]
        );

        return redirect()->route('patient.select-appointment-type', ['doctor_id' => $validatedData['doctor_id']])->with('success', 'Attending physician assigned successfully!');
    }

    public function showAppointmentTypeForm($doctor_id)
    {
        $user = Auth::user();
        $patient = $user->patient;
        if (!$patient) {
            return redirect()->route('patient-details')->with('error', 'Please complete your patient profile before booking an appointment.');
        }

        $doctor = Doctor::findOrFail($doctor_id);

        // Check if the patient has this doctor as their attending physician, or allow selection if no attending physician is set
        $currentAttendingPhysician = $patient->attendingPhysician;

        // If there's an assigned attending physician AND it's not the doctor we're trying to book with,
        // prevent booking and redirect.
        if ($currentAttendingPhysician && $currentAttendingPhysician->doctor_id !== $doctor->id) {
            return redirect()->route('patient.dashboard')->with('error', 'You can only book appointments with your assigned attending physician, or select a new one first.');
        }

    
        $sched = $doctor -> doctorAvailability()->whereNotNull('clinic_id')->get();

        $clinics = [];

        foreach ($sched as $s) {
            if ($s->clinic && !in_array($s->clinic, $clinics)) {
                $clinics[] = $s->clinic;
            }
           
        } 

       

 
        
        return view('patient.select-appointment-type', compact('doctor', 'clinics'));
    }

    public function showDateTimeSelectionForm(Request $request)
    {
        $user = Auth::user();
        $patient = $user->patient;
        if (!$patient) {
            return redirect()->route('patient-details')->with('error', 'Please complete your patient profile before booking an appointment.');
        }

        $validatedData = $request->validate([
            'doctor_id' => 'required|uuid|exists:doctor_profiles,id',
            'appointment_type' => 'required|string|in:online,clinic',
            'clinic_id' => 'nullable|uuid|exists:clinics,id', // Required if type is 'clinic'
        ]);

        $doctor = Doctor::findOrFail($validatedData['doctor_id']);
        $appointmentType = $validatedData['appointment_type'];
        $clinic = null;

        if ($appointmentType === 'clinic') {
            if (!$validatedData['clinic_id']) {
                return back()->withErrors(['clinic_id' => 'Clinic selection is required for clinic appointments.']);
            }
            $clinic = Clinic::findOrFail($validatedData['clinic_id']);
        }

        // Fetch doctor's availability for the next few weeks
        $availabilities = DoctorAvailability::where('doctor_id', $doctor->id)
                                            ->where('is_active', true)
                                            ->when($clinic, function ($query, $clinic) {
                                                return $query->where('clinic_id', $clinic->id);
                                            })
                                            ->get();

        // Group availabilities by day of week
        $groupedAvailabilities = $availabilities->groupBy('day_of_week');


        // Generate possible slots for the next 30 days
        // Generate possible slots for the next 30 days, starting from tomorrow (1-day lead time)
        $slots = [];
        $tomorrow = Carbon::tomorrow();
        for ($i = 0; $i < 30; $i++) {
            $date = $tomorrow->copy()->addDays($i);
            $dayOfWeek = $date->dayOfWeekIso; // 1 for Monday, 7 for Sunday

            if ($groupedAvailabilities->has($dayOfWeek)) {
                foreach ($groupedAvailabilities[$dayOfWeek] as $availability) {
                    $startTime = Carbon::parse($availability->start_time);
                    $endTime = Carbon::parse($availability->end_time);

                    // Break down into 30-minute slots
                    while ($startTime->lessThan($endTime)) {
                        $slot = $startTime->format('H:i');
                        $slotDateTime = $date->copy()->setTimeFromTimeString($slot);

                        // Check for existing appointments at this slot
                        $existingAppointment = Appointment::where('doctor_id', $doctor->id)
                                                        ->where('appointment_datetime', $slotDateTime)
                                                        ->first();

                        if (!$existingAppointment) {
                            $slots[$date->toDateString()][] = $slot;
                        }
                        $startTime->addMinutes(30);
                    }
                }
            }
        }
        return view('patient.select-date-time', compact('doctor', 'appointmentType', 'clinic', 'slots'));
    }

    public function storeAppointment(Request $request)
    {
        $user = Auth::user();
        $patient = $user->patient;
        if (!$patient) {
            return redirect()->route('patient-details')->with('error', 'Please complete your patient profile before booking an appointment.');
        }

        $validatedData = $request->validate([
            'doctor_id' => 'required|uuid|exists:doctor_profiles,id',
            'appointment_type' => 'required|string|in:online,clinic',
            'clinic_id' => 'required_if:appointment_type,clinic|nullable|uuid|exists:clinics,id',
            'appointment_datetime' => 'required|date_format:Y-m-d H:i',
            'chief_complaint' => 'nullable|string|max:1000',
        ]);

        $doctor = Doctor::findOrFail($validatedData['doctor_id']);

        // Check if the patient has this doctor as their attending physician, or allow selection if no attending physician is set
        $currentAttendingPhysician = $patient->attendingPhysician;
        if ($currentAttendingPhysician && $currentAttendingPhysician->doctor_id !== $doctor->id) {
            return redirect()->route('patient.dashboard')->with('error', 'You can only book appointments with your assigned attending physician, or select a new one first.');
        }

        // Validate that the selected slot is actually available based on doctor's availability and no existing appointments
        $requestedDateTime = Carbon::parse($validatedData['appointment_datetime']);
        $dayOfWeek = $requestedDateTime->dayOfWeekIso;
        $requestedTime = $requestedDateTime->format('H:i:s');

        $q = DoctorAvailability::where('doctor_id', $doctor->id)
                                        ->where('is_active', true)
                                        ->where('day_of_week', $dayOfWeek)
                                        ->where('start_time', '<=', $requestedTime)
                                        ->where('end_time', '>', $requestedTime);
        
        
        if ($validatedData['appointment_type'] === 'clinic') {
            $q->where('clinic_id', $validatedData['clinic_id']);
        }

      
                                        
                                        
        $availableSlot = $q ->first();

        if (!$availableSlot) {
            return back()->withErrors(['appointment_datetime' => 'Selected time slot is not available. Please choose another one.']);
        }

        $existingAppointment = Appointment::where('doctor_id', $doctor->id)
                                        ->where('appointment_datetime', $requestedDateTime)
                                        ->first();

        if ($existingAppointment) {
            return back()->withErrors(['appointment_datetime' => 'Selected time slot is already booked. Please choose another one.']);
        }
        
        $meetLink = null;

        // Generate Google Meet link if the appointment is online
        if ($validatedData['appointment_type'] === 'online') {
            try {
                // Initialize Google Client
                $client = new Client();
                // IMPORTANT: Configure your Google Client with credentials.
                // This typically involves setting the path to your service account key file,
                // or setting up OAuth 2.0 with client ID and secret.
                // For a Laravel app, you might load this from config/services.php or .env
                // Example for service account:
                // $client->setAuthConfig(config('services.google.service_account_key_file'));
                // $client->addScope(Calendar::CALENDAR_EVENTS);

                // Example for OAuth 2.0 (assuming you have a token or an existing flow):
                // $client->setClientId(config('services.google.client_id'));
                // $client->setClientSecret(config('services.google.client_secret'));
                // $client->setRedirectUri(config('services.google.redirect_uri'));
                // $client->setAccessType('offline');
                // $client->setPrompt('select_account consent');
                // // You would typically store and retrieve the access token/refresh token for the user
                // $accessToken = Patient::find($patient->id)->google_access_token; // Example: assuming patient has a token field
                // if ($accessToken) {
                //     $client->setAccessToken($accessToken);
                // } else {
                //     // Handle obtaining a new access token (redirect user to auth URL, then exchange code)
                //     // For background processing (like this), a service account is often more suitable.
                //     throw new \Exception('Google access token not found for patient.');
                // }
                // For simplicity in this direct implementation, we assume authentication is set up.
                // A service account approach is often preferred for server-to-server interactions.

                // Placeholder for actual client setup
                // You MUST configure $client appropriately for your application's authentication method.
                // For instance, if using a service account:
                // $client->setAuthConfig(storage_path('app/google-service-account-key.json')); // Path to your JSON key file
                // $client->addScope(Calendar::CALENDAR_EVENTS);

                // For this example, let's assume a simplified service account like setup for demonstration
                // YOU NEED TO REPLACE THIS WITH YOUR ACTUAL GOOGLE API CLIENT CONFIGURATION
                // For example, from your .env or config/services.php:
                $client->setClientId(env('GOOGLE_CLIENT_ID'));
                $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
                // $client->setRedirectUri(env('GOOGLE_REDIRECT_URI')); // Or leave empty for CLI
                // $client->addScope(Calendar::CALENDAR_EVENTS);
                // $client->setAccessToken($yourStoredAccessToken); // If using user-based OAuth

                // Use patient's stored Google tokens for authentication
                $accessToken = $patient->google_access_token;
                $refreshToken = $patient->google_refresh_token;

                if (!$accessToken || !$refreshToken) {
                    throw new \Exception('Google account not linked or tokens missing. Please link your Google account to enable online consultations.');
                }

                $client->setAccessToken($accessToken);

                // Check if the access token is expired and refresh if necessary
                if ($client->isAccessTokenExpired()) {
                    if ($refreshToken) {
                        $client->fetchAccessTokenWithRefreshToken($refreshToken);
                        $newAccessToken = $client->getAccessToken();
                        $patient->google_access_token = $newAccessToken['access_token'];
                        if (isset($newAccessToken['refresh_token'])) { // Refresh token might not be returned on refresh
                            $patient->google_refresh_token = $newAccessToken['refresh_token'];
                        }
                        $patient->save(); // Save updated tokens
                    } else {
                        throw new \Exception('Google access token expired and no refresh token available. Please re-link your Google account.');
                    }
                }

                $client->addScope(Calendar::CALENDAR_EVENTS);


                $service = new Calendar($client);

                $event = new Event(array(
                    'summary' => 'Appointment with Dr. ' . $doctor->last_name,
                    'description' => $validatedData['chief_complaint'] ?? 'Online consultation',
                    'start' => new EventDateTime([
                        'dateTime' => Carbon::parse($validatedData['appointment_datetime'])->toIso8601String(),
                        'timeZone' => config('app.timezone'), // Use your app's timezone
                    ]),
                    'end' => new EventDateTime([
                        'dateTime' => Carbon::parse($validatedData['appointment_datetime'])->addMinutes(30)->toIso8601String(),
                        'timeZone' => config('app.timezone'),
                    ]),
                    'attendees' => array(
                        new EventAttendee(['email' => $patient->user->email]), // Patient's email
                        new EventAttendee(['email' => $doctor->user->email]),  // Doctor's email
                    ),
                    'conferenceData' => new ConferenceData([
                        'createRequest' => new CreateConferenceRequest([
                            'requestId' => (string) Str::uuid(), // Unique request ID
                            'conferenceSolutionKey' => new ConferenceSolutionKey([
                                'type' => 'hangoutsMeet',
                            ]),
                        ]),
                    ]),
                ));

                $calendarId = 'primary'; // Or a specific calendar ID for the doctor/system
                $createdEvent = $service->events->insert($calendarId, $event, ['conferenceDataVersion' => 1]);

                if ($createdEvent->getConferenceData() && $createdEvent->getConferenceData()->getEntryPoints()) {
                    foreach ($createdEvent->getConferenceData()->getEntryPoints() as $entryPoint) {
                        if ($entryPoint->getEntryPointType() === 'video') {
                            $meetLink = $entryPoint->getUri();
                            break;
                        }
                    }
                }
            } catch (\Exception $e) {
                // Log the error and handle it gracefully
                \Log::error('Google Meet generation failed: ' . $e->getMessage());
                // Optionally, inform the user or fall back to a different appointment type
                return back()->withErrors(['google_meet_error' => 'Failed to generate Google Meet link. Please try again or select a clinic appointment.']);
            }
        }

        // Create the appointment
        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'clinic_id' => isset($validatedData['clinic_id']) ? $validatedData['clinic_id'] : null,
            'appointment_datetime' => $validatedData['appointment_datetime'],
            'type' => $validatedData['appointment_type'], // 'online' or 'clinic'
            'status' => 'pending', // or 'scheduled'
            'is_online' => ($validatedData['appointment_type'] === 'online'),
            'meet_link' => $meetLink, // Use the generated link
            'chief_complaint' => $validatedData['chief_complaint'],
        ]);

        return redirect()->route('patient.appointment-confirmed', ['appointment_id' => $appointment->id])->with('success', 'Appointment booked successfully!');
    }

    public function showAppointmentConfirmation($appointment_id)
    {
        $user = Auth::user();
        $patient = $user->patient;
        $appointment = Appointment::findOrFail($appointment_id);

        if ($appointment->patient_id !== $patient->id) { // Use the correctly loaded patient ID
            abort(403); // Ensure patient can only see their own appointments
        }

        return view('patient.confirm-appointment', compact('appointment'));
    }

    public function showAttendingPhysicianDetails()
    {
        $user = Auth::user();
        $patient = $user->patient;

        if (!$patient) {
            return redirect()->route('patient-details')->with('error', 'Please complete your patient profile.');
        }

         

        $attendingPhysician = $patient->attendingPhysician;

        if (!$attendingPhysician) {
            return redirect()->route('patient.select-doctor')->with('error', 'Please select your preferred attending physician.');
        }
 
        // The patient->attendingPhysician already loads the related Doctor if it was eager loaded before
        // or it will lazily load it. If you need the doctor directly, you can access $attendingPhysician->doctor

        // Load additional relationships for a more comprehensive view
        // if ($attendingPhysician && $attendingPhysician->doctor) {
        //     $attendingPhysician->doctor->load([ 
        //         'doctorAvailability.clinic'
        //     ]);
        // }

        $doctor_clinics = [];

        foreach ($attendingPhysician->doctor->doctorAvailability as $availability) {
            if ($availability->clinic && !in_array($availability->clinic->id, $doctor_clinics)) {
                $doctor_clinics[] = $availability->clinic;
            }
        }

        return view('patient.attending-physician-details', compact('attendingPhysician','doctor_clinics'));
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
    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            $authenticatedPatientUser = Auth::user(); // Get the currently authenticated user (patient)

            // Find the patient record associated with the authenticated user
            $patient = $authenticatedPatientUser->patient;

            if ($patient) {

                // dd($user->token, $user->refresh_token);
                // Store Google access token and refresh token for the patient
                // Ensure your 'patients' table has 'google_access_token' and 'google_refresh_token' columns
                $patient->google_access_token = $user->token;
                $patient->google_refresh_token = $user->refreshToken; // Will be null if access_type 'offline' not used or already consented
                $patient->save();

                return redirect()->route('patient.select-appointment-type', ['doctor_id' => session('doctor_id_for_google_auth')])->with('success', 'Google account linked successfully!');
            } else {
                return redirect()->route('patient.dashboard')->with('error', 'Patient profile not found.');
            }

        } catch (\Exception $e) {
            \Log::error('Google OAuth failed: ' . $e->getMessage());
            return redirect()->route('patient.dashboard')->with('error', 'Failed to link Google account. Please try again.');
        }
    }
    public function getPatientDetailsForApi()
    {
        $user = Auth::user();
        $patient = $user->patient;

        if (!$patient) {
            return response()->json(['error' => 'Patient profile not found.'], 404);
        }

        // Return a subset of patient data that is safe to expose to the AI
        return response()->json([
            'first_name' => $patient->first_name,
            'last_name' => $patient->last_name,
            'date_of_birth' => $patient->date_of_birth,
            'sex' => $patient->sex,
            'blood_type' => $patient->blood_type,
            'medical_conditions' => $patient->medical_conditions,
            'allergies' => $patient->allergies,
            // Add other non-sensitive fields as needed
        ]);
    }

    public function getDoctorsForApi()
    {
        // Fetch all doctors that are marked as active (if such a flag exists)
        // For simplicity, let's assume all doctors in the Doctor model are visible for recommendations.
        $doctors = Doctor::all()->map(function($doctor) {
            return [
                'id' => $doctor->id,
                'first_name' => $doctor->first_name,
                'last_name' => $doctor->last_name,
                'specialization' => $doctor->specialization,
                'years_of_experience' => $doctor->years_of_experience,
                // Add other relevant non-sensitive doctor details
            ];
        });

        return response()->json($doctors);
    }
}
