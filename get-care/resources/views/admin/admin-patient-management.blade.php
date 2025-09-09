@extends('admin_layout')

@section('content')
<div class="p-4">
  <h1 class="text-2xl font-bold mb-4">Admin: Patient Management</h1>

  <div class="bg-white p-4 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Existing Patients</h2>
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        {{-- Dummy data, replace with dynamic data --}}
        <tr>
          <td class="px-6 py-4 whitespace-nowrap">Alice Smith</td>
          <td class="px-6 py-4 whitespace-nowrap">alice.smith@example.com</td>
          <td class="px-6 py-4 whitespace-nowrap">123-456-7890</td>
          <td class="px-6 py-4 whitespace-nowrap">123 Main St</td>
          <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            <a href="#" class="text-indigo-600 hover:text-indigo-900 mr-2">View Details</a>
            <a href="#" class="text-red-600 hover:text-red-900">Contact</a>
          </td>
        </tr>
        <tr>
          <td class="px-6 py-4 whitespace-nowrap">Bob Johnson</td>
          <td class="px-6 py-4 whitespace-nowrap">bob.j@example.com</td>
          <td class="px-6 py-4 whitespace-nowrap">098-765-4321</td>
          <td class="px-6 py-4 whitespace-nowrap">456 Oak Ave</td>
          <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            <a href="#" class="text-indigo-600 hover:text-indigo-900 mr-2">View Details</a>
            <a href="#" class="text-red-600 hover:text-red-900">Contact</a>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
@endsection