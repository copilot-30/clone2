<?php

namespace App\Http\Controllers;

use App\Prescription;
use App\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Events\AuditableEvent;

class PrescriptionController extends Controller
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
        $validator = Validator::make($request->all(), [
            'consultation_id' => 'required|exists:consultations,id',
            'medication_name' => 'required|string|max:255',
            'dosage' => 'required|string|max:255',
            'frequency' => 'required|string|max:255',
            'instructions' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $prescription = Prescription::create($request->all());

        event(new AuditableEvent(auth()->id(), 'prescription_created', [
            'prescription_id' => $prescription->id,
            'consultation_id' => $prescription->consultation_id,
            'medication_name' => $prescription->medication_name,
        ]));

        return response()->json(['message' => 'Prescription created successfully', 'prescription' => $prescription], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $prescription = Prescription::find($id);
        if (!$prescription) {
            return response()->json(['message' => 'Prescription not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'medication_name' => 'sometimes|required|string|max:255',
            'dosage' => 'sometimes|required|string|max:255',
            'frequency' => 'sometimes|required|string|max:255',
            'instructions' => 'sometimes|nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $prescription->update($request->all());

        event(new AuditableEvent(auth()->id(), 'prescription_updated', [
            'prescription_id' => $prescription->id,
            'medication_name' => $prescription->medication_name,
        ]));

        return response()->json(['message' => 'Prescription updated successfully', 'prescription' => $prescription]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $prescription = Prescription::find($id);
        if (!$prescription) {
            return response()->json(['message' => 'Prescription not found'], 404);
        }

        $prescription->delete();

        event(new AuditableEvent(auth()->id(), 'prescription_deleted', [
            'prescription_id' => $prescription->id,
        ]));

        return response()->json(['message' => 'Prescription deleted successfully']);
    }
}
