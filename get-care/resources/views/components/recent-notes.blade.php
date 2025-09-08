<div class="bg-white rounded-lg shadow-sm border border-gray-200">
  <div class="bg-emerald-600 text-white px-6 py-4 rounded-t-lg flex justify-between items-center">
    <h2 class="text-lg font-semibold">Recent Notes</h2>
    <button class="text-white hover:text-emerald-100 transition-colors">
      Add Notes
    </button>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Date
          </th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Patient
          </th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Notes
          </th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200">
        @foreach ([
            ['id' => '1', 'date' => 'Mar 10, 2025', 'patient' => 'Juan Dela Cruz', 'notes' => 'quis nostrud exercitation ullamco laboris nisl ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit'],
            ['id' => '2', 'date' => 'Mar 10, 2025', 'patient' => 'Juan Dela Cruz', 'notes' => 'quis nostrud exercitation ullamco laboris nisl ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate'],
            ['id' => '3', 'date' => 'Mar 10, 2025', 'patient' => 'Maria Clara', 'notes' => 'Duis aute irure dolor in reprehenderit'],
            ['id' => '4', 'date' => 'Mar 10, 2025', 'patient' => 'Juan Dela Cruz', 'notes' => 'in voluptate velit esse cillum dolore eu fugiat nulla pariatur'],
            ['id' => '5', 'date' => 'Mar 10, 2025', 'patient' => 'Susan Ramirez', 'notes' => 'in voluptate velit esse cillum dolore eu fugiat nulla pariatur']
        ] as $note)
          <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
              {{ $note['date'] }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
              {{ $note['patient'] }}
            </td>
            <td class="px-6 py-4 text-sm text-gray-600 max-w-md">
              <div class="truncate">
                {{ $note['notes'] }}
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>