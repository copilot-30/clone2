<?php

namespace App\Http\Controllers;

use App\User;
use App\Doctor;
use App\Patient;
use App\Consultation;
use App\Payment;
use App\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Events\AuditableEvent; // Import the event

class AdminController extends Controller
{
    public function createDoctor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'specialization' => 'required|string|max:255', // Renamed from specialty_id for clarity with Doctor model
            'years_of_experience' => 'nullable|integer',
            'certifications' => 'nullable|string',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'sex' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'prc_license_number' => 'required|string|unique:doctor_profiles',
            'ptr_license_number' => 'required|string|unique:doctor_profiles',
            'affiliated_hospital' => 'nullable|string',
            'training' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        return DB::transaction(function () use ($request) {
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'DOCTOR', // Assign 'DOCTOR' role as per base.sql
                'is_active' => true,
            ]);

            $doctor = Doctor::create([
                'user_id' => $user->id,
                'specialization' => $request->specialization,
                'years_of_experience' => $request->years_of_experience,
                'certifications' => $request->certifications,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'sex' => $request->sex,
                'phone_number' => $request->phone_number,
                'email' => $request->email, // Doctor's profile email should match user email
                'prc_license_number' => $request->prc_license_number,
                'ptr_license_number' => $request->ptr_license_number,
                'affiliated_hospital' => $request->affiliated_hospital,
                'training' => $request->training,
            ]);

            // Dispatch audit event for doctor creation
            event(new AuditableEvent(auth()->id(), 'doctor_created', [
                'doctor_id' => $doctor->id,
                'user_id' => $user->id,
                'email' => $user->email,
                'specialization' => $doctor->specialization,
            ]));

