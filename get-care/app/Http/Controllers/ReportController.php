<?php

namespace App\Http\Controllers;

use App\Doctor;
use App\Patient;
use App\Appointment;
use App\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
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
        //
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
    public function doctorPerformanceMetrics(Request $request)
    {
        $doctors = Doctor::with('user')->get();
        $metrics = [];

        foreach ($doctors as $doctor) {
            $totalAppointments = Appointment::where('doctor_id', $doctor->id)->count();
            $completedAppointments = Appointment::where('doctor_id', $doctor->id)->where('status', 'completed')->count();
            $metrics[] = [
                'doctor_id' => $doctor->id,
                'doctor_name' => $doctor->user->name,
                'total_appointments' => $totalAppointments,
                'completed_appointments' => $completedAppointments,
            ];
        }

        return response()->json(['doctor_performance_metrics' => $metrics]);
    }

    public function consultationHistory(Request $request)
    {
        $consultations = Consultation::with('appointment.patient.user', 'appointment.doctor.user')
            ->orderBy('consultation_datetime', 'desc')
            ->get();

        return response()->json(['consultation_history' => $consultations]);
    }
}
