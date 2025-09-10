@extends('admin_layout')

@section('content')
<div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-xl max-w-md w-full text-center">
        <div class="flex justify-center mb-6">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
        </div>
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Appointment Confirmed!</h2>
        <p class="text-gray-600 mb-6">Your appointment has been successfully booked.</p>

        <div class="text-left mb-6 space-y-2">
            <p><strong class="text-gray-700">Appointment ID:</strong> #{{ substr($appointment->id, 0, 8) }}</p>
            <p><strong class="text-gray-700">Doctor:</strong> Dr. {{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}</p>
            <p><strong class="text-gray-700">Date & Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_datetime)->format('l, F j, Y \a\t h:i A') }}</p>
            <p><strong class="text-gray-700">Type:</strong> {{ ucfirst($appointment->type) }} Consultation</p>
            @if($appointment->type === 'clinic' && $appointment->clinic)
                <p><strong class="text-gray-700">Location:</strong> {{ $appointment->clinic->name }} ({{ $appointment->clinic->address }})</p>
            @elseif($appointment->type === 'online' && $appointment->meet_link)
                <p><strong class="text-gray-700">Google Meet Link:</strong> <a href="{{ $appointment->meet_link }}" class="text-blue-600 hover:underline" target="_blank">{{ $appointment->meet_link }}</a></p>
            @endif
        </div>

        <div class="bg-blue-50 p-4 rounded-lg text-sm text-blue-700 mb-6">
            <p class="mb-2">
                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Meeting details and Google Meet link have been sent to your email.
            </p>
            <p>
                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                You'll also receive an SMS reminder 30 minutes before your appointment.
            </p>
        </div>

        <a href="{{ route('patient.dashboard') }}" class="block w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 focus:outline-none">View My Appointments</a>
    </div>
</div>
@endsection