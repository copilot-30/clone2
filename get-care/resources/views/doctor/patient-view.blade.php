@extends('admin_layout')

@section('content')
<div class="p-4">
    <div class="flex bg-gray-100 rounded-lg shadow-xl min-h-[80vh]">
        <!-- Left Patient List Panel -->
        <div class="w-1/5 bg-white border-r border-gray-200 p-4">
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

        <!-- Middle Patient Details Panel -->
        <div class="w-3/5 p-6 bg-white border-r border-gray-200">
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
                    <button class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-emerald-600 border-emerald-600" data-tab="basic-information">Basic Information</button>
                    <button class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-gray-600 hover:text-gray-900 border-transparent" data-tab="medical-background">Medical Background</button>
                    <button class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-gray-600 hover:text-gray-900 border-transparent" data-tab="soap-notes">SOAP Notes</button>
                    <button class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-gray-600 hover:text-gray-900 border-transparent" data-tab="notes">Patient Notes</button>
                    <button class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-gray-600 hover:text-gray-900 border-transparent" data-tab="doctors">Doctors</button>
                </nav>
            </div>

            <!-- Tab Content -->
            <!-- Tab Content -->
            <div id="tab-content">
                <!-- Basic Information Tab Content -->
                <div id="basic-information-tab" class="tab-pane">
                    @if(isset($selectedPatient))
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Basic Information</h3>
                        <div class="space-y-2 text-gray-700">
                            <p><span class="font-semibold">Full Name:</span> {{ $selectedPatient->first_name }} {{ $selectedPatient->middle_name }} {{ $selectedPatient->last_name }} {{ $selectedPatient->suffix }}</p>
                            <p><span class="font-semibold">Date of Birth:</span> {{ $selectedPatient->date_of_birth ? $selectedPatient->date_of_birth->format('M d, Y') : 'N/A' }}</p>
                            <p><span class="font-semibold">Age:</span> {{ $selectedPatient->age ?? 'N/A' }}</p>
                            <p><span class="font-semibold">Sex:</span> {{ $selectedPatient->sex ?? 'N/A' }}</p>
                            <p><span class="font-semibold">Blood Type:</span> {{ $selectedPatient->blood_type ?? 'N/A' }}</p>
                            <p><span class="font-semibold">Civil Status:</span> {{ $selectedPatient->civil_status ?? 'N/A' }}</p>
                            <p><span class="font-semibold">Phone Number:</span> {{ $selectedPatient->primary_mobile ?? 'N/A' }}</p>
                            <p><span class="font-semibold">Address:</span> {{ $selectedPatient->address ?? 'N/A' }}</p>
                            <p><span class="font-semibold">PhilHealth No:</span> {{ $selectedPatient->philhealth_no ?? 'N/A' }}</p>
                            <p><span class="font-semibold">Tag:</span> {{ $selectedPatient->tag ?? 'N/A' }}</p>
                        </div>
                    @endif
                </div>

                <!-- Medical Background Tab Content -->
                <div id="medical-background-tab" class="tab-pane hidden">
                    @if(isset($selectedPatient))
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Known Medical Background</h3>
                        <div class="space-y-2 text-gray-700">
                            <p><span class="font-semibold">Medical Conditions:</span> {{ $selectedPatient->medical_conditions ?? 'N/A' }}</p>
                            <p><span class="font-semibold">Allergies:</span> {{ $selectedPatient->allergies ?? 'N/A' }}</p>
                            <p><span class="font-semibold">Surgeries:</span> {{ $selectedPatient->surgeries ?? 'N/A' }}</p>
                            <p><span class="font-semibold">Family History:</span> {{ $selectedPatient->family_history ?? 'N/A' }}</p>
                            <p><span class="font-semibold">Medications:</span> {{ $selectedPatient->medications ?? 'N/A' }}</p>
                            <p><span class="font-semibold">Supplements:</span> {{ $selectedPatient->supplements ?? 'N/A' }}</p>
                        </div>



                    @endif
                    
                    
                    @if(isset($selectedPatient) && $selectedPatient->medicalBackground)
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Medical Background</h3>
                        <div class="space-y-2 text-gray-700">
                            <p><span class="font-semibold">Medical Conditions:</span> {{ $selectedPatient->medicalBackground->medical_conditions ?? 'N/A' }}</p>
                            <p><span class="font-semibold">Allergies:</span> {{ $selectedPatient->medicalBackground->allergies ?? 'N/A' }}</p>
                            <p><span class="font-semibold">Surgeries:</span> {{ $selectedPatient->medicalBackground->surgeries ?? 'N/A' }}</p>
                            <p><span class="font-semibold">Family History:</span> {{ $selectedPatient->medicalBackground->family_history ?? 'N/A' }}</p>
                            <p><span class="font-semibold">Medications:</span> {{ $selectedPatient->medicalBackground->medications ?? 'N/A' }}</p>
                            <p><span class="font-semibold">Supplements:</span> {{ $selectedPatient->medicalBackground->supplements ?? 'N/A' }}</p>
                        </div>
                    @else
                        <!-- <p class="text-gray-500">No medical background information available for this patient.</p> -->
                    @endif
                </div>

                <!-- SOAP Notes Tab Content -->
                <div id="soap-notes-tab" class="tab-pane hidden">
                    @if(isset($selectedPatient) && $selectedPatient->soapNotes->isNotEmpty())
                        <h3 class="text-xl font-bold text-gray-800 mb-4">SOAP Notes</h3>
                        <div class="space-y-4">
                            @foreach($selectedPatient->soapNotes as $soapNote)
                                <div class="p-4 bg-white rounded-lg shadow-sm border border-gray-200">
                                    <p class="text-sm text-gray-600 mb-2"><strong>Date:</strong> {{ $soapNote->created_at->format('M d, Y H:i') }}</p>
                                    <p><span class="font-semibold">Subjective:</span> {{ $soapNote->subjective }}</p>
                                    <p><span class="font-semibold">Objective:</span> {{ $soapNote->objective }}</p>
                                    <p><span class="font-semibold">Assessment:</span> {{ $soapNote->assessment }}</p>
                                    <p><span class="font-semibold">Plan:</span> {{ $soapNote->plan }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No SOAP notes available for this patient.</p>
                    @endif
                </div>

                <!-- Notes Tab Content -->
                <!-- Notes Tab Content -->
                <div id="notes-tab" class="tab-pane hidden">
                    @if(isset($selectedPatient))
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold text-gray-800">Patient Notes</h3>
                            <button id="openPatientNoteModal" class="px-4 py-2 bg-emerald-600 text-white font-semibold rounded-md hover:bg-emerald-700">Add New Note</button>
                        </div>
                        @if($selectedPatient->patientNotes->isNotEmpty())
                            <div class="space-y-4">
                                @foreach($selectedPatient->patientNotes as $note)
                                    <div class="p-4 bg-white rounded-lg shadow-sm border border-gray-200">
                                        <p class="text-sm text-gray-600 mb-2"><strong>Date:</strong> {{ $note->created_at->format('M d, Y H:i') }}</p>
                                        <p><span class="font-semibold">Subject:</span> {{ $note->subject ?? 'N/A' }}</p>
                                        <p><span class="font-semibold">Content:</span> {{ $note->content }}</p>
                                        <p class="text-sm text-gray-600">Type: {{ ucfirst($note->note_type ?? 'N/A') }}</p>
                                        <p class="text-sm text-gray-600">Visibility: {{ ucfirst($note->visibility ?? 'N/A') }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No patient notes available for this patient.</p>
                        @endif
                    @endif
                </div>
                <!-- Doctors Tab Content -->
                <div id="doctors-tab" class="tab-pane hidden">
                    @if(isset($selectedPatient))
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Doctors</h3>
                        <div class="space-y-4">
                            <!-- Primary Doctor -->
                            @if($selectedPatient->attendingPhysician)
                                <div class="flex items-center p-4 bg-white rounded-lg shadow-sm border border-gray-200">
                                    <div class="w-10 h-10 bg-yellow-200 rounded-full flex items-center justify-center text-yellow-700 font-bold mr-3">
                                        {{ strtoupper(substr($selectedPatient->attendingPhysician->doctor->first_name, 0, 1) . substr($selectedPatient->attendingPhysician->doctor->last_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">You (Dr. {{ $selectedPatient->attendingPhysician->doctor->last_name }})</p>
                                        <p class="text-sm text-gray-600">Primary Doctor</p>
                                    </div>
                                </div>
                            @else
                                <p class="text-gray-500">No primary doctor assigned.</p>
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
<!-- Right Sidebar Panel for Appointments -->
<div class="w-1/4 p-4 bg-white">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Appointments</h2>
    <div class="space-y-4">
        @if(isset($selectedPatient))
            <button id="openAppointmentModal" class="w-full bg-emerald-600 text-white p-2 rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 mb-4">Book New Appointment</button>
            @include('doctor.components.past-appointments', ['selectedPatient' => $selectedPatient])
        @else
            <p class="text-gray-500 text-center">Select a patient to manage appointments.</p>
        @endif
    </div>
</div>
</div>
</div>

<!-- Appointment Modal -->
<div id="appointmentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
<div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
<div class="flex justify-end">
    <button id="closeAppointmentModal" class="text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>
</div>
@if(isset($selectedPatient))
    @include('doctor.components.appointment-form', ['selectedPatient' => $selectedPatient])
@endif
</div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
const tabs = document.querySelectorAll('nav button');
const tabContent = document.getElementById('tab-content');

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

// Set initial active tab (Basic Information tab)
const initialActiveTab = document.querySelector('button[data-tab="basic-information"]');
const initialActivePane = document.getElementById('basic-information-tab');
if (initialActiveTab && initialActivePane) {
    initialActiveTab.click(); // This will handle setting the active class and displaying the pane
}

// Modal functionality
const openModalBtn = document.getElementById('openAppointmentModal');
const closeModalBtn = document.getElementById('closeAppointmentModal');
const appointmentModal = document.getElementById('appointmentModal');

if (openModalBtn) {
    openModalBtn.addEventListener('click', function() {
        appointmentModal.classList.remove('hidden');
    });
}

if (closeModalBtn) {
    closeModalBtn.addEventListener('click', function() {
        appointmentModal.classList.add('hidden');
    });
}

// Close modal when clicking outside
if (appointmentModal) {
    appointmentModal.addEventListener('click', function(event) {
        if (event.target === appointmentModal) {
            appointmentModal.classList.add('hidden');
        }
    });
}

// Patient Note Modal functionality
const openPatientNoteModalBtn = document.getElementById('openPatientNoteModal');
const closePatientNoteModalBtn = document.getElementById('closePatientNoteModal');
const patientNoteModal = document.getElementById('patientNoteModal');

if (openPatientNoteModalBtn) {
    openPatientNoteModalBtn.addEventListener('click', function() {
        patientNoteModal.classList.remove('hidden');
    });
}

if (closePatientNoteModalBtn) {
    closePatientNoteModalBtn.addEventListener('click', function() {
        patientNoteModal.classList.add('hidden');
    });
}

// Close patient note modal when clicking outside
if (patientNoteModal) {
    patientNoteModal.addEventListener('click', function(event) {
        if (event.target === patientNoteModal) {
            patientNoteModal.classList.add('hidden');
        }
    });
}
});
</script>

<!-- Patient Note Modal -->
<div id="patientNoteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
<div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
<div class="flex justify-end">
    <button id="closePatientNoteModal" class="text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>
</div>
@if(isset($selectedPatient))
    @include('doctor.components.patient-note-form', ['selectedPatient' => $selectedPatient])
@endif
</div>
</div>
@endsection