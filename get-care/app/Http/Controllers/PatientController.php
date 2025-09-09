<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Patient;
use App\Appointment; // Import the Appointment model
use App\PatientNote; // Import the PatientNote model

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
}
