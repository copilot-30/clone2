<?php

namespace App\Http\Controllers;

use App\DoctorAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Events\AuditableEvent;

class DoctorAvailabilityController extends Controller
{
    public function index(Request $request)
    {
        $doctorAvailabilities = DoctorAvailability::where('doctor_id', $request->user()->doctor->id)->get();
        return response()->json(['doctor_availabilities' => $doctorAvailabilities]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $availability = DoctorAvailability::create([
            'doctor_id' => $request->doctor_id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        event(new AuditableEvent(auth()->id(), 'doctor_availability_created', [
            'availability_id' => $availability->id,
            'doctor_id' => $availability->doctor_id,
            'date' => $availability->date,
        ]));

        return response()->json(['message' => 'Doctor availability created successfully', 'availability' => $availability]);
    }

    public function show($id)
    {
        $availability = DoctorAvailability::find($id);
        if (!$availability) {
            return response()->json(['message' => 'Doctor availability not found'], 404);
        }
        return response()->json(['availability' => $availability]);
    }

    public function update(Request $request, $id)
    {
        $availability = DoctorAvailability::find($id);
        if (!$availability) {
            return response()->json(['message' => 'Doctor availability not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'date' => 'sometimes|required|date',
            'start_time' => 'sometimes|required|date_format:H:i',
            'end_time' => 'sometimes|required|date_format:H:i|after:start_time',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $availability->update($request->all());

        event(new AuditableEvent(auth()->id(), 'doctor_availability_updated', [
            'availability_id' => $availability->id,
            'doctor_id' => $availability->doctor_id,
        ]));

        return response()->json(['message' => 'Doctor availability updated successfully', 'availability' => $availability]);
    }

    public function destroy($id)
    {
        $availability = DoctorAvailability::find($id);
        if (!$availability) {
            return response()->json(['message' => 'Doctor availability not found'], 404);
        }

        $availability->delete();

        event(new AuditableEvent(auth()->id(), 'doctor_availability_deleted', [
            'availability_id' => $availability->id,
            'doctor_id' => $availability->doctor_id,
        ]));

        return response()->json(['message' => 'Doctor availability deleted successfully']);
    }
}