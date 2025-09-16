<div class="p-1 bg-gray-50 rounded-lg shadow-sm border border-gray-200"> 
    @forelse($selectedPatient->appointments->sortByDesc('appointment_date') as $appointment)
        <a href="{{ route('doctor.appointments.view', $appointment->id) }}" class="block mb-3 p-3 bg-white rounded-md shadow-sm border border-gray-200 hover:bg-gray-50 cursor-pointer">
            <p class="font-semibold text-sm text-gray-800">Date & Time: {{ \Carbon\Carbon::parse($appointment->appointment_datetime)->timezone('Asia/Hong_Kong')->format('M d, Y h:i A') }}</p>
            <p class="text-sm text-gray-600">CC: {{ $appointment->chief_complaint ?? 'N/A' }}</p>
            <p class="text-sm text-gray-600">Status: {{ $appointment->status ?? 'N/A' }}</p>
        </a>
    @empty
        <p class="text-gray-500  text-sm text-center">No past appointments for this patient.</p>
    @endforelse
</div>