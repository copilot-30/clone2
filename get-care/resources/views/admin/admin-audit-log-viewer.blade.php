@extends('admin_layout')

@section('content')
<div class="p-4">
  <h1 class="text-2xl font-bold mb-4">Admin: Audit Log Viewer</h1>

  <div class="bg-white p-4 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">System Audit Logs</h2>
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User Email</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Type</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User Agent</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        {{-- Dummy data, replace with dynamic data --}}
        <tr>
          <td class="px-6 py-4 whitespace-nowrap">2025-09-06T08:00:00Z</td>
          <td class="px-6 py-4 whitespace-nowrap">admin@example.com</td>
          <td class="px-6 py-4 whitespace-nowrap">login_success</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{"user_id":"user1","email":"admin@example.com","user_type":"admin"}</td>
          <td class="px-6 py-4 whitespace-nowrap">192.168.1.1</td>
          <td class="px-6 py-4 whitespace-nowrap">Mozilla/5.0</td>
        </tr>
        <tr>
          <td class="px-6 py-4 whitespace-nowrap">2025-09-06T08:05:00Z</td>
          <td class="px-6 py-4 whitespace-nowrap">doctor@example.com</td>
          <td class="px-6 py-4 whitespace-nowrap">doctor_created</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{"doctor_id":"doc1","user_id":"user2","email":"doctor@example.com","specialty":"Cardiology"}</td>
          <td class="px-6 py-4 whitespace-nowrap">192.168.1.2</td>
          <td class="px-6 py-4 whitespace-nowrap">Postman</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
@endsection