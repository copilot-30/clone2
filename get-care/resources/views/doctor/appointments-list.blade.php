@extends('admin_layout')

@section('content')
<div class="p-4">
    <div class="bg-white p-6 rounded-lg shadow-xl">
        <h2 class="text-2xl font-bold text-emerald-600 mb-6">My Appointments</h2>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Patient Name</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date & Time</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Clinic</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($appointments as $appointment)
                        <tr class="hover:bg-gray-100">
                            <td class="py-3 px-4 border-b border-gray-200">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</td>
                            <td class="py-3 px-4 border-b border-gray-200">{{ $appointment->appointment_datetime->format('M d, Y h:i A') }}</td>
                            <td class="py-3 px-4 border-b border-gray-200">{{ ucfirst(str_replace('_', ' ', $appointment->type)) }}</td>
                            <td class="py-3 px-4 border-b border-gray-200">{{ $appointment->clinic->name ?? 'N/A' }}</td>
                            <td class="py-3 px-4 border-b border-gray-200">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($appointment->status == 'scheduled') bg-blue-100 text-blue-800
                                    @elseif($appointment->status == 'completed') bg-green-100 text-green-800
                                    @elseif($appointment->status == 'cancelled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 border-b border-gray-200 text-sm">
                                <a href="{{ route('doctor.appointments.view', $appointment->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                @if($appointment->status == 'scheduled')
                                    <button type="button" onclick="openCancelModal('{{ $appointment->id }}')" class="text-red-600 hover:text-red-900">Cancel</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-3 px-4 text-center text-gray-500">No appointments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Cancel Appointment Modal -->
<div id="cancelModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <h3 class="text-lg font-bold mb-4">Cancel Appointment</h3>
        <form action="" method="POST" id="cancelForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="appointment_id" id="cancelAppointmentId">
            <div class="mb-4">
                <label for="cancellation_reason" class="block text-gray-700 text-sm font-bold mb-2">Reason for Cancellation:</label>
                <textarea name="cancellation_reason" id="cancellation_reason" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-2">Cancel Appointment</button>
                <button type="button" onclick="closeCancelModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Close</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCancelModal(appointmentId) {
        document.getElementById('cancelAppointmentId').value = appointmentId;
        document.getElementById('cancelForm').action = "{{ url('doctor/appointments') }}" + '/' + appointmentId + '/cancel';
        document.getElementById('cancelModal').classList.remove('hidden');
    }

    function closeCancelModal() {
        document.getElementById('cancelModal').classList.add('hidden');
        document.getElementById('cancellation_reason').value = ''; // Clear reason
    }
</script>
@endsection