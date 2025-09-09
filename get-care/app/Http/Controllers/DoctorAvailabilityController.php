<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\DoctorAvailability; // Assuming you have this model

class DoctorAvailabilityController extends Controller
{
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

    public function show(Request $request)
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
                'is_available' => $slot->is_available,
                'id' => $slot->id,
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


        return view('doctor.availability', compact('organizedAvailability', 'availability_status'));
    }

    public function update(Request $request)
    {
        $doctor_id = Auth::user()->doctor->id;

        // Validate the submitted data
        $validator = Validator::make($request->all(), [
            'status' => 'required|boolean', // Overall enabled/disabled status
            'availability' => 'array',
            'availability.*.day_of_week' => 'required|string', // day_of_week is now part of each slot
            'availability.*.start_time' => 'required|date_format:H:i',
            'availability.*.end_time' => 'required|date_format:H:i|after:availability.*.start_time',
            'availability.*.is_available' => 'boolean',
            'availability.*.id' => 'nullable|string', // For existing records
        ]);
        // Custom rule to validate day_of_week values
        $validator->sometimes('availability.*.day_of_week', ['in:' . implode(',', array_keys(self::$dayMapping))], function ($input) {
            return is_array($input['availability']) && count($input['availability']) > 0;
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::transaction(function () use ($request, $doctor_id) {
            // Update overall availability status on Doctor model
            $doctor = Auth::user()->doctor;
            // $doctor->online_availability_enabled = $request->input('status');
            // $doctor->save();

            // First, delete existing availability for the doctor to handle removals
            DoctorAvailability::where('doctor_id', $doctor_id)->delete();

            // Then, re-create or update based on the submitted data
            foreach ($request->input('availability', []) as $slotData) {
                DoctorAvailability::create([
                    'doctor_id' => $doctor_id,
                    'day_of_week' => self::$dayMapping[$slotData['day_of_week']], // Convert string day to integer
                    'start_time' => $slotData['start_time'],
                    'end_time' => $slotData['end_time'],
                    'is_available' => $slotData['is_available'],
                ]);
            }
        });

        return redirect()->back()->with('success', 'Availability updated successfully!');
    }
}