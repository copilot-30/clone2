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
use App\Events\AuditableEvent;
use Auth;
use Illuminate\Support\Str;
class DoctorController extends Controller
{
    public function dashboard()
    {
        return view('doctor.dashboard'); // Assuming you'll create this view
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
            'availability.*.availability_type.*' => 'string|in:appointment,follow-up', // Validate array elements
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

        return redirect()->route('doctor.appointments.list')->with('success', 'Appointment cancelled successfully.');
    }
}