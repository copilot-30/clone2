<div class="bg-white rounded-lg shadow-sm border border-gray-200">
  <div class="bg-emerald-600 text-white px-6 py-4 rounded-t-lg flex justify-between items-center">
    <h2 class="text-lg font-semibold">Upcoming Appointments</h2>
    <a href="{{ route('patient.dashboard') }}" class="text-white hover:text-emerald-100 transition-colors">
      View All
    </a>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Date / Time
          </th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Doctor
          </th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Mode
          </th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Status
          </th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200">
        @php
          $getStatusColor = function($status) {
            switch ($status) {
              case 'confirmed':
                return 'text-emerald-600 bg-emerald-50';
              case 'pending':
                return 'text-orange-600 bg-orange-50';
              case 'cancelled':
                return 'text-red-600 bg-red-50';
              default:
                return 'text-gray-600 bg-gray-50';
            }
          };
        @endphp

        @forelse ($upcomingAppointments as $appointment)
          <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('patient.appointment-confirmed', $appointment->id) }}'">
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
              {{ $appointment->appointment_datetime->format('M j, Y / g:i A') }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
              Dr. {{ $appointment->doctor->first_name ?? '' }} {{ $appointment->doctor->last_name ?? '' }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
              {{ ucfirst($appointment->type) }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $getStatusColor($appointment->status) }}">
                {{ ucfirst($appointment->status) }}
              </span>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
              No upcoming appointments
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  

</div>