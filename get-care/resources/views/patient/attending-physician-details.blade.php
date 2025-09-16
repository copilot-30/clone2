@extends('patient_layout')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    <div class="bg-white rounded-lg shadow-md p-6   mx-auto">
        <h2 class="text-2xl font-bold mb-6 text-center">Your Attending Physician</h2>

        @if($attendingPhysician && $attendingPhysician->doctor)
            <div class="max-w-4xl mx-auto">
                <!-- Doctor Header -->
                <div class="flex flex-col md:flex-row items-center md:items-start mb-8 p-6 bg-gray-50 rounded-lg">
                    <div class="w-24 h-24 flex items-center justify-center bg-blue-500 text-white rounded-full text-3xl font-bold mb-4 md:mb-0 md:mr-6">
                        {{ substr($attendingPhysician->doctor->first_name, 0, 1) }}{{ substr($attendingPhysician->doctor->last_name, 0, 1) }}
                    </div>
                    <div class="text-center md:text-left flex-1">
                        <h1 class="text-2xl font-bold text-gray-800">Dr. {{ $attendingPhysician->doctor->first_name }} {{ $attendingPhysician->doctor->last_name }}</h1>
                        <p class="text-lg text-blue-600 font-medium">{{ $attendingPhysician->doctor->specialization ?? 'General Practitioner' }}</p>
                        <div class="mt-2 flex flex-wrap justify-center md:justify-start gap-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span class="text-gray-600">{{ $attendingPhysician->doctor->years_of_experience ?? 'N/A' }} years experience</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span class="text-gray-600">{{ $attendingPhysician->doctor->clinics->count() }} Clinic/s</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <a href="{{ route('patient.select-appointment-type', ['doctor_id' => $attendingPhysician->doctor->id]) }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 focus:outline-none transition duration-300">
                            <i class="fas fa-calendar"></i> Create New Appointment
                        </a>
                    </div>
                </div>

                <!-- Doctor Bio -->
                @if($attendingPhysician->doctor->certifications || $attendingPhysician->doctor->training || $attendingPhysician->doctor->affiliated_hospital)
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Professional Background</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($attendingPhysician->doctor->certifications)
                        <div>
                            <h3 class="font-semibold text-gray-700 mb-2">Certifications</h3>
                            <p class="text-gray-600">{{ $attendingPhysician->doctor->certifications }}</p>
                        </div>
                        @endif
                        @if($attendingPhysician->doctor->training)
                        <div>
                            <h3 class="font-semibold text-gray-700 mb-2">Training</h3>
                            <p class="text-gray-600">{{ $attendingPhysician->doctor->training }}</p>
                        </div>
                        @endif
                        @if($attendingPhysician->doctor->affiliated_hospital)
                        <div>
                            <h3 class="font-semibold text-gray-700 mb-2">Affiliated Hospital</h3>
                            <p class="text-gray-600">{{ $attendingPhysician->doctor->affiliated_hospital }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Clinics -->
                @if($attendingPhysician->doctor->doctorClinics->count() > 0)
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Clinics</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($attendingPhysician->doctor->doctorClinics as $doctorClinic)
                            @if($doctorClinic->clinic)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-300">
                                <h3 class="font-bold text-lg text-gray-800 mb-2">{{ $doctorClinic->clinic->name }}</h3>
                                <p class="text-gray-600 text-sm mb-2 flex items-start">
                                    <svg class="w-4 h-4 mr-2 text-gray-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ $doctorClinic->clinic->address }}, {{ $doctorClinic->clinic->city }}, {{ $doctorClinic->clinic->state }} {{ $doctorClinic->clinic->postal_code }}
                                </p>
                                @if($doctorClinic->clinic->phone)
                                <p class="text-gray-600 text-sm mb-1 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    {{ $doctorClinic->clinic->phone }}
                                </p>
                                @endif
                                @if($doctorClinic->clinic->email)
                                <p class="text-gray-600 text-sm flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    {{ $doctorClinic->clinic->email }}
                                </p>
                                @endif
                                
                                <!-- Clinic Facilities -->
                                @if(isset($doctorClinic->clinic->facilities) && is_array($doctorClinic->clinic->facilities) && count($doctorClinic->clinic->facilities) > 0)
                                <div class="mt-3">
                                    <h4 class="text-xs font-semibold text-gray-700 mb-1">Facilities:</h4>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($doctorClinic->clinic->facilities as $facility)
                                            <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">{{ ucwords(str_replace('-', ' ', strtolower($facility))) }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                
                                <!-- Operating Hours -->
                                @if(isset($doctorClinic->clinic->operating_hours) && is_array($doctorClinic->clinic->operating_hours) && count($doctorClinic->clinic->operating_hours) > 0)
                                <div class="mt-3">
                                    <h4 class="text-xs font-semibold text-gray-700 mb-1">Operating Hours:</h4>
                                    <div class="text-xs text-gray-600">
                                        @foreach($doctorClinic->clinic->operating_hours as $day => $hours)
                                            @if(isset($hours['start']) && isset($hours['end']))
                                                <div class="flex justify-between">
                                                    <span class="font-medium">{{ $day }}:</span>
                                                    <span>{{ \Carbon\Carbon::parse($hours['start'])->format('g:i A') }} - {{ \Carbon\Carbon::parse($hours['end'])->format('g:i A') }}</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Availability -->
                @if($attendingPhysician->doctor->doctorAvailability->count() > 0)
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Availability</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="py-2 px-4 border-b text-left">Day</th>
                                    <th class="py-2 px-4 border-b text-left">Time</th>
                                    <th class="py-2 px-4 border-b text-left">Clinic</th>
                                    <th class="py-2 px-4 border-b text-left">Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendingPhysician->doctor->doctorAvailability as $availability)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2 px-4 border-b">
                                        @php
                                            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                            $dayName = $availability->day_of_week >= 1 && $availability->day_of_week <= 7 ? $days[$availability->day_of_week - 1] : 'Unknown';
                                        @endphp
                                        {{ $dayName }}
                                    </td>
                                    <td class="py-2 px-4 border-b">
                                        {{ \Carbon\Carbon::parse($availability->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($availability->end_time)->format('g:i A') }}
                                    </td>
                                    <td class="py-2 px-4 border-b">
                                        {{ $availability->clinic->name ?? 'N/A' }}
                                    </td>
                                    <td class="py-2 px-4 border-b">
                                        @if(isset($availability->availability_type) && is_array($availability->availability_type))
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($availability->availability_type as $type)
                                                    <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded">{{ ucfirst($type) }}</span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-gray-500">Not specified</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
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