            return redirect()->back()->with('success', 'Doctor account created successfully');
        });
    }
    public function listDoctors()
    {
        return view('admin.admin-doctor-management');
    }

    public function editDoctor(Request $request, $id)
    {
        $doctor = Doctor::find($id);
        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'specialization' => 'sometimes|required|string|max:255',
            'years_of_experience' => 'nullable|integer',
            'certifications' => 'nullable|string',
            'first_name' => 'sometimes|required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'sex' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'prc_license_number' => 'sometimes|required|string|unique:doctor_profiles,prc_license_number,' . $id,
            'ptr_license_number' => 'sometimes|required|string|unique:doctor_profiles,ptr_license_number,' . $id,
            'affiliated_hospital' => 'nullable|string',
            'training' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $doctor->update($request->all());

        event(new AuditableEvent(auth()->id(), 'doctor_updated', [
            'doctor_id' => $doctor->id,
            'user_id' => $doctor->user_id,
            'email' => $doctor->email,
        ]));

        return response()->json(['message' => 'Doctor updated successfully', 'doctor' => $doctor]);
    }

    public function deleteDoctor($id)
    {
        $doctor = Doctor::find($id);
        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }

        DB::transaction(function () use ($doctor) {
            $user_id = $doctor->user_id;
            $email = $doctor->email;
            $doctor->delete();
            User::where('id', $user_id)->delete();

            event(new AuditableEvent(auth()->id(), 'doctor_deleted', [
                'user_id' => $user_id,
                'email' => $email,
            ]));
        });

        return response()->json(['message' => 'Doctor deleted successfully']);
    }
    public function listPatients()
    {
        return view('admin.admin-patient-management');
    }

    public function viewPatientDetails($id)
    {
        $patient = Patient::with('user', 'medicalBackgrounds', 'patientVisits', 'appointments.consultation')
                          ->find($id);

        if (!$patient) {
            return response()->json(['message' => 'Patient not found'], 404);
        }

        return response()->json(['patient' => $patient]);
    }

    public function listAllAppointments()
    {
        return view('admin.admin-appointment-oversight');
    }

    public function filterAppointments(Request $request)
    {
        $query = \App\Appointment::with('patient.user', 'doctor.user');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        if ($request->has('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }
        if ($request->has('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = \Carbon\Carbon::parse($request->start_date)->startOfDay();
            $endDate = \Carbon\Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('appointment_datetime', [$startDate, $endDate]);
        }

        $appointments = $query->orderBy('appointment_datetime', 'desc')->get();

        return response()->json(['appointments' => $appointments]);
    }

    public function cancelAppointment($id)
    {
        $appointment = \App\Appointment::find($id);
        if (!$appointment) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }

        $appointment->status = 'cancelled';
        $appointment->save();

        event(new AuditableEvent(auth()->id(), 'appointment_cancelled', [
            'appointment_id' => $appointment->id,
            'patient_id' => $appointment->patient_id,
            'doctor_id' => $appointment->doctor_id,
        ]));

        return response()->json(['message' => 'Appointment cancelled successfully', 'appointment' => $appointment]);
    }

    public function rescheduleAppointment(Request $request, $id)
    {
        $appointment = \App\Appointment::find($id);
        if (!$appointment) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'appointment_datetime' => 'required|date|after:now',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $oldDateTime = $appointment->appointment_datetime;
        $appointment->appointment_datetime = $request->appointment_datetime;
        $appointment->status = 'rescheduled';
        $appointment->save();

        event(new AuditableEvent(auth()->id(), 'appointment_rescheduled', [
            'appointment_id' => $appointment->id,
            'old_datetime' => $oldDateTime,
            'new_datetime' => $appointment->appointment_datetime,
        ]));

        return response()->json(['message' => 'Appointment rescheduled successfully', 'appointment' => $appointment]);
    }

    public function reassignAppointment(Request $request, $id)
    {
        $appointment = \App\Appointment::find($id);
        if (!$appointment) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'new_doctor_id' => 'required|exists:doctors,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $oldDoctorId = $appointment->doctor_id;
        $appointment->doctor_id = $request->new_doctor_id;
        $appointment->save();

        event(new AuditableEvent(auth()->id(), 'appointment_reassigned', [
            'appointment_id' => $appointment->id,
            'old_doctor_id' => $oldDoctorId,
            'new_doctor_id' => $appointment->doctor_id,
        ]));

        return response()->json(['message' => 'Appointment reassigned successfully', 'appointment' => $appointment]);
    }

    public function viewDoctorPerformanceMetrics($id)
    {
        $doctor = \App\Doctor::find($id);
        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }

        $totalAppointments = \App\Appointment::where('doctor_id', $id)->count();
        $completedAppointments = \App\Appointment::where('doctor_id', $id)->where('status', 'completed')->count();
        $totalEarnings = \App\Payment::where('payable_type', 'App\\Doctor')->where('payable_id', $id)->where('status', 'completed')->sum('amount');

        return response()->json([
            'doctor' => $doctor,
            'metrics' => [
                'total_appointments' => $totalAppointments,
                'completed_appointments' => $completedAppointments,
                'total_earnings' => $totalEarnings,
            ]
        ]);
    }

    public function viewConsultationHistory($id)
    {
        $patient = \App\Patient::find($id);
        if (!$patient) {
            return response()->json(['message' => 'Patient not found'], 404);
        }

        $consultations = \App\Consultation::with('appointment.doctor.user', 'appointment.patient.user', 'prescriptions', 'labRequests')
                                    ->whereHas('appointment', function ($query) use ($id) {
                                        $query->where('patient_id', $id);
                                    })
                                    ->orderBy('consultation_datetime', 'desc')
                                    ->get();

        return response()->json(['consultations' => $consultations]);
    }

    public function listSubscriptions()
    {
        return view('admin.admin-subscriptions'); // Assuming you'll create this
    }

    public function monitorTransactions()
    {
        return view('admin.admin-transactions'); // Assuming you'll create this
    }

    public function viewAuditLogs()
    {
        return view('admin.admin-audit-log-viewer');
    }

    public function dashboard()
    {
        return view('admin.admin-dashboard');
    }
}