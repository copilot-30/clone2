<?php

namespace App\Http\Controllers;

use App\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Events\AuditableEvent;

class DoctorProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $doctor = Doctor::find($id);

        if (!$doctor) {
            return response()->json(['message' => 'Doctor profile not found'], 404);
        }

        return response()->json(['doctor' => $doctor]);
    }

    public function update(Request $request, $id)
    {
        $doctor = Doctor::find($id);

        if (!$doctor) {
            return response()->json(['message' => 'Doctor profile not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'specialization' => 'sometimes|string|max:255',
            'medical_license_number' => 'sometimes|string|unique:doctors,medical_license_number,' . $id,
            'contact_number' => 'sometimes|string|max:255',
            'address' => 'sometimes|string|max:255',
            'bio' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $doctor->update($request->all());

        event(new AuditableEvent(auth()->id(), 'doctor_profile_updated', [
            'doctor_id' => $doctor->id,
            'updated_fields' => $request->all(),
        ]));

        return response()->json(['message' => 'Doctor profile updated successfully', 'doctor' => $doctor]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
