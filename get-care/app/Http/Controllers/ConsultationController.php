<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Appointment;
use App\Consultation;
use App\Patient;
use App\PatientVisit; // Import PatientVisit model
use Google\Client;
use Google\Service\Calendar;
use Illuminate\Support\Facades\Log;

class ConsultationController extends Controller
{
    /**
     * Initialize Google Client.
     *
     * @return \Google\Client
     */
    private function getGoogleClient()
    {
        $client = new Client();
        $client->setApplicationName('GetCare Consultation Service');
        $client->setAuthConfig(config_path('google_service_account.json')); // Path to your service account key file
        $client->setScopes([
            'https://www.googleapis.com/auth/calendar',
            'https://www.googleapis.com/auth/calendar.events',
        ]);
        return $client;
    }

    /**
     * Start a consultation and generate Google Meet link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function start(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|uuid|exists:appointments,id',
        ]);

        $appointment = Appointment::find($request->appointment_id);

        if (!$appointment) {
            return response()->json(['message' => 'Appointment not found.'], 404);
        }

        if ($appointment->type !== 'online') {
            return response()->json(['message' => 'Google Meet link generation is only for online appointments.'], 400);
        }

        try {
            $client = $this->getGoogleClient();
            $service = new Calendar($client);

            $event = new Calendar\Event([
                'summary' => 'GetCare Consultation with Dr. ' . $appointment->doctor->user->name . ' and ' . $appointment->patient->user->name,
                'location' => 'Google Meet',
                'description' => 'Online medical consultation via GetCare platform.',
                'start' => [
                    'dateTime' => $appointment->appointment_datetime->format('Y-m-d\TH:i:s'),
                    'timeZone' => 'Asia/Manila', // Assuming Philippine timezone
                ],
                'end' => [
                    'dateTime' => $appointment->appointment_datetime->addMinutes(30)->format('Y-m-d\TH:i:s'), // Assuming 30 min consultations
                    'timeZone' => 'Asia/Manila',
                ],
                'attendees' => [
                    ['email' => $appointment->doctor->user->email],
                    ['email' => $appointment->patient->user->email],
                ],
                'conferenceData' => [
                    'createRequest' => [
                        'requestId' => uniqid(),
                        'conferenceSolutionKey' => [
                            'type' => 'hangoutsMeet',
                        ],
                    ],
                ],
            ]);

            $calendarId = 'primary'; // Use 'primary' for the service account's primary calendar
            $event = $service->events->insert($calendarId, $event, ['conferenceDataVersion' => 1]);

            $googleMeetLink = $event->hangoutLink;

            // Store the Google Meet link in the appointment
            $appointment->meet_link = $googleMeetLink; // Renamed to meet_link as per base.sql
            $appointment->save();
            
            return redirect($googleMeetLink); // Redirect directly to the Google Meet link
 
        } catch (\Exception $e) {
            Log::error('Google Meet API Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to generate Google Meet link: ' . $e->getMessage());
        }
    }

    /**
     * Update consultation notes.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Consultation  $consultation
     * @return \Illuminate\Http\Response
     */
    public function updateNotes(Request $request, Consultation $consultation)
    {
        $request->validate([
            'consultation_notes' => 'sometimes|string',
            'ai_recommendations' => 'sometimes|string',
        ]);

        if ($request->has('consultation_notes')) {
            $consultation->consultation_notes = $request->consultation_notes;
        }
        if ($request->has('ai_recommendations')) {
            $consultation->ai_recommendations = $request->ai_recommendations;
        }
        $consultation->save();

        return response()->json(['message' => 'Consultation notes updated successfully', 'consultation' => $consultation]);
    }

    /**
     * Get AI recommendations for a patient's health record.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getAIRecommendations(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|uuid|exists:patients,id',
            'health_record_data' => 'required|json', // This would be the actual data to send to AI
        ]);

        // In a real scenario, this would involve sending health_record_data to an AI service
        // and processing its response. For now, we return a dummy recommendation.

        $dummyRecommendation = "Based on the provided health data, consider checking for potential correlations with chronic fatigue. Recommend additional blood tests for specific markers and a follow-up with a sleep specialist if symptoms persist.";

        return redirect()->back()->with('ai_recommendations', $dummyRecommendation);
    }
    public function viewPatientHealthRecord($patientId)
    {
        $patient = Patient::with(['medicalBackgrounds', 'patientVisits', 'appointments.consultation.prescriptions', 'appointments.consultation.labRequests'])
                          ->find($patientId);

        if (!$patient) {
            return response()->json(['message' => 'Patient not found'], 404);
        }

        return response()->json(['patient_health_record' => $patient]);
    }
}
