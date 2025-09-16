@extends('patient_layout')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    @include('patient.medical-records-tabs', ['allRecords' => $allRecords, 'patient' => $patient])
</div>
@endsection