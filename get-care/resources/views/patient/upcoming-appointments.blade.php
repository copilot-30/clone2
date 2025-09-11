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
  
  @if(isset($recentNotes) && $recentNotes->count() > 0)
  <div class="border-t border-gray-200 mt-4">
    <div class="px-6 py-4">
      <h3 class="text-md font-semibold text-gray-800 mb-3">Recent Notes</h3>
      <div class="space-y-3">
        @foreach($recentNotes as $note)
          <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50">
            <div class="flex justify-between items-start">
              <h4 class="font-medium text-gray-800">{{ $note->subject ?? 'Untitled Note' }}</h4>
              <span class="text-xs text-gray-500">{{ $note->created_at->format('M j, Y') }}</span>
            </div>
            <p class="text-sm text-gray-600 mt-1 line-clamp-2">
              {{ Str::limit($note->content, 100) }}
            </p>
            <div class="mt-2 text-xs text-gray-500">
              Dr. {{ $note->doctor->first_name ?? '' }} {{ $note->doctor->last_name ?? '' }}
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
  @endif
</div>