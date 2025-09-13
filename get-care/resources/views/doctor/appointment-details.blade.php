@extends('admin_layout')

@section('content')
<div class="p-4">
    <div class="bg-white rounded-lg shadow-xl p-6">
        <h2 class="text-2xl font-bold text-emerald-600 mb-6">Appointment Details</h2>

        <div class="mb-4">
            <p class="text-gray-700"><strong class="font-semibold">Patient:</strong> {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</p>
            <p class="text-gray-700"><strong class="font-semibold">Date & Time:</strong> {{ $appointment->appointment_datetime->format('M d, Y H:i A') }}</p>
            <p class="text-gray-700"><strong class="font-semibold">Type:</strong> {{ ucfirst($appointment->type) }} ({{ ucfirst($appointment->subtype) }})</p>
            @if($appointment->clinic)
                <p class="text-gray-700"><strong class="font-semibold">Clinic:</strong> {{ $appointment->clinic->name }}</p>
            @endif
            @if($appointment->meet_link)
                <p class="text-gray-700"><strong class="font-semibold">Meet Link:</strong> <a href="{{ $appointment->meet_link }}" target="_blank" class="text-blue-600 hover:underline">{{ $appointment->meet_link }}</a></p>
            @endif
            <p class="text-gray-700"><strong class="font-semibold">Status:</strong> {{ ucfirst($appointment->status) }}</p>
            @if($appointment->chief_complaint)
                <p class="text-gray-700"><strong class="font-semibold">Chief Complaint:</strong> {{ $appointment->chief_complaint }}</p>
            @endif
            @if($appointment->cancellation_reason)
                <p class="text-gray-700"><strong class="font-semibold">Cancellation Reason:</strong> {{ $appointment->cancellation_reason }}</p>
            @endif
        </div>

        <div class="mt-6 flex justify-end">
            <a href="{{ route('doctor.dashboard') }}" class="bg-gray-300 text-gray-800 px-6 py-2 rounded-lg font-semibold hover:bg-gray-400 focus:outline-none">Back to Dashboard</a>
            {{-- Add action buttons here if needed, e.g., Cancel, Reschedule --}}
        </div>
    </div>
</div>
@endsection