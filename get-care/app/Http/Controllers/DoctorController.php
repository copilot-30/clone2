<?php

namespace App\Http\Controllers;

use App\Doctor;
use App\User;
use App\Patient;
use App\Appointment;
use App\Payment;
use App\DoctorAvailability;
use App\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage; // Add Storage facade
use App\Events\AuditableEvent;
use Auth;
use Illuminate\Support\Str;
use App\SharedCase; // Add this line
use App\FileAttachment; // Import FileAttachment model
use App\LabRequest; // Import LabRequest model
use App\LabResult; // Import LabResult model

class DoctorController extends Controller
{
    public function dashboard()
    {
        $doctor = Auth::user()->doctor;

        if (!$doctor) {
            return redirect()->route('doctor.create')->withErrors('Please complete your doctor profile.');
        }

        // Fetch upcoming appointments for the authenticated doctor
        $upcomingAppointments = Appointment::where('doctor_id', $doctor->id)
            ->where('appointment_datetime', '>=', now())
            ->orderBy('appointment_datetime')
            ->limit(5) // Limit to 5 upcoming appointments for the dashboard
            ->with(['patient', 'clinic'])
            ->get();

        // Count total unique patients for this doctor
        $totalPatients = Patient::where(function ($query) use ($doctor) {
            $query->whereHas('appointments', function ($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id);
            })->orWhereHas('attendingPhysician', function ($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id);
            });
        })->distinct('id')->count();

        // Count pending shared cases for this doctor as a receiver
        $pendingSharedCases = SharedCase::where('receiving_doctor_id', $doctor->id)
            ->where('status', 'PENDING')
            ->count();

        return view('doctor.dashboard', compact('upcomingAppointments', 'totalPatients', 'pendingSharedCases'));
    }

    public function createDoctor(Request $request)
    {
        $doctor = Auth::user()->doctor;
 
        if (!$doctor) {
            return view('doctor.create-doctor', compact('doctor'));
        }else{
            return redirect()->route('doctor.dashboard')->withErrors('You already have a doctor profile.');
        }
    }

    public function editDoctor(Request $request)
    {
        $doctor = Auth::user()->doctor;
        if (!$doctor) {
            dd("Unauthorized");
        }else{
            return view('doctor.edit-doctor', compact('doctor'));
        }
    }

    public function storeDoctorDetails(Request $request)
    {
        $user = User::findOrFail(Auth::user()->id);

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'sex' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'specialization' => 'required|string|max:255',
            'years_of_experience' => 'nullable|integer',
            'certifications' => 'nullable|string',
            'prc_license_number' => 'required|string|unique:doctor_profiles,prc_license_number',
            'ptr_license_number' => 'required|string|unique:doctor_profiles,ptr_license_number',
            'affiliated_hospital' => 'nullable|string',
            'training' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Doctor::create([
            'user_id' => $user->id,
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'sex' => $request->input('sex'),
            'phone_number' => $request->input('phone_number'),
            'email' => $user->email,
            'specialization' => $request->input('specialization'),
            'years_of_experience' => $request->input('years_of_experience'),
            'certifications' => $request->input('certifications'),
            'prc_license_number' => $request->input('prc_license_number'),
            'ptr_license_number' => $request->input('ptr_license_number'),
            'affiliated_hospital' => $request->input('affiliated_hospital'),
            'training' => $request->input('training'),
        ]);

        event(new AuditableEvent(auth()->id(), 'doctor_details_added', [
            'doctor_user_id' => $user->id,
            'email' => $user->email,
        ]));

        return redirect()->route('doctor.edit')->with('success', 'Doctor details added successfully.');
    }

    public function updateDoctorDetails(Request $request)
    {
        $user = User::findOrFail(Auth::user()->id);
        $doctor = Doctor::where('user_id', $user->id)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'sex' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'specialization' => 'required|string|max:255',
            'years_of_experience' => 'nullable|integer',
            'certifications' => 'nullable|string',
            'prc_license_number' => 'required|string|unique:doctor_profiles,prc_license_number,' . $doctor->id,
            'ptr_license_number' => 'required|string|unique:doctor_profiles,ptr_license_number,' . $doctor->id,
            'affiliated_hospital' => 'nullable|string',
            'training' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $doctor->update([
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'sex' => $request->input('sex'),
            'phone_number' => $request->input('phone_number'),
            'specialization' => $request->input('specialization'),
            'years_of_experience' => $request->input('years_of_experience'),
            'certifications' => $request->input('certifications'),
            'prc_license_number' => $request->input('prc_license_number'),
            'ptr_license_number' => $request->input('ptr_license_number'),
            'affiliated_hospital' => $request->input('affiliated_hospital'),
            'training' => $request->input('training'),
        ]);

        event(new AuditableEvent(auth()->id(), 'doctor_details_updated', [
            'doctor_user_id' => $user->id,
            'email' => $user->email,
        ]));

        return redirect()->back()->with('success', 'Doctor details updated successfully.');
    }

    private static $dayMapping = [
        'Monday' => 1,
        'Tuesday' => 2,
        'Wednesday' => 3,
        'Thursday' => 4,
        'Friday' => 5,
        'Saturday' => 6,
        'Sunday' => 7,
    ];

    private static $reverseDayMapping = [
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
        7 => 'Sunday',
    ];

    public function editAvailability(Request $request)
    {
        // For a doctor, get their own availability
        $doctor_id = Auth::user()->doctor->id; // Assuming doctor profile is linked to user

        $availability = DoctorAvailability::where('doctor_id', $doctor_id)->get();

        // Organize availability by day for easier display in the view
        $organizedAvailability = [];
        foreach ($availability as $slot) {
            $dayName = self::$reverseDayMapping[$slot->day_of_week] ?? 'Unknown'; // Handle potential unknown values
            $organizedAvailability[$dayName][] = [
                'start_time' => $slot->start_time,
                'end_time' => $slot->end_time,
                'is_active' => $slot->is_active, // Ensure consistency
                'id' => $slot->id,
                'type' => $slot->clinic_id ? 'face_to_face' : 'online_consultation', // Derive type
                'clinic_id' => $slot->clinic_id,
                'availability_type' => $slot->availability_type,
            ];
        }

        // Default availability for each day if not set
        foreach (self::$dayMapping as $dayName => $dayInt) {
            if (!isset($organizedAvailability[$dayName])) {
                $organizedAvailability[$dayName] = []; // Initialize empty array for days with no slots
            }
        }
        
        // Get the overall availability status (enabled/disabled)
        // This might be stored on the Doctor model or a separate config
        // For now, let's assume a default 'enabled' if not explicitly set.
        $availability_status = Auth::user()->doctor->online_availability_enabled ?? true;

        $clinics = Clinic::where('is_active', true)->get();

        return view('doctor.availability', compact('organizedAvailability', 'availability_status', 'clinics'));
    }

    public function updateAvailability(Request $request)
    {
        $doctor_id = Auth::user()->doctor->id;

        // Validate the submitted data
        $validator = Validator::make($request->all(), [
            'status' => 'required|boolean', // Overall enabled/disabled status
            'availability' => 'array',
            'availability.*.day_of_week' => 'required|string', // day_of_week is now part of each slot
            'availability.*.start_time' => 'required|date_format:H:i',
            'availability.*.end_time' => 'required|date_format:H:i|after:availability.*.start_time',
            'availability.*.id' => 'nullable|string', // For existing records
            'availability.*.type' => 'required|in:online_consultation,face_to_face',
            'availability.*.clinic_id' => 'nullable|uuid',
            'availability.*.clinic_id' => 'nullable|uuid',
            'availability.*.is_active' => 'nullable|boolean', // Add validation for is_active
            'availability.*.availability_type' => 'nullable|array', // New: Add validation for availability_type
            'availability.*.availability_type.*' => 'string|in:consultation,follow-up', // Validate array elements
        ]);
        // Custom rule to validate day_of_week values
        $validator->sometimes('availability.*.day_of_week', ['in:' . implode(',', array_keys(self::$dayMapping))], function ($input) {
            return is_array($input['availability']) && count($input['availability']) > 0;
        });

        // Add a rule to ensure clinic_id is valid based on the consultation type
        $validator->after(function ($validator) {
            foreach ($validator->getData()['availability'] ?? [] as $index => $slot) {
                if ($slot['type'] === 'online_consultation') {
                    if (!empty($slot['clinic_id'])) {
                        $validator->errors()->add("availability.{$index}.clinic_id", 'Clinic ID must be null for online consultations.');
                    }
                } elseif ($slot['type'] === 'face_to_face') {
                    if (empty($slot['clinic_id'])) {
                        $validator->errors()->add("availability.{$index}.clinic_id", 'Clinic ID is required for face-to-face consultations.');
                    } elseif (!Str::isUuid($slot['clinic_id'])) {
                        $validator->errors()->add("availability.{$index}.clinic_id", 'Invalid Clinic ID format.');
                    }
                }
            }
        });
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::transaction(function () use ($request, $doctor_id) {
            // Update overall availability status on Doctor model
            $doctor = Auth::user()->doctor;
            $doctor->online_availability_enabled = $request->input('status');
            $doctor->save();
 
            // First, delete existing availability for the doctor to handle removals
            DoctorAvailability::where('doctor_id', $doctor_id)->delete();

            // Then, re-create or update based on the submitted data
            foreach ($request->input('availability', []) as $slotData) {
                DoctorAvailability::create([
                    'doctor_id' => $doctor_id,
                    'day_of_week' => self::$dayMapping[$slotData['day_of_week']], // Convert string day to integer
                    'start_time' => $slotData['start_time'],
                    'end_time' => $slotData['end_time'],
                    'is_active' => isset($slotData['is_active']), // Correctly handle checkbox value
                    'clinic_id' => ($slotData['type'] === 'face_to_face') ? $slotData['clinic_id'] : null,
                    'availability_type' => $slotData['availability_type'] ?? [], // Save availability type
                ]);
            }
        });

        return redirect()->back()->with('success', 'Availability updated successfully!');
    }

    public function listClinics()
    {
        $clinics = Clinic::all();
        return view('doctor.clinic-list', compact('clinics'));
    }

    public function createClinic()
    {
        return view('doctor.clinic-create');
    }

    public function storeClinic(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'operating_hours' => 'nullable|array',
            'operating_hours.*.start' => 'nullable|date_format:H:i',
            'operating_hours.*.end' => 'nullable|date_format:H:i|after:operating_hours.*.start',
            'facilities' => 'nullable|array',
            'facilities.*' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'is_hospital' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validatedData = $validator->validated();

        // Handle operating_hours and facilities conversion
        $operatingHours = [];
        if (isset($validatedData['operating_hours'])) {
            foreach ($validatedData['operating_hours'] as $day => $times) {
                if (!empty($times['start']) && !empty($times['end'])) {
                    $operatingHours[$day] = $times;
                }
            }
        }
        $validatedData['operating_hours'] = $operatingHours;

        $facilities = [];
        if (isset($validatedData['facilities'])) {
            $facilities = array_values(array_filter($validatedData['facilities'])); // Remove empty and re-index
        }
        $validatedData['facilities'] = $facilities;

        $clinic = Clinic::create($validatedData);

        event(new AuditableEvent(auth()->id(), 'clinic_created', [
            'clinic_id' => $clinic->id,
            'clinic_name' => $clinic->name,
        ]));

        return redirect()->route('doctor.clinics.list')->with('success', 'Clinic created successfully.');
    }

    public function editClinic(Clinic $clinic)
    {
        return view('doctor.clinic-edit', compact('clinic'));
    }

    public function updateClinic(Request $request, Clinic $clinic)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'operating_hours' => 'nullable|array',
            'operating_hours.*.start' => 'nullable|date_format:H:i',
            'operating_hours.*.end' => 'nullable|date_format:H:i|after:operating_hours.*.start',
            'facilities' => 'nullable|array',
            'facilities.*' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'is_hospital' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validatedData = $validator->validated();

        // Handle operating_hours and facilities conversion
        $operatingHours = [];
        if (isset($validatedData['operating_hours'])) {
            foreach ($validatedData['operating_hours'] as $day => $times) {
                if (!empty($times['start']) && !empty($times['end'])) {
                    $operatingHours[$day] = $times;
                }
            }
        }
        $validatedData['operating_hours'] = $operatingHours;

        $facilities = [];
        if (isset($validatedData['facilities'])) {
            $facilities = array_values(array_filter($validatedData['facilities'])); // Remove empty and re-index
        }
        $validatedData['facilities'] = $facilities;

        $clinic->update($validatedData);

        event(new AuditableEvent(auth()->id(), 'clinic_updated', [
            'clinic_id' => $clinic->id,
            'clinic_name' => $clinic->name,
        ]));

        return redirect()->route('doctor.clinics.list')->with('success', 'Clinic updated successfully.');
    }

    public function deleteClinic(Clinic $clinic)
    {
        $clinic->delete();

        event(new AuditableEvent(auth()->id(), 'clinic_deleted', [
            'clinic_id' => $clinic->id,
            'clinic_name' => $clinic->name,
        ]));

        return redirect()->route('doctor.clinics.list')->with('success', 'Clinic deleted successfully.');
    }
    public function listAppointments()
    {
        $doctor = Auth::user()->doctor;
        $appointments = Appointment::where('doctor_id', $doctor->id)
            ->with(['patient', 'clinic'])
            ->orderBy('appointment_datetime', 'desc')
            ->get();

        return view('doctor.appointments-list', compact('appointments'));
    }

    public function viewAppointment(Appointment $appointment)
    {
        // Ensure the appointment belongs to the authenticated doctor
        if ($appointment->doctor_id !== Auth::user()->doctor->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('doctor.appointment-details', compact('appointment'));
    }

    public function cancelAppointment(Request $request, Appointment $appointment)
    {
        // Ensure the appointment belongs to the authenticated doctor
        if ($appointment->doctor_id !== Auth::user()->doctor->id) {
            abort(403, 'Unauthorized action.');
        }

        $validator = Validator::make($request->all(), [
            'cancellation_reason' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $appointment->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->input('cancellation_reason'),
        ]);

        event(new AuditableEvent(auth()->id(), 'doctor_appointment_cancelled', [
            'appointment_id' => $appointment->id,
            'patient_id' => $appointment->patient_id,
            'doctor_id' => $appointment->doctor_id,
        ]));

        return response()->json(['success' => true, 'message' => 'Appointment cancelled successfully.']);
    }
public function storeAppointment(Request $request)
{
    $validator = Validator::make($request->all(), [
        'patient_id' => 'required|uuid|exists:patients,id',
        'appointment_date' => 'required|date',
        'appointment_time' => 'required|date_format:H:i',
        'reason' => 'nullable|string',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $doctor = Auth::user()->doctor;

    Appointment::create([
        'patient_id' => $request->input('patient_id'),
        'doctor_id' => $doctor->id,
        'appointment_datetime' => $request->input('appointment_date') . ' ' . $request->input('appointment_time'),
        'reason' => $request->input('reason'),
        'status' => 'scheduled',
    ]);

    event(new AuditableEvent(auth()->id(), 'doctor_created_appointment', [
        'patient_id' => $request->input('patient_id'),
        'doctor_id' => $doctor->id,
    ]));

    return redirect()->back()->with('success', 'Appointment booked successfully!');
}

/**
 * Store a new SOAP note for a patient
 *
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public function storeSoapNote(Request $request)
{
 
    $validator = Validator::make($request->all(), [
        'patient_id' => 'required|uuid|exists:patients,id',
        'subjective' => 'nullable|string',
        'chief_complaint' => 'nullable|string', // New
        'history_of_illness' => 'nullable|string', // New
        'objective' => 'nullable|string',
        'weight' => 'nullable|numeric', // New - Vital Signs
        'height' => 'nullable|numeric', // New - Vital Signs
        'bmi' => 'nullable|numeric', // New - Vital Signs
        'blood_pressure' => 'nullable|string', // New - Vital Signs
        'oxygen_saturation' => 'nullable|numeric', // New - Vital Signs
        'respiratory_rate' => 'nullable|numeric', // New - Vital Signs
        'heart_rate' => 'nullable|numeric', // New - Vital Signs
        'body_temperature' => 'nullable|numeric', // New - Vital Signs
        'capillary_blood_glucose' => 'nullable|numeric', // New - Vital Signs
        'vitals_remark' => 'nullable|string', // New - Vital Signs additional notes
        'laboratory_results' => 'nullable|string', // New
        'imaging_results' => 'nullable|string', // New
        'assessment' => 'nullable|string',
        'diagnosis' => 'nullable|string', // New
        'plan' => 'nullable|string',
        'prescription' => 'nullable|string', // New
        'test_request' => 'nullable|string', // New
        'remarks' => 'nullable|string', // New
        'file_remarks' => 'nullable|string', // New 
        'follow_up_date' => 'nullable|date', // New
        'lab_files' => 'nullable|array', // Allow multiple lab files
        'lab_files.*' => 'file|mimes:pdf,jpg,png,doc,docx|max:256000', // Validate each file, max 256MB
    ]);

    if ($validator->fails()) {
        return response()->json(['success' => false, 'message' => 'Validation failed.', 'errors' => $validator->errors()], 422);
    }

    $doctor = Auth::user()->doctor;
    $patientId = $request->input('patient_id');

    // Verify that the doctor has permission to add SOAP notes for this patient
    $patient = Patient::findOrFail($patientId);
    
    // Check if doctor is the attending physician
    $isAttendingPhysician = $patient->attendingPhysician && $patient->attendingPhysician->doctor_id === $doctor->id;
    
    // Check if doctor is a receiving doctor for an accepted shared case
    $isReceivingDoctor = $patient->sharedCases()
        ->where('receiving_doctor_id', $doctor->id)
        ->where('status', 'ACCEPTED')
        ->exists();
    
    if (!$isAttendingPhysician && !$isReceivingDoctor) {
        return response()->json(['success' => false, 'message' => 'Unauthorized to add SOAP notes for this patient.'], 403);
    }

    // Prepare vital signs data
    $vitalSigns = [
        'weight' => $request->input('weight'),
        'height' => $request->input('height'),
        'bmi' => $request->input('bmi'),
        'blood_pressure' => $request->input('blood_pressure'),
        'oxygen_saturation' => $request->input('oxygen_saturation'),
        'respiratory_rate' => $request->input('respiratory_rate'),
        'heart_rate' => $request->input('heart_rate'),
        'body_temperature' => $request->input('body_temperature'),
        'capillary_blood_glucose' => $request->input('capillary_blood_glucose'),
        'vitals_remark' => $request->input('vitals_remark'),
    ];

    // Create the SOAP note
    $soapNote = \App\Consultation::create([
        'patient_id' => $patientId,
        'doctor_id' => $doctor->id,
        'date' => now(),
        'subjective' => $request->input('subjective'),
        'chief_complaint' => $request->input('chief_complaint'),
        'history_of_illness' => $request->input('history_of_illness'),
        'objective' => $request->input('objective'),
        'vital_signs' => empty(array_filter($vitalSigns)) ? null : $vitalSigns, // Store as JSON
        'assessment' => $request->input('assessment'),
        'diagnosis' => $request->input('diagnosis'),
        'plan' => $request->input('plan'),
        'prescription' => $request->input('prescription'),
        'test_request' => $request->input('test_request'),
        'remarks' => $request->input('remarks'),
        'remarks_note' => $request->input('remarks_note'),
        'remarks_template' => $request->input('remarks_template'),
        'follow_up_date' => $request->input('follow_up_date'),
    ]);

    event(new AuditableEvent(auth()->id(), 'soap_note_created', [
        'soap_note_id' => $soapNote->id,
        'patient_id' => $patientId,
        'doctor_id' => $doctor->id,
    ]));
// Handle lab file uploads
if ($request->hasFile('lab_files')) {
    foreach ($request->file('lab_files') as $file) {
        $path = $file->store('lab_results', 'public'); // Store in 'storage/app/public/lab_results'
        
        // Create a LabResult entry for each file
        // Create a LabResult entry for each file
        $labResult = LabResult::create([
            'test_request_id' => null, // If there's no explicit LabRequest for this file, leave null
            'patient_id' => $patientId,
            'result_data' => json_encode([
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'laboratory_results_text' => $request->input('laboratory_results'), // Include text results
                'imaging_results_text' => $request->input('imaging_results'), // Include text results
            ]),
            'result_file_url' => Storage::url($path),
            'result_date' => now(),
            'notes' => $request->input('remarks_note') ?? 'Uploaded with SOAP note (Consultation ID: ' . $soapNote->id . ')',
        ]);
        
        // Create a FileAttachment record for generic tracking, linking to the LabResult
        FileAttachment::create([
            'entity_type' => 'App\\LabResult', // Link to LabResult model
            'entity_id' => $labResult->id, // Link to the newly created LabResult
            'file_name' => $file->getClientOriginalName(),
            'file_url' => Storage::url($path),
            'file_size' => $file->getSize(),
            'file_type' => $file->getMimeType(),
            'uploaded_by_id' => Auth::id(),
        ]);
    } // Closes the foreach loop
} // Closes the if ($request->hasFile('lab_files')) block

return response()->json(['success' => true, 'message' => 'SOAP note added successfully.', 'soap_note' => $soapNote]);
} // Closes the storeSoapNote method
public function viewPatients(Request $request, $patient_id = null)
{
    $doctor = Auth::user()->doctor;
    $filter = $request->query('filter', 'my-patients'); // Default to 'my-patients'

    $patients = collect(); // Initialize an empty collection

    if ($filter === 'my-patients' or $filter === '') {
        $patients = Patient::where(function ($query) use ($doctor) {
            $query->whereHas('appointments', function ($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id);
            })->orWhereHas('attendingPhysician', function ($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id);
            });
        })
        ->with(['medicalBackground', 'attendingPhysician.doctor', 'sharedCases.sharingDoctor', 'sharedCases.receivingDoctor', 'soapNotes', 'patientNotes', 'appointments'])
        ->get();
    } elseif ($filter === 'shared-cases') {
        $patients = Patient::whereHas('sharedCases', function ($query) use ($doctor) {
            $query->where('receiving_doctor_id', $doctor->id)
                  ->where('status', 'ACCEPTED');
        })
        ->with(['medicalBackground', 'attendingPhysician.doctor', 'sharedCases.sharingDoctor', 'sharedCases.receivingDoctor', 'soapNotes', 'patientNotes', 'appointments'])
        ->get();
    }

    $selectedPatient = null;

    if ($patient_id) {
        // Find the patient in the filtered list
        $selectedPatient = $patients->firstWhere('id', $patient_id);

        // If not found in filtered list, try to find it directly (e.g., if link was directly clicked for a patient not in the current filter)
        if (!$selectedPatient) {
            $selectedPatient = Patient::where('id', $patient_id)
                ->with(['medicalBackground', 'attendingPhysician.doctor', 'sharedCases.sharingDoctor', 'sharedCases.receivingDoctor', 'soapNotes', 'patientNotes', 'appointments'])
                ->first();
        }

        if (!$selectedPatient) {
            return redirect()->route('doctor.patients.view', ['filter' => $filter])->with('error', 'Patient not found or not part of the selected filter.');
        }
    } elseif ($patients->isNotEmpty()) {
        $selectedPatient = $patients->first(); // Select the first patient by default if no patient_id is provided
    }
 
    return view('doctor.patient-view', compact('patients', 'selectedPatient', 'filter', 'doctor'));
}

public function storeSharedCase(Request $request)
{
    $validator = Validator::make($request->all(), [
        'patient_id' => 'required|uuid|exists:patients,id',
        'receiving_doctor_id' => 'required|uuid|exists:doctor_profiles,id',
        'case_description' => 'required|string',
        'urgency' => 'required|string|in:high,medium,low', // Add validation for urgency
        'permissions' => 'nullable|array',
        'expires_at' => 'nullable|date',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $sharingDoctor = Auth::user()->doctor;
    $receivingDoctor = Doctor::findOrFail($request->input('receiving_doctor_id'));

    if ($sharingDoctor->id === $receivingDoctor->id) {
        return redirect()->back()->with('error', 'You cannot share a case with yourself.')->withInput();
    }
 
    SharedCase::create([
        'patient_id' => $request->input('patient_id'),
        'sharing_doctor_id' => $sharingDoctor->id,
        'receiving_doctor_id' => $receivingDoctor->id,
        'case_description' => $request->input('case_description'),
        'shared_data' => $request->input('permissions'), // This should be an array of permissions
        'permissions' => $request->input('permissions'), // Keeping for compatibility, ideally combine with shared_data
        'status' => 'PENDING',
        'expires_at' => $request->input('expires_at'),
        'urgency' => $request->input('urgency'), // Save urgency
    ]);

    event(new AuditableEvent(auth()->id(), 'shared_case_created', [
        'patient_id' => $request->input('patient_id'),
        'sharing_doctor_id' => $sharingDoctor->id,
        'receiving_doctor_id' => $receivingDoctor->id,
    ]));

    return redirect()->back()->with('success', 'Case shared successfully and is pending acceptance.');
}

public function listSharedCases(Request $request, $filter = null)
{
    $doctor = Auth::user()->doctor;

    $urgencyCaseStatement = "CASE WHEN urgency = 'High' THEN 0 WHEN urgency = 'Medium' THEN 1 WHEN urgency = 'Low' THEN 2 ELSE 3 END as urgency_order";

    $baseQueryReceived = SharedCase::where(function($query) use ($doctor) {
        $query->where('receiving_doctor_id', $doctor->id)
        ->orWhere('sharing_doctor_id', $doctor->id);
    })
        ->select('*', DB::raw($urgencyCaseStatement)) // Select all columns and the urgency_order
        ->with(['patient' => function ($query) {
            $query->select('*');
        }, 'sharingDoctor', 'receivingDoctor']);

 

    // Calculate counts for the header
    $pendingSharedCasesCount = (clone $baseQueryReceived)->where('status', 'PENDING')->count();
    $acceptedSharedCasesCount = (clone $baseQueryReceived)->where('status', 'ACCEPTED')->count();
    $declinedSharedCasesCount = (clone $baseQueryReceived)->where('status', 'DECLINED')->count();
    $revokedSharedCasesCount = (clone $baseQueryReceived)->where('status', 'REVOKED')->count();
    $cancelledSharedCasesCount = (clone $baseQueryReceived)->where('status', 'CANCELLED')->count();
    $totalSharedCases = (clone $baseQueryReceived)->count();

    // Apply filter
    $sharedCases = collect();
    
    if ($filter === 'PENDING') {
        $sharedCases = (clone $baseQueryReceived)
            ->where('status', 'PENDING')
            ->orderBy('urgency_order') // Now order by the derived column
            ->orderBy('created_at', 'desc')
            ->get();
    } elseif ($filter === 'ACCEPTED') {
        $sharedCases = (clone $baseQueryReceived)->where('status', 'ACCEPTED')->orderBy('created_at', 'desc')->get();
    } elseif ($filter === 'DECLINED') {
        $sharedCases = (clone $baseQueryReceived)->where('status', 'DECLINED')->orderBy('created_at', 'desc')->get();
    } elseif ($filter === 'REVOKED') {
        $sharedCases = (clone $baseQueryReceived)->where('status', 'REVOKED')->orderBy('created_at', 'desc')->get();
    }
    elseif ($filter === 'CANCELLED') {
        $sharedCases = (clone $baseQueryReceived)->where('status', 'CANCELLED')->orderBy('created_at', 'desc')->get();
    }
    
    elseif ($filter === 'ALL' || is_null($filter)) {
        // For 'ALL' cases, combine and then sort by urgency_order
        $sharedCases = $baseQueryReceived
            ->orderBy('urgency_order') // Order by the derived column after union
            ->orderBy('created_at', 'desc')
            ->get();
    } else {
        // Default to showing all cases if an invalid filter is provided
        $sharedCases = (clone $baseQueryReceived) 
            ->orderBy('urgency_order') // Order by the derived column after union
            ->orderBy('created_at', 'desc')
            ->get();
    }

    foreach ($sharedCases as $sharedCase) {
        // Ensure patient->date_of_birth is used for age calculation
        $sharedCase->patient_age = $this->getPatientAge($sharedCase->patient->date_of_birth);

        $symptoms = null;
        $duration = null;
        $testsDone = null;

        // Use the urgency field directly if available, otherwise default to 'Normal'
        $urgency = $sharedCase->urgency ?? 'Normal';

        // Parse symptoms, duration, and tests done from case_description for backward compatibility if needed,
        // or ensure these are stored in structured fields directly.
        // For now, assuming case_description might still contain this info for older entries.
        if (preg_match('/Symptoms:\s*(.*?)(?:Duration:|$)/i', $sharedCase->case_description, $matches)) {
            $symptoms = trim($matches[1]);
        }
        if (preg_match('/Duration:\s*(.*?)(?:Tests Done:|$)/i', $sharedCase->case_description, $matches)) {
            $duration = trim($matches[1]);
        }
        if (preg_match('/Tests Done:\s*(.*)/i', $sharedCase->case_description, $matches)) {
            $testsDone = trim($matches[1]);
        }

        $sharedCase->parsed_description = [
            'symptoms' => $symptoms,
            'duration' => $duration,
            'tests_done' => $testsDone,
            'urgency' => $urgency, // Assign urgency to parsed_description
        ];
    }
    
    return view('doctor.shared-cases.shared-cases', compact( 
        'doctor', 
        'sharedCases', 
        'pendingSharedCasesCount', 
        'acceptedSharedCasesCount', 
        'declinedSharedCasesCount',  
        'totalSharedCases', 
        'cancelledSharedCasesCount',
        'revokedSharedCasesCount',
        'filter'
    ));
}
public function acceptSharedCaseInvitation(SharedCase $sharedCase)
{
    // Ensure the shared case is pending and for the authenticated doctor
    if ($sharedCase->receiving_doctor_id !== Auth::user()->doctor->id || $sharedCase->status !== 'PENDING') {
        abort(403, 'Unauthorized action or invalid shared case status.');
    }

    $sharedCase->status = 'ACCEPTED';
    $sharedCase->save();

    event(new AuditableEvent(auth()->id(), 'shared_case_accepted', [
        'shared_case_id' => $sharedCase->id,
        'patient_id' => $sharedCase->patient_id,
        'receiving_doctor_id' => Auth::user()->doctor->id,
    ]));

    return redirect()->back()->with('success', 'Shared case accepted successfully.');
}

public function declineSharedCaseInvitation(SharedCase $sharedCase)
{
    // Ensure the shared case is pending and for the authenticated doctor
    if ($sharedCase->receiving_doctor_id !== Auth::user()->doctor->id || $sharedCase->status !== 'PENDING') {
        abort(403, 'Unauthorized action or invalid shared case status.');
    }

    $sharedCase->status = 'DECLINED';
    $sharedCase->save();

    event(new AuditableEvent(auth()->id(), 'shared_case_declined', [
        'shared_case_id' => $sharedCase->id,
        'patient_id' => $sharedCase->patient_id,
        'receiving_doctor_id' => Auth::user()->doctor->id,
    ]));

    return redirect()->back()->with('success', 'Shared case invitation declined.');
}

public function cancelSharedCaseInvitation(SharedCase $sharedCase)
{
    // Ensure the shared case is pending and was sent by the authenticated doctor
    if ($sharedCase->sharing_doctor_id !== Auth::user()->doctor->id || $sharedCase->status !== 'PENDING') {
        abort(403, 'Unauthorized action or invalid shared case status.');
    }

    $sharedCase->status = 'CANCELLED';
    $sharedCase->save();

    event(new AuditableEvent(auth()->id(), 'shared_case_cancelled', [
        'shared_case_id' => $sharedCase->id,
        'patient_id' => $sharedCase->patient_id,
        'sharing_doctor_id' => Auth::user()->doctor->id,
    ]));

    return response()->json(['success' => true, 'message' => 'Shared case invitation cancelled successfully.']);
}

public function searchDoctors(Request $request)
{
    $query = $request->input('query');
    $patientId = $request->input('patient_id');
    $currentDoctorId = Auth::user()->doctor->id;

    $eligibleDoctors = Doctor::where(function($q) use ($query) {
        $q->where('first_name', 'ILIKE',  $query . '%')
          ->orWhere('last_name', 'ILIKE',  $query . '%')
          ->orWhere('email', 'ILIKE',  $query . '%');
    })
    ->where('id', '!=', $currentDoctorId) // Exclude current doctor
    ->whereDoesntHave('attendingPhysicians', function($q) use ($patientId) {
        $q->where('patient_id', $patientId);
    }) // Exclude patient's primary physician
    ->whereDoesntHave('sharedCasesAsReceiver', function($q) use ($patientId) {
        $q->where('patient_id', $patientId)
          ->whereIn('status', ['PENDING', 'ACCEPTED']); // Exclude already invited/accepted doctors for this patient
    })
    ->limit(10)
    ->get(['id', 'first_name', 'last_name', 'email']);

    return response()->json($eligibleDoctors);
}

/**
 * Remove a doctor from an accepted shared case (inactivate the shared case)
 *
 * @param SharedCase $sharedCase
 * @return \Illuminate\Http\JsonResponse
 */
public function removeSharedCase(SharedCase $sharedCase)
{
    // Ensure the shared case is accepted and the authenticated doctor is the sharing doctor (primary doctor)
    if ($sharedCase->sharing_doctor_id !== Auth::user()->doctor->id || $sharedCase->status !== 'ACCEPTED') {
        return response()->json(['success' => false, 'message' => 'Unauthorized action or invalid shared case status.'], 403);
    }

    // Inactivate the shared case by setting status to 'INACTIVE'
    $sharedCase->status = 'REVOKED';
    $sharedCase->save();

    event(new AuditableEvent(auth()->id(), 'shared_case_removed', [
        'shared_case_id' => $sharedCase->id,
        'patient_id' => $sharedCase->patient_id,
        'receiving_doctor_id' => $sharedCase->receiving_doctor_id,
    ]));

    return response()->json(['success' => true, 'message' => 'Doctor removed from shared case successfully.']);
}

/**
 * Remove a rejected shared case from the list
 *
 * @param SharedCase $sharedCase
 * @return \Illuminate\Http\JsonResponse
 */
public function removeDeclinedSharedCase(SharedCase $sharedCase)
{
    // Ensure the shared case is rejected and the authenticated doctor is the sharing doctor (primary doctor)
    if ($sharedCase->sharing_doctor_id !== Auth::user()->doctor->id && ($sharedCase->status !== 'DECLINED' || $sharedCase->status !== 'CANCELLED')) {
        return response()->json(['success' => false, 'message' => 'Unauthorized action or invalid shared case status.'], 403);
    }

    // Delete the rejected shared case
    $sharedCase->delete();

    event(new AuditableEvent(auth()->id(), 'rejected_shared_case_removed', [
        'shared_case_id' => $sharedCase->id,
        'patient_id' => $sharedCase->patient_id,
        'receiving_doctor_id' => $sharedCase->receiving_doctor_id,
    ]));

    return response()->json(['success' => true, 'message' => 'Rejected invitation removed successfully.']);
}

/**
 * Helper function to calculate patient's age based on birthdate.
 *
 * @param string $birthdate
 * @return int|null
 */
private function getPatientAge($birthdate)
{
    if (empty($birthdate)) {
        return null;
    }
    return \Carbon\Carbon::parse($birthdate)->age;
}

    // createSoapNote
    public function createSoapNote()
    {
        // $selectedPatient = Patient::find(request('patient_id'));
        $doctor = Auth::user()->doctor;
        $patients = Auth::user()->doctor->attendingPhysicians;
        return view('doctor.components.soap', compact( 'doctor', 'patients'));
    }

}