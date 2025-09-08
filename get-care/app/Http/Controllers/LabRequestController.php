<?php

namespace App\Http\Controllers;

use App\LabRequest;
use App\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Events\AuditableEvent;

class LabRequestController extends Controller
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
            'test_name' => 'required|string|max:255',
            'instructions' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $labRequest = LabRequest::create($request->all());

        event(new AuditableEvent(auth()->id(), 'lab_request_created', [
            'lab_request_id' => $labRequest->id,
            'consultation_id' => $labRequest->consultation_id,
            'test_name' => $labRequest->test_name,
        ]));

        return response()->json(['message' => 'Lab request created successfully', 'lab_request' => $labRequest], 201);
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
        $labRequest = LabRequest::find($id);
        if (!$labRequest) {
            return response()->json(['message' => 'Lab Request not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'test_name' => 'sometimes|required|string|max:255',
            'instructions' => 'sometimes|nullable|string',
            'status' => 'sometimes|required|in:pending,completed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $labRequest->update($request->all());

        event(new AuditableEvent(auth()->id(), 'lab_request_updated', [
            'lab_request_id' => $labRequest->id,
            'status' => $labRequest->status,
        ]));

        return response()->json(['message' => 'Lab Request updated successfully', 'lab_request' => $labRequest]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $labRequest = LabRequest::find($id);
        if (!$labRequest) {
            return response()->json(['message' => 'Lab Request not found'], 404);
        }

        $labRequest->delete();

        event(new AuditableEvent(auth()->id(), 'lab_request_deleted', [
            'lab_request_id' => $labRequest->id,
        ]));

        return response()->json(['message' => 'Lab Request deleted successfully']);
    }
}
