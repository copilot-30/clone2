@extends('patient_layout')

@section('content')
<div class="min-h-screen bg-gray-100 flex flex-col items-center justify-center py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl w-full bg-white rounded-xl shadow-lg overflow-hidden md:flex">
        <!-- Confirmation Section (Left/Top) -->
        <div class="md:w-1/2 p-8 md:p-12 flex flex-col justify-center items-center text-center bg-gradient-to-br from-blue-500 to-indigo-600 text-white">
            <div class="mb-6">
                <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
            </div>
            <h1 class="text-4xl font-extrabold mb-4 leading-tight">Appointment Confirmed!</h1>
            <p class="text-blue-100 text-lg mb-8">Your booking is complete. We look forward to seeing you.</p>
            <a href="{{ route('patient.dashboard') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-semibold rounded-full shadow-sm text-blue-800 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition ease-in-out duration-150">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c-4.142 0-6.667-2.616-6.667-6S4.858 7 9 7s6.667 2.616 6.667 6-2.525 6-6.667 6zm0 0v-6"></path></svg>
                View My Appointments
            </a>
        </div>

        <!-- Details Section (Right/Bottom) -->
        <div class="md:w-1/2 p-8 md:p-12 bg-white flex flex-col justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-4">Appointment Details</h2>
                <div class="space-y-4 text-gray-700">
                    <p class="flex justify-between items-center"><strong class="font-semibold">Appointment ID:</strong> <span class="text-gray-800 text-right">#{{ substr($appointment->id, 0, 8) }}</span></p>
                    <p class="flex justify-between items-center"><strong class="font-semibold">Doctor:</strong> <span class="text-gray-800 text-right">Dr. {{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}</span></p>
                    <p class="flex justify-between items-center"><strong class="font-semibold">Date & Time:</strong> <span class="text-gray-800 text-right">{{ \Carbon\Carbon::parse($appointment->appointment_datetime)->format('l, F j, Y \a\t h:i A') }}</span></p>
                    <p class="flex justify-between items-center"><strong class="font-semibold">Type:</strong> <span class="text-gray-800 text-right">{{ ucfirst($appointment->type) }} Consultation</span></p>
                    @if($appointment->type === 'clinic' && $appointment->clinic)
                        <p class="flex justify-between items-start"><strong class="font-semibold">Location:</strong> <span class="text-gray-800 text-right">{{ $appointment->clinic->name }} <br>({{ $appointment->clinic->address }})</span></p>
                    @elseif($appointment->type === 'online' && $appointment->meet_link)
                        <p class="flex justify-between items-center"><strong class="font-semibold">Google Meet Link:</strong> <a href="{{ $appointment->meet_link }}" class="text-blue-600 hover:underline break-all ml-4 text-right" target="_blank">{{ $appointment->meet_link }}</a></p>
                    @else 
                       {{ $appointment->type === 'online' }} => {{$appointment->meet_link}}
                        @endif
                </div>

                <div class="bg-blue-50 border-l-4 border-blue-400 text-blue-800 p-4 rounded-md mt-8" role="alert">
                    <div class="flex">
                        <div class="py-1">
                            <svg class="fill-current h-6 w-6 text-blue-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l3 3a1 1 0 001.414-1.414L11 9.586V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold">Important Information</p>
                            <p class="text-sm mt-1">Meeting details and Google Meet link have been sent to your email.</p>
                            <p class="text-sm mt-1">You'll also receive an SMS reminder 30 minutes before your appointment.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection