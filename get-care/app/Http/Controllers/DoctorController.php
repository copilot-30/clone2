<?php

namespace App\Http\Controllers;

use App\Doctor;
use App\User;
use App\Patient;
use App\Appointment;
use App\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Events\AuditableEvent;

class DoctorController extends Controller
{
    public function dashboard()
    {
        return view('doctor.dashboard'); // Assuming you'll create this view
    }

    public function createDoctor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'specialization' => 'required|string|max:255',
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
                'role' => 'DOCTOR',
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
                'email' => $request->email,
                'prc_license_number' => $request->prc_license_number,
                'ptr_license_number' => $request->ptr_license_number,
                'affiliated_hospital' => $request->affiliated_hospital,
                'training' => $request->training,
            ]);

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
        $doctors = Doctor::all(); // Fetch all doctors
        return view('admin.admin-doctor-management', compact('doctors'));
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
}