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
use Auth;
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
 


}