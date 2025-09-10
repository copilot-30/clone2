@extends('admin_layout')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-6 text-center">Your Attending Physician</h2>

        @if($attendingPhysician)
            <div class="doctor-card bg-white border border-gray-200 rounded-lg shadow-sm p-6 mb-8 text-center">
                <div class="flex flex-col items-center justify-center mb-4">
                    <div class="w-20 h-20 flex items-center justify-center bg-blue-500 text-white rounded-full text-3xl font-bold mb-4">
                        {{ substr($attendingPhysician->doctor->first_name, 0, 1) }}{{ substr($attendingPhysician->doctor->last_name, 0, 1) }}
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">Dr. {{ $attendingPhysician->doctor->first_name }} {{ $attendingPhysician->doctor->last_name }}</h3>
                    <p class="text-gray-500 text-md">{{ $attendingPhysician->doctor->specialization ?? 'General Practitioner' }}</p>
                </div>
                <div class="text-gray-600 text-sm mb-4">
                    <p class="flex items-center justify-center mb-1">
                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 16v.01M6 16v.01M12 16v.01M6 10h.01M12 10h.01M16 10h.01M6 14h.01M12 14h.01M16 14h.01"></path></svg>
                        {{ $attendingPhysician->doctor->years_of_experience ?? 'N/A' }} years experience
                    </p>
                    <p class="flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        {{ $attendingPhysician->doctor->doctorClinics->count() }} clinics available
                    </p>
                </div>

                <a href="{{ route('patient.select-appointment-type', ['doctor_id' => $attendingPhysician->doctor->id]) }}" class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 focus:outline-none mt-4">
                    Create New Appointment
                </a>
            </div>
        @else
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
                <p class="font-bold">No Attending Physician Assigned</p>
                <p>You currently do not have an attending physician assigned. Please select one to proceed with appointments.</p>
                <div class="mt-4">
                    <a href="{{ route('patient.select-doctor') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 focus:outline-none">
                        Select an Attending Physician
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection