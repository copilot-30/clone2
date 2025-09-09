@extends('admin_layout')

@section('content')
<div class="p-4">
  <h1 class="text-2xl font-bold mb-4">Admin: Doctor Management</h1>

  <div class="bg-white p-4 rounded shadow mb-6">
    <h2 class="text-xl font-semibold mb-4">Create New Doctor Account</h2>
    <form action="#" method="POST" class="space-y-4">
      @csrf
      <input type="text" name="name" placeholder="Name" class="block w-full border p-2 rounded" />
      <input type="email" name="email" placeholder="Email" class="block w-full border p-2 rounded" />
      <input type="password" name="password" placeholder="Password" class="block w-full border p-2 rounded" />
      <input type="password" name="password_confirmation" placeholder="Confirm Password" class="block w-full border p-2 rounded" />
      <select name="specialty_id" class="block w-full border p-2 rounded">
        <option value="">Select Specialty</option>
        {{-- This would be dynamically populated --}}
        <option value="uuid1">Cardiology</option>
        <option value="uuid2">Pediatrics</option>
      </select>
      <input type="text" name="medical_license_number" placeholder="Medical License Number" class="block w-full border p-2 rounded" />
      <input type="text" name="contact_number" placeholder="Contact Number" class="block w-full border p-2 rounded" />
      <input type="text" name="address" placeholder="Address" class="block w-full border p-2 rounded" />
      <textarea name="bio" placeholder="Bio" class="block w-full border p-2 rounded"></textarea>
      <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Create Doctor
      </button>
    </form>
  </div>

  <div class="bg-white p-4 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Existing Doctors</h2>
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Specialty</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">License</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        {{-- Dummy data, replace with dynamic data --}}
        <tr>
          <td class="px-6 py-4 whitespace-nowrap">Dr. John Doe</td>
          <td class="px-6 py-4 whitespace-nowrap">john.doe@example.com</td>
          <td class="px-6 py-4 whitespace-nowrap">Cardiology</td>
          <td class="px-6 py-4 whitespace-nowrap">MD12345</td>
          <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            <a href="#" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</a>
            <a href="#" class="text-red-600 hover:text-red-900">Delete</a>
          </td>
        </tr>
        <tr>
          <td class="px-6 py-4 whitespace-nowrap">Dr. Jane Smith</td>
          <td class="px-6 py-4 whitespace-nowrap">jane.smith@example.com</td>
          <td class="px-6 py-4 whitespace-nowrap">Pediatrics</td>
          <td class="px-6 py-4 whitespace-nowrap">MD67890</td>
          <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            <a href="#" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</a>
            <a href="#" class="text-red-600 hover:text-red-900">Delete</a>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
@endsection