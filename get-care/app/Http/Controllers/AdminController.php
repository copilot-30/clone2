<?php

namespace App\Http\Controllers;

use App\User;
use App\Doctor;
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
}