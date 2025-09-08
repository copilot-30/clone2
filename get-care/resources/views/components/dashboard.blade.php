@extends('admin_layout')


@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
  {{-- Top Section - Appointments and Patients --}}
  <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-6">
    @include('components.upcoming-appointments')
    @include('components.assigned-patients')
  </div>

  {{-- Bottom Section - Recent Notes --}}
  @include('components.recent-notes')
</div>
@endsection