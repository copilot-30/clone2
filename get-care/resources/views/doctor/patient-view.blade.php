@extends('admin_layout')

@section('content')
<div class="p-4">
    <div class="flex bg-gray-100 rounded-lg shadow-xl min-h-[80vh]">
        <!-- Left Patient List Panel -->
        <div class="w-1/4 bg-white border-r border-gray-200 p-4">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Patients</h2>
            <div class="mb-4">
                <div class="relative">
                    <input type="text" placeholder="Search patients..." class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <div class="flex border-b border-gray-200 mb-4">
                <button class="px-4 py-2 text-emerald-600 border-b-2 border-emerald-600 font-semibold">My Patients</button>
                <button class="px-4 py-2 text-gray-600 hover:text-emerald-600">Shared Cases</button>
            </div>

            <!-- Patient List Items (Example) -->
            <!-- Patient List Items -->
            <div class="space-y-3">
                @forelse($patients as $patient)
                    @php
                        $initials = strtoupper(substr($patient->first_name, 0, 1) . substr($patient->last_name, 0, 1));
                        $isActive = (isset($selectedPatient) && $selectedPatient->id == $patient->id) ? 'bg-emerald-50 border border-emerald-300' : 'bg-white hover:bg-gray-50';
                    @endphp
                    <div class="flex items-center p-3 rounded-lg shadow-sm cursor-pointer {{ $isActive }}" data-patient-id="{{ $patient->id }}">
                        <div class="w-10 h-10 bg-purple-200 rounded-full flex items-center justify-center text-purple-700 font-bold mr-3">{{ $initials }}</div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $patient->first_name }} {{ $patient->last_name }}</p>
                            <p class="text-sm text-gray-600">{{ $patient->age ?? 'N/A' }} years old • {{ $patient->sex ?? 'N/A' }} • {{ $patient->blood_type ?? 'N/A' }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center">No patients assigned yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Right Patient Details Panel -->
        <div class="w-3/4 p-6 bg-white">
            @if(isset($selectedPatient))
            <div class="flex items-center mb-6">
                <div class="w-16 h-16 bg-purple-200 rounded-full flex items-center justify-center text-purple-700 font-bold text-2xl mr-4">
                    {{ strtoupper(substr($selectedPatient->first_name, 0, 1) . substr($selectedPatient->last_name, 0, 1)) }}
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $selectedPatient->first_name }} {{ $selectedPatient->last_name }}</h1>
                    <p class="text-lg text-gray-700">{{ $selectedPatient->age ?? 'N/A' }} years old • {{ $selectedPatient->sex ?? 'N/A' }} • {{ $selectedPatient->blood_type ?? 'N/A' }}</p>
                </div>
            </div>
            @else
            <div class="flex items-center justify-center h-full text-gray-500">
                Select a patient from the list to view their details.
            </div>
            @endif
            <!-- Tabs for Patient Information -->
            <div class="border-b border-gray-200 mb-6">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-gray-600 hover:text-gray-900 border-transparent" data-tab="basic-information">Basic Information</button>
                    <button class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-gray-600 hover:text-gray-900 border-transparent" data-tab="medical-background">Medical Background</button>
                    <button class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-gray-600 hover:text-gray-900 border-transparent" data-tab="soap-notes">SOAP Notes</button>
                    <button class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-gray-600 hover:text-gray-900 border-transparent" data-tab="notes">Notes</button>
                    <button class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-emerald-600 border-emerald-600" data-tab="doctors">Doctors</button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div id="tab-content">
                <!-- Doctors Tab Content -->
                <div id="doctors-tab" class="tab-pane">
                    @if(isset($selectedPatient))
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Doctors</h3>
                        <div class="space-y-4">
                            <!-- Primary Doctor -->
                            @if($selectedPatient->primaryAttendingPhysician)
                                <div class="flex items-center p-4 bg-white rounded-lg shadow-sm border border-gray-200">
                                    <div class="w-10 h-10 bg-yellow-200 rounded-full flex items-center justify-center text-yellow-700 font-bold mr-3">
                                        {{ strtoupper(substr($selectedPatient->primaryAttendingPhysician->doctor->first_name, 0, 1) . substr($selectedPatient->primaryAttendingPhysician->doctor->last_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">You (Dr. {{ $selectedPatient->primaryAttendingPhysician->doctor->last_name }})</p>
                                        <p class="text-sm text-gray-600">Primary Doctor</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Referring Doctors -->
                            @forelse($selectedPatient->sharedCases as $sharedCase)
                                <div class="flex items-center p-4 bg-white rounded-lg shadow-sm border border-gray-200 justify-between">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-purple-200 rounded-full flex items-center justify-center text-purple-700 font-bold mr-3">
                                            {{ strtoupper(substr($sharedCase->doctor->first_name, 0, 1) . substr($sharedCase->doctor->last_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">Dr. {{ $sharedCase->doctor->first_name }} {{ $sharedCase->doctor->last_name }}</p>
                                            <p class="text-sm text-gray-600">Referring Doctor</p>
                                            <p class="text-sm text-emerald-600">{{ $sharedCase->doctor->specialization }}</p>
                                            <p class="text-sm text-gray-500">{{ $sharedCase->doctor->email }}</p>
                                        </div>
                                    </div>
                                    <button class="text-red-600 hover:text-red-800 text-sm">Remove</button>
                                </div>
                            @empty
                                <p class="text-gray-500">No referring doctors.</p>
                            @endforelse
                        </div>
                        <button class="mt-6 px-6 py-3 bg-gray-800 text-white font-semibold rounded-lg shadow hover:bg-gray-700">Add Doctor</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('nav button');
        const tabContent = document.getElementById('tab-content');
        const patientListItems = document.querySelectorAll('.patient-list-item'); // Assuming a class for patient list items

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs
                tabs.forEach(t => {
                    t.classList.remove('border-emerald-600', 'text-emerald-600');
                    t.classList.add('border-transparent', 'text-gray-600', 'hover:text-gray-900');
                });

                // Add active class to clicked tab
                this.classList.add('border-emerald-600', 'text-emerald-600');
                this.classList.remove('border-transparent', 'text-gray-600', 'hover:text-gray-900');

                // Hide all tab panes
                Array.from(tabContent.children).forEach(pane => {
                    pane.style.display = 'none';
                });

                // Show the corresponding tab pane
                const targetTab = this.dataset.tab;
                const targetPane = document.getElementById(targetTab + '-tab');
                if (targetPane) {
                    targetPane.style.display = 'block';
                }
            });
        });

        // Event listener for patient list item clicks
        document.querySelectorAll('.flex.items-center.p-3.rounded-lg.shadow-sm.cursor-pointer').forEach(item => {
            item.addEventListener('click', function() {
                const patientId = this.dataset.patientId;
                if (patientId) {
                    window.location.href = `{{ route('doctor.patients.view') }}/${patientId}`;
                }
            });
        });

        // Set initial active tab (Doctors tab in this case, based on the image)
        const initialActiveTab = document.querySelector('button[data-tab="doctors"]');
        if (initialActiveTab) {
            initialActiveTab.click();
        }
    });
</script>
@endsection