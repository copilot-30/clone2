<div class="bg-white rounded-lg shadow-sm border border-gray-200">
  <div class="bg-emerald-600 text-white px-6 py-4 rounded-t-lg flex justify-between items-center">
    <h2 class="text-lg font-semibold">Upcoming Appointments</h2>
    <button class="text-white hover:text-emerald-100 transition-colors">
      View
    </button>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Date / Time
          </th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Patient
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
          $appointments = [
            ['id' => '1', 'dateTime' => 'Mar 10, 2025 / 9:00 AM', 'patient' => 'Juan Dela Cruz', 'mode' => 'Online', 'status' => 'Confirmed'], 
          ];

          $getStatusColor = function($status) {
            switch ($status) {
              case 'Confirmed':
                return 'text-emerald-600 bg-emerald-50';
              case 'Pending':
                return 'text-orange-600 bg-orange-50';
              case 'Cancelled':
                return 'text-red-600 bg-red-50';
              default:
                return 'text-gray-600 bg-gray-50';
            }
          };
        @endphp

        @foreach ($appointments as $appointment)
          <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
              {{ $appointment['dateTime'] }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
              {{ $appointment['patient'] }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
              {{ $appointment['mode'] }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $getStatusColor($appointment['status']) }}">
                {{ $appointment['status'] }}
              </span>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>