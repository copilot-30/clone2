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
                                  ->orderBy('created_at', 'desc')
                                  ->take(5) // Get latest 5 notes
                                  ->get();

        return view('patient.dashboard', compact('upcomingAppointments', 'recentNotes'));
    }

    public function chat()
    {
        return view('patient.chat-interface');
    }

    public function showDoctorSelectionForm()
    {
        $patient = Auth::user()->patient;

        if (!$patient) {
            return redirect()->route('patient-details')->with('error', 'Please complete your patient profile before booking an appointment.');
        }

        // Fetch the patient's current attending physician, if any
        $attendingPhysician = $patient->attendingPhysicians()->whereNull('end_date')->first();

        // If an attending physician is set, directly proceed to selecting appointment type for that doctor
        if ($attendingPhysician) {
            return redirect()->route('patient.select-appointment-type', ['doctor_id' => $attendingPhysician->doctor_id]);
        }

        // Otherwise, show the doctor selection form for active doctors
        $doctors = Doctor::whereNull('deleted_at')->get();
        return view('patient.select-doctor', compact('doctors'));
    }

    public function storeAttendingPhysician(Request $request)
    {
        $patient = Auth::user()->patient;

        if (!$patient) {
            return back()->with('error', 'Patient profile not found.');
        }

        $validatedData = $request->validate([
            'doctor_id' => 'required|uuid|exists:doctor_profiles,id',
        ]);

        // Check if this patient-doctor relationship already exists
        $existingAttendingPhysician = AttendingPhysician::where('patient_id', $patient->id)
                                                        ->where('doctor_id', $validatedData['doctor_id'])
                                                        ->first();

        if ($existingAttendingPhysician) {
            return back()->with('error', 'This doctor is already set as your attending physician.');
        }

        AttendingPhysician::create([
            'patient_id' => $patient->id,
            'doctor_id' => $validatedData['doctor_id'],
            'start_date' => now(), // Set the start date to now
        ]);

        return redirect()->route('patient.dashboard')->with('success', 'Attending physician assigned successfully!');
    }

    public function showAppointmentTypeForm($doctor_id)
    {
        $patient = Auth::user()->patient;
        if (!$patient) {
            return redirect()->route('patient-details')->with('error', 'Please complete your patient profile before booking an appointment.');
        }

        $doctor = Doctor::findOrFail($doctor_id);

        // Check if the patient has this doctor as their attending physician, or allow selection if no attending physician is set
        $isAttendingPhysician = $patient->attendingPhysicians()->where('doctor_id', $doctor->id)->whereNull('end_date')->exists();
        if (!$isAttendingPhysician && $patient->attendingPhysicians()->whereNull('end_date')->exists()) {
            return redirect()->route('patient.dashboard')->with('error', 'You can only book appointments with your assigned attending physician, or select a new one first.');
        }
        
        return view('patient.select-appointment-type', compact('doctor'));
    }

    public function showDateTimeSelectionForm(Request $request)
    {
        $patient = Auth::user()->patient;
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
        $slots = [];
        $today = Carbon::today();
        for ($i = 0; $i < 30; $i++) {
            $date = $today->copy()->addDays($i);
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
        $patient = Auth::user()->patient;
        if (!$patient) {
            return redirect()->route('patient-details')->with('error', 'Please complete your patient profile before booking an appointment.');
        }

        $validatedData = $request->validate([
            'doctor_id' => 'required|uuid|exists:doctor_profiles,id',
            'appointment_type' => 'required|string|in:online,clinic',
            'clinic_id' => 'nullable|uuid|exists:clinics,id',
            'appointment_datetime' => 'required|date_format:Y-m-d H:i',
            'chief_complaint' => 'nullable|string|max:1000',
        ]);

        $doctor = Doctor::findOrFail($validatedData['doctor_id']);

        // Check if the patient has this doctor as their attending physician, or allow selection if no attending physician is set
        $isAttendingPhysician = $patient->attendingPhysicians()->where('doctor_id', $doctor->id)->whereNull('end_date')->exists();
        if (!$isAttendingPhysician && $patient->attendingPhysicians()->whereNull('end_date')->exists()) {
            return redirect()->route('patient.dashboard')->with('error', 'You can only book appointments with your assigned attending physician, or select a new one first.');
        }

        // Validate that the selected slot is actually available based on doctor's availability and no existing appointments
        $requestedDateTime = Carbon::parse($validatedData['appointment_datetime']);
        $dayOfWeek = $requestedDateTime->dayOfWeekIso;
        $requestedTime = $requestedDateTime->format('H:i:s');

        $availableSlot = DoctorAvailability::where('doctor_id', $doctor->id)
                                        ->where('is_active', true)
                                        ->where('day_of_week', $dayOfWeek)
                                        ->where('start_time', '<=', $requestedTime)
                                        ->where('end_time', '>', $requestedTime)
                                        ->when($validatedData['clinic_id'], function ($query, $clinicId) {
                                            return $query->where('clinic_id', $clinicId);
                                        })
                                        ->first();

        if (!$availableSlot) {
            return back()->withErrors(['appointment_datetime' => 'Selected time slot is not available. Please choose another one.']);
        }

        $existingAppointment = Appointment::where('doctor_id', $doctor->id)
                                        ->where('appointment_datetime', $requestedDateTime)
                                        ->first();

        if ($existingAppointment) {
            return back()->withErrors(['appointment_datetime' => 'Selected time slot is already booked. Please choose another one.']);
        }
        
        // Create the appointment
        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'clinic_id' => $validatedData['clinic_id'],
            'appointment_datetime' => $validatedData['appointment_datetime'],
            'type' => $validatedData['appointment_type'], // 'online' or 'clinic'
            'status' => 'pending', // or 'scheduled'
            'is_online' => ($validatedData['appointment_type'] === 'online'),
            'meet_link' => ($validatedData['appointment_type'] === 'online') ? 'https://meet.google.com/example' : null, // Generate actual link
            'chief_complaint' => $validatedData['chief_complaint'],
            'duration_minutes' => 30, // Assuming 30-minute slots
        ]);

        return redirect()->route('patient.appointment-confirmed', ['appointment_id' => $appointment->id])->with('success', 'Appointment booked successfully!');
    }

    public function showAppointmentConfirmation($appointment_id)
    {
        $appointment = Appointment::with(['patient', 'doctor', 'clinic'])->findOrFail($appointment_id);

        if ($appointment->patient_id !== Auth::user()->patient->id) {
            abort(403); // Ensure patient can only see their own appointments
        }

        return view('patient.confirm-appointment', compact('appointment'));
    }

    public function showAttendingPhysicianDetails()
    {
        $patient = Auth::user()->patient;

        if (!$patient) {
            return redirect()->route('patient-details')->with('error', 'Please complete your patient profile.');
        }

        $attendingPhysician = $patient->attendingPhysicians()->with('doctor')->whereNull('end_date')->first();

        return view('patient.attending-physician-details', compact('attendingPhysician'));
    }
}
