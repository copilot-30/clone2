@extends('admin_layout')

@section('content')
<div class="p-4">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Doctor Dashboard</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Card: Total Patients -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Patients</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalPatients }}</p>
                </div>
            </div>
        </div>

        <!-- Card: Upcoming Appointments -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                    <i class="fas fa-calendar-check text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm font-medium">Upcoming Appointments</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $upcomingAppointments->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Card: Pending Shared Cases -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                    <i class="fas fa-share-alt text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm font-medium">Pending Shared Cases</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $pendingSharedCases }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Section: Upcoming Appointments List -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-2xl font-semibold text-gray-800 mb-4">Your Next Appointments</h3>
        @if ($upcomingAppointments->isEmpty())
            <p class="text-gray-600">No upcoming appointments.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Clinic / Link</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($upcomingAppointments as $appointment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $appointment->appointment_datetime->format('M d, Y H:i A') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($appointment->type) }} ({{ ucfirst($appointment->subtype) }})</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($appointment->clinic)
                                        {{ $appointment->clinic->name }}
                                    @elseif ($appointment->meet_link)
                                        <a href="{{ $appointment->meet_link }}" target="_blank" class="text-blue-600 hover:underline">Meet Link</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('doctor.appointments.view', $appointment->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">View</a>
                                    <!-- Add Cancel/Reschedule buttons if needed -->
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection