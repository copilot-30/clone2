@extends('admin_layout')

@section('content')
<div class="p-4">
  <h1 class="text-2xl font-bold mb-4">Admin Dashboard</h1>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <div class="bg-white p-4 rounded shadow">
      <h2 class="text-xl font-semibold mb-2">System Overview</h2>
      <ul>
        <li>Total Doctors: X</li>
        <li>Total Patients: Y</li>
        <li>Appointments Today: Z</li>
      </ul>
    </div>
    <div class="bg-white p-4 rounded shadow">
      <h2 class="text-xl font-semibold mb-2">User Management</h2>
      <ul>
        <li>Manage Doctors</li>
        <li>Manage Patients</li>
      </ul>
    </div>
    <div class="bg-white p-4 rounded shadow">
      <h2 class="text-xl font-semibold mb-2">Reports & Logs</h2>
      <ul>
        <li>View Consultations</li>
        <li>View Audit Logs</li>
      </ul>
    </div>
  </div>
</div>
@endsection