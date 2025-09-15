@extends('patient_layout')


@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    @include('patient.patient-profile-display')
    @include('patient.medical-records-tabs')
    {{-- Top Section - Appointments and Patients --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-6">
        @include('patient.upcoming-appointments') 

  {{-- Bottom Section - Recent Notes --}}
  @include('patient.recent-notes')
</div>
@endsection