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
use Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Events\AuditableEvent; // Import the event

class AdminController extends Controller
{
    public function listUsers()
    {
        $users = User::all();
        return view('admin.admin-user-management', compact('users'));
    }

    public function createUser(){
        return view('admin.create-user');
    }

    public function storeUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:ADMIN,DOCTOR,PATIENT',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => $request->has('is_active'),
        ]);

        event(new AuditableEvent(auth()->id(), 'user_created', [
            'user_id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
        ]));


        if ($user->role === 'PATIENT' || $user->role === 'DOCTOR') {
            return redirect()->route('admin.users.edit', ['id' => $user->id])->with('success', 'User created. Please fill in '.strtolower($user->role).' details.');
        }

        return redirect()->back()->with('success', 'User account created successfully.');
    }



    public function editUser(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $patient = null;
        $doctor = null;
        
        if ($user->role === 'PATIENT') {
            $patient = Patient::where('user_id', $user->id)->first();
        } elseif ($user->role === 'DOCTOR') {
            $doctor = Doctor::where('user_id', $user->id)->first();
        }

        // Log the variables for debugging
        Log::info('EditUser: User ID - ' . $user->id . ', Role - ' . $user->role);
        Log::info('EditUser: Patient data - ' . ($patient ? 'Exists' : 'Does Not Exist'));
        Log::info('EditUser: Doctor data - ' . ($doctor ? 'Exists' : 'Does Not Exist'));

        // Return the view with user, patient, and doctor data
        return view('admin.edit-user', compact('user', 'patient', 'doctor'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string|in:ADMIN,DOCTOR,PATIENT',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->email = $request->email ?? $user->email;
        $user->role = $request->role ?? $user->role;
        $user->is_active = $request->has('is_active') ? $request->is_active : $user->is_active;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        event(new AuditableEvent(auth()->id(), 'user_updated', [
            'user_id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
        ]));

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->back()->with(['message' => 'User not found']);
        }

        DB::transaction(function () use ($user) {
            $user_id = $user->id;
            $email = $user->email;
            $user->delete();

            event(new AuditableEvent(auth()->id(), 'user_deleted', [
                'user_id' => $user_id,
                'email' => $email,
            ]));
        });

        return  redirect()->back()->with(['message' => 'User deleted successfully']);
    }

    public function storePatientDetails(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);

        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'middleName' => 'nullable|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'sex' => 'required|string|in:Male,Female,Other',
            'civilStatus' => 'required|string|in:Single,Married,Widowed,Separated,Divorced',
            'dateOfBirth' => 'required|date',
            'mobileNumber' => 'required|string|max:255',
            'bloodType' => 'nullable|string|max:255',
            'philhealthNo' => 'nullable|string|max:255',
            'knownMedicalCondition' => 'nullable|string',
            'allergies' => 'nullable|string',
            'previousSurgeries' => 'nullable|string',
            'familyHistory' => 'nullable|string',
            'medication' => 'nullable|string',
            'supplements' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Patient::create([
            'user_id' => $user->id,
            'first_name' => $request->input('firstName'),
            'last_name' => $request->input('lastName'),
            'middle_name' => $request->input('middleName'),
            'suffix' => $request->input('suffix'),
            'address' => $request->input('address'),
            'sex' => $request->input('sex'),
            'civil_status' => $request->input('civilStatus'),
            'date_of_birth' => $request->input('dateOfBirth'),
            'primary_mobile' => $request->input('mobileNumber'),
            'blood_type' => $request->input('bloodType'),
            'philhealth_no' => $request->input('philhealthNo'),
            'medical_conditions' => $request->input('knownMedicalCondition'),
            'allergies' => $request->input('allergies'),
            'surgeries' => $request->input('previousSurgeries'),
            'family_history' => $request->input('familyHistory'),
            'medications' => $request->input('medication'),
            'supplements' => $request->input('supplements'),
        ]);

        event(new AuditableEvent(auth()->id(), 'patient_details_added', [
            'patient_user_id' => $user->id,
            'email' => $user->email,
        ]));

        return redirect()->route('admin.users')->with('success', 'Patient details added successfully.');
    }

    public function updatePatientDetails(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);
        $patient = Patient::where('user_id', $user->id)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'middleName' => 'nullable|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'sex' => 'required|string|in:Male,Female,Other',
            'civilStatus' => 'required|string|in:Single,Married,Widowed,Separated,Divorced',
            'dateOfBirth' => 'required|date',
            'mobileNumber' => 'required|string|max:255',
            'bloodType' => 'nullable|string|max:255',
            'philhealthNo' => 'nullable|string|max:255',
            'knownMedicalCondition' => 'nullable|string',
            'allergies' => 'nullable|string',
            'previousSurgeries' => 'nullable|string',
            'familyHistory' => 'nullable|string',
            'medication' => 'nullable|string',
            'supplements' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $patient->update([
            'first_name' => $request->input('firstName'),
            'last_name' => $request->input('lastName'),
            'middle_name' => $request->input('middleName'),
            'suffix' => $request->input('suffix'),
            'address' => $request->input('address'),
            'sex' => $request->input('sex'),
            'civil_status' => $request->input('civilStatus'),
            'date_of_birth' => $request->input('dateOfBirth'),
            'primary_mobile' => $request->input('mobileNumber'),
            'blood_type' => $request->input('bloodType'),
            'philhealth_no' => $request->input('philhealthNo'),
            'medical_conditions' => $request->input('knownMedicalCondition'),
            'allergies' => $request->input('allergies'),
            'surgeries' => $request->input('previousSurgeries'),
            'family_history' => $request->input('familyHistory'),
            'medications' => $request->input('medication'),
            'supplements' => $request->input('supplements'),
        ]);

        event(new AuditableEvent(auth()->id(), 'patient_details_updated', [
            'patient_user_id' => $user->id,
            'email' => $user->email,
        ]));

        return redirect()->back()->with('success', 'Patient details updated successfully.');
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

    public function listSubscriptions(Request $request)
    {
        $query = Subscription::with('patient.user', 'plan');

        if ($request->has('patient_name')) {
            $patientName = $request->input('patient_name');
            $query->whereHas('patient', function ($q) use ($patientName) {
                $q->where('first_name', 'ILIKE', '%' . $patientName . '%')
                  ->orWhere('last_name', 'ILIKE', '%' . $patientName . '%');
            });
        }

        if ($request->has('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        $subscriptions = $query->orderBy('created_at', 'desc')->paginate(10);
        $statuses = ['ACTIVE', 'INACTIVE', 'EXPIRED']; // Example statuses

        return view('admin.subscriptions.index', compact('subscriptions', 'statuses'));
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

    public function storeDoctorDetails(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }
 

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

        return redirect()->route('admin.users')->with('success', 'Doctor details added successfully.');
    }

    public function updateDoctorDetails(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);
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

    public function viewDoctorPerformanceMetrics($id)
    {
        $doctor = Doctor::find($id);
        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }

        $totalAppointments = Appointment::where('doctor_id', $id)->count();
        $completedAppointments = Appointment::where('doctor_id', $id)->where('status', 'completed')->count();
        $totalEarnings = Payment::where('payable_type', 'App\\Doctor')->where('payable_id', $id)->where('status', 'completed')->sum('amount');

        return response()->json([
            'doctor' => $doctor,
            'metrics' => [
                'total_appointments' => $totalAppointments,
                'completed_appointments' => $completedAppointments,
                'total_earnings' => $totalEarnings,
            ]
        ]);
    }
    public function listPayments(Request $request)
    {
        $query = Payment::with('user');

        if ($request->has('patient_name')) {

            $patientName = $request->input('patient_name');
            $query->whereHasMorph('payable', [Patient::class], function ($q) use ($patientName) {
                $q->where('first_name', 'ILIKE', '%' . $patientName . '%')
                    ->orWhere('last_name', 'ILIKE', '%' . $patientName . '%');
            });
   
        }

        if ($request->has('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(10);
        $statuses = ['PENDING', 'PAID', 'FAILED', 'REFUNDED']; // Example statuses

        return view('admin.payments.index', compact('payments', 'statuses'));
    }

    public function updatePaymentStatus(Request $request, Payment $payment)
    {
        $validatedData = $request->validate([
            'status' => 'required|string|in:PENDING,PAID,FAILED,REFUNDED',
        ]);
 

        DB::transaction(function () use ($payment, $validatedData) {
            $payment->status = $validatedData['status'];
            $payment->save();

            // If payment is marked as PAID and it's for a plan, create/update subscription
            if ($payment->status === 'PAID' && $payment->payable_type === 'MEMBERSHIP') {
                $plan = $payment->payable; // This will be the Plan model instance
                $patient = Patient::where('user_id', $payment->user_id)->first(); // Assuming patient has user_id

                if ($patient && $plan) {
                    // Deactivate any existing active subscriptions for this patient
                    $exists = Subscription::where('patient_id', $patient->id)->where('status', 'ACTIVE')->first();

                    if ($exists) {
                        DB::rollback();
                        return response()->json(['message' => 'Patient already has an active subscription.'], 400);
                    }

                    // Create or update the new subscription
                    Subscription::updateOrCreate(
                        ['patient_id' => $patient->id],
                        [
                            'plan_id' => $plan->id,
                            'start_date' => now(),
                            'end_date' => now()->addMonth(), // Example: 1-month subscription
                            'status' => 'ACTIVE',
                        ]
                    );
                }
            }
        }); // End of DB::transaction

        return redirect()->back()->with('success', 'Payment status updated successfully.');
    }
    public function listPlans()
    {
        $plans = \App\Plan::all();
        return view('admin.plans.index', compact('plans'));
    }

    public function createPlan()
    {
        return view('admin.plans.create');
    }

    public function storePlan(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:plans,name',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);

        \App\Plan::create($validatedData);

        return redirect()->route('admin.plans')->with('success', 'Plan created successfully!');
    }

    public function editPlan(\App\Plan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    public function updatePlan(Request $request, \App\Plan $plan)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:plans,name,' . $plan->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);

        $plan->update($validatedData);

        return redirect()->route('admin.plans')->with('success', 'Plan updated successfully!');
    }

    public function deletePlan(\App\Plan $plan)
    {
        $plan->delete();
        return redirect()->route('admin.plans')->with('success', 'Plan deleted successfully!');
    }
}