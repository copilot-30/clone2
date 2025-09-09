<div class="bg-white rounded-lg shadow-sm border border-gray-200">
  <div class="bg-emerald-600 text-white px-6 py-4 rounded-t-lg">
    <h2 class="text-lg font-semibold">Assigned Patients</h2>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Patient
          </th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Condition
          </th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
            Action
          </th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200">
        @foreach ([
            ['id' => '1', 'name' => 'Juan Dela Cruz', 'condition' => 'Hypertension'],
            ['id' => '2', 'name' => 'Juan Dela Cruz', 'condition' => 'Diabetes'],
            ['id' => '3', 'name' => 'Maria Clara', 'condition' => 'Amnesia'],
            ['id' => '4', 'name' => 'Juan Dela Cruz', 'condition' => 'Hypertension'],
            ['id' => '5', 'name' => 'Susan Ramirez', 'condition' => 'Diabetes']
        ] as $patient)
          <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
              {{ $patient['name'] }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
              {{ $patient['condition'] }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <button class="text-emerald-600 hover:text-emerald-800 text-sm font-medium transition-colors">
                View
              </button>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>