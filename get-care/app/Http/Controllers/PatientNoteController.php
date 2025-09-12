<?php

namespace App\Http\Controllers;

use App\PatientNote;
use App\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Events\AuditableEvent;

class PatientNoteController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|uuid|exists:patients,id',
            'subject' => 'nullable|string|max:255',
            'content' => 'required|string',
            'note_type' => 'required|in:general,progress,assessment,plan',
            'visibility' => 'required|in:private,shared',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $doctor = Auth::user()->doctor;

        // Ensure the doctor has access to this patient
        $patient = Patient::where('id', $request->input('patient_id'))
                            ->where(function($query) use ($doctor) {
                                $query->whereHas('appointments', function($q) use ($doctor) {
                                    $q->where('doctor_id', $doctor->id);
                                })->orWhereHas('attendingPhysician', function($q) use ($doctor) {
                                    $q->where('doctor_id', $doctor->id);
                                });
                            })->first();

        if (!$patient) {
            return redirect()->back()->with('error', 'Unauthorized to add note for this patient.');
        }

        PatientNote::create([
            'patient_id' => $request->input('patient_id'),
            'doctor_id' => $doctor->id,
            'subject' => $request->input('subject'),
            'content' => $request->input('content'),
            'note_type' => $request->input('note_type'),
            'visibility' => $request->input('visibility'),
        ]);

        event(new AuditableEvent(auth()->id(), 'patient_note_added', [
            'patient_id' => $request->input('patient_id'),
            'doctor_id' => $doctor->id,
        ]));

        return redirect()->back()->with('success', 'Patient note added successfully!');
    }
}