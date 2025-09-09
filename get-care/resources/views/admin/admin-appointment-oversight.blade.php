@extends('admin_layout')

@section('content')
<div class="p-4">
  <h1 class="text-2xl font-bold mb-4">Admin: Appointment Oversight</h1>

  <div class="bg-white p-4 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">All Appointments</h2>
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doctor</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date/Time</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        {{-- Dummy data, replace with dynamic data --}}
        <tr>
          <td class="px-6 py-4 whitespace-nowrap">Alice Smith</td>
          <td class="px-6 py-4 whitespace-nowrap">Dr. John Doe</td>
          <td class="px-6 py-4 whitespace-nowrap">2025-09-10 10:00 AM</td>
          <td class="px-6 py-4 whitespace-nowrap">online</td>
          <td class="px-6 py-4 whitespace-nowrap">scheduled</td>
          <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            <button class="text-red-600 hover:text-red-900 mr-2">Cancel</button>
            <button class="text-yellow-600 hover:text-yellow-900 mr-2">Reschedule</button>
            <button class="text-blue-600 hover:text-blue-900">Reassign</button>
          </td>
        </tr>
        <tr>
          <td class="px-6 py-4 whitespace-nowrap">Bob Johnson</td>
          <td class="px-6 py-4 whitespace-nowrap">Dr. Jane Smith</td>
          <td class="px-6 py-4 whitespace-nowrap">2025-09-10 02:00 PM</td>
          <td class="px-6 py-4 whitespace-nowrap">face-to-face</td>
          <td class="px-6 py-4 whitespace-nowrap">completed</td>
          <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            <button class="text-red-600 hover:text-red-900 mr-2">Cancel</button>
            <button class="text-yellow-600 hover:text-yellow-900 mr-2">Reschedule</button>
            <button class="text-blue-600 hover:text-blue-900">Reassign</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
@endsection