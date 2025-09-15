@extends('admin_layout')

@section('content')
<div class="p-4">
    <div class="flex bg-gray-100 rounded-lg shadow-xl min-h-[80vh]">
        <!-- Left Patient List Panel -->
        <div class="w-1/5 bg-white border-r border-gray-200 p-4">
            <h2 class="text-2xl font-bold text-emerald-600 border-emerald-600 mb-4">Patients</h2>
            <div class="mb-4">
                <div class="relative">
                    <form method="GET" action="{{route('doctor.patients.view')}}">
                    <input type="text" name="name" value={{$name}} placeholder="Search patients..." class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    </form>
                </div>
            </div>

            <div class="flex border-b border-gray-200 mb-4">
                <button id="my-patients-btn" class="px-4 py-2 {{ isset($filter) && $filter === 'my-patients' ? 'text-emerald-600 border-b-2 border-emerald-600 font-semibold' : 'text-gray-600 hover:text-emerald-600' }}">My Patients</button>
                <button id="shared-cases-btn" class="px-4 py-2 {{ isset($filter) && $filter === 'shared-cases' ? 'text-emerald-600 border-b-2 border-emerald-600 font-semibold' : 'text-gray-600 hover:text-emerald-600' }}">Shared Cases</button>
            </div>
 
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
            @if(isset($selectedPatient))
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
                    
                </div>

                <!-- Medical Background Tab Content -->
                <div id="medical-background-tab" class="tab-pane hidden">
                    
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Known Medical Background</h3>
                        <div class="space-y-2 text-gray-700">
                            <p><span class="font-semibold">Medical Conditions:</span> {{ $selectedPatient->medical_conditions ?? 'N/A' }}</p>
                            <p><span class="font-semibold">Allergies:</span> {{ $selectedPatient->allergies ?? 'N/A' }}</p>
                            <p><span class="font-semibold">Surgeries:</span> {{ $selectedPatient->surgeries ?? 'N/A' }}</p>
                            <p><span class="font-semibold">Family History:</span> {{ $selectedPatient->family_history ?? 'N/A' }}</p>
                            <p><span class="font-semibold">Medications:</span> {{ $selectedPatient->medications ?? 'N/A' }}</p>
                            <p><span class="font-semibold">Supplements:</span> {{ $selectedPatient->supplements ?? 'N/A' }}</p>
                        </div>



                    
                    
                    @if($selectedPatient->medicalBackground)
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
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-800">SOAP Notes</h3>
                        <!-- Add SOAP Note button - visible only to authorized doctors -->
                        @php
                            $canAddSoapNotes = false;
                            // Check if current doctor is the attending physician
                            if (isset($selectedPatient->attendingPhysician) && isset($doctor) && $selectedPatient->attendingPhysician->doctor_id == $doctor->id) {
                                $canAddSoapNotes = true;
                            }
                            // Check if current doctor is a receiving doctor with permission
                            elseif (isset($doctor)) {
                                $sharedCase = $selectedPatient->sharedCases->where('status', 'ACCEPTED')->where('receiving_doctor_id', $doctor->id)->first();
                                if ($sharedCase) {
                                    // For now, we'll allow all receiving doctors to add SOAP notes
                                    // In a more advanced implementation, we could check specific permissions
                                    $canAddSoapNotes = true;
                                }
                            }
                        @endphp
                        @if($canAddSoapNotes)
                            <a href="{{ route('doctor.soap-notes.create', ['patient' => $selectedPatient]) }}" class="px-4 py-2 bg-emerald-600 text-white font-semibold rounded-md hover:bg-emerald-700"><i class="fas fa-plus mr-2"></i>Add SOAP Note</a>
                        @endif
                    </div>
                    @if($selectedPatient->soapNotes->isNotEmpty())
                        <div class="grid grid-cols-1 gap-2 mb-4 md:grid-cols-6 sticky top-0 bg-white z-10 p-2 border-b border-gray-200" id="soap-notes-menu">
                            @foreach($selectedPatient->soapNotes()->orderBy('date', 'desc')->get() as $soapNote)
                                <a href="#soap-note-{{ $soapNote->id }}" class="soap-note-card  inline-block bg-white rounded-lg shadow-md p-3 mx-2  hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-emerald-500" data-soap-note-id="{{ $soapNote->id }}">
                                    <p class="text-sm font-semibold text-gray-700">{{ $soapNote->date->format('M d, Y') }}</p>
                                    <p class="text-xs text-gray-500">CC: {{$soapNote->chief_complaint  }}</p> 
                                </a>
                            @endforeach
                        </div>
                        <div class="space-y-4">
                            @foreach($selectedPatient->soapNotes()->orderBy('date', 'desc')->get() as $soapNote)
                                @include('doctor.components.soap-note-display', ['soapNote' => $soapNote, 'doctor' => $doctor])
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No SOAP notes available for this patient.</p>
                    @endif
                </div>
                <!-- Notes Tab Content -->
                <!-- Notes Tab Content -->
                <div id="notes-tab" class="tab-pane hidden">
                    
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
                    
                </div>
                <!-- Doctors Tab Content -->
                <div id="doctors-tab" class="tab-pane hidden">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-800">Doctors</h3>
                               <!-- Show Add Doctor button only for primary doctors -->
                                @if(isset($selectedPatient->attendingPhysician) && isset($doctor) && $selectedPatient->attendingPhysician->doctor_id == $doctor->id)
                                <button id="openAddDoctorModalBtn" class="px-4 py-2 bg-emerald-700 text-white font-semibold rounded-lg shadow hover:bg-gray-700"><i class="fas fa-plus mr-2"></i>Add Doctor</button>
                                @endif

                        </div>
                        <div class="space-y-4">
                            <!-- Primary Doctor -->
                            @if(isset($selectedPatient->attendingPhysician))
                                <div class="flex items-center p-4 bg-white rounded-lg shadow-sm border border-gray-200">
                                    <div class="w-10 h-10 bg-yellow-200 rounded-full flex items-center justify-center text-yellow-700 font-bold mr-3">
                                        {{ strtoupper(substr($selectedPatient->attendingPhysician->doctor->first_name, 0, 1) . substr($selectedPatient->attendingPhysician->doctor->last_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">Dr. {{ $selectedPatient->attendingPhysician->doctor->first_name }} {{ $selectedPatient->attendingPhysician->doctor->last_name }}</p>
                                        <p class="text-sm text-gray-600">Primary Doctor</p>
                                    </div>
                                </div>
                            @else
                                <p class="text-gray-500">No primary doctor assigned.</p>
                            @endif

                            <!-- Referring Doctors -->
                            @forelse($selectedPatient->sharedCases->where('status', 'ACCEPTED') as $sharedCase)
                                <!-- <div class="flex items-center p-4 bg-white rounded-lg shadow-sm border border-gray-200 justify-between">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-purple-200 rounded-full flex items-center justify-center text-purple-700 font-bold mr-3">
                                            {{ strtoupper(substr($sharedCase->sharingDoctor->first_name, 0, 1) . substr($sharedCase->sharingDoctor->last_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">Dr. {{ $sharedCase->sharingDoctor->first_name }} {{ $sharedCase->sharingDoctor->last_name }}</p>
                                            <p class="text-sm text-gray-600">Referring Doctor</p>
                                            <p class="text-sm text-emerald-600">{{ $sharedCase->sharingDoctor->specialization ?? 'N/A' }}</p>
                                            <p class="text-sm text-gray-500">{{ $sharedCase->sharingDoctor->email }}</p>
                                        </div>
                                    </div>
                               
                                </div> -->
                                @if(isset($sharedCase->receivingDoctor))
                                    <div class="flex items-center p-4 bg-white rounded-lg shadow-sm border border-gray-200 justify-between mt-2">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-blue-200 rounded-full flex items-center justify-center text-blue-700 font-bold mr-3">
                                                {{ strtoupper(substr($sharedCase->receivingDoctor->first_name, 0, 1) . substr($sharedCase->receivingDoctor->last_name, 0, 1)) }}
                                            
                                                    
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-800">Dr. {{ $sharedCase->receivingDoctor->first_name }} {{ $sharedCase->receivingDoctor->last_name }}</p>
                                                <p class="text-sm text-gray-600">
                                                    Collaborating Doctor
                                                     <!-- Show remove button only for primary doctors -->
                                                    @if(isset($selectedPatient->attendingPhysician) && isset($doctor) && $selectedPatient->attendingPhysician->doctor_id == $doctor->id)
                                                    <button class="text-red-600 hover:text-red-800 text-sm remove-doctor-btn" data-shared-case-id="{{ $sharedCase->id }}">Remove</button>
                                                    @endif
                                                </p>
                                                <p class="text-sm text-emerald-600">{{ $sharedCase->receivingDoctor->specialization ?? 'N/A' }}</p>
                                                <p class="text-sm text-gray-500">{{ $sharedCase->receivingDoctor->email }}</p>
                                            </div>
                                            
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <p class="text-gray-500">No accepted shared cases.</p>
                            @endforelse
                        </div>

                        <!-- Show invitations only for primary doctors -->
                        @if(isset($selectedPatient->attendingPhysician) && isset($doctor) && $selectedPatient->attendingPhysician->doctor_id == $doctor->id)
                        <h4 class="text-lg font-semibold text-gray-700 mt-6 mb-3">Pending Invitations</h4>
                        <div class="space-y-4">
                            @forelse($selectedPatient->sharedCases->where('status', 'PENDING') as $sharedCase)
                                <div class="flex items-center p-4 bg-white rounded-lg shadow-sm border border-gray-200 justify-between">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-orange-200 rounded-full flex items-center justify-center text-orange-700 font-bold mr-3">
                                            {{ strtoupper(substr($sharedCase->receivingDoctor->first_name, 0, 1) . substr($sharedCase->receivingDoctor->last_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">Dr. {{ $sharedCase->receivingDoctor->first_name }} {{ $sharedCase->receivingDoctor->last_name }}</p>
                                            <p class="text-sm text-gray-600">Pending Invitation</p>
                                            <p class="text-sm text-orange-600">{{ $sharedCase->receivingDoctor->specialization ?? 'N/A' }}</p>
                                            <p class="text-sm text-gray-500">{{ $sharedCase->receivingDoctor->email }}</p>
                                        </div>
                                    </div>
                                    <button class="text-red-600 hover:text-red-800 text-sm cancel-invite-btn" data-shared-case-id="{{ $sharedCase->id }}">Cancel Invite</button>
                                </div>
                            @empty
                                <p class="text-gray-500">No pending invitations.</p>
                            @endforelse
                        </div>

                        <h4 class="text-lg font-semibold text-gray-700 mt-6 mb-3">Rejected Invitations</h4>
                        <div class="space-y-4">
                            @forelse($selectedPatient->sharedCases->where('status', 'REJECTED') as $sharedCase)
                                <div class="flex items-center p-4 bg-white rounded-lg shadow-sm border border-gray-200 justify-between">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-red-200 rounded-full flex items-center justify-center text-red-700 font-bold mr-3">
                                            {{ strtoupper(substr($sharedCase->receivingDoctor->first_name, 0, 1) . substr($sharedCase->receivingDoctor->last_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">Dr. {{ $sharedCase->receivingDoctor->first_name }} {{ $sharedCase->receivingDoctor->last_name }}</p>
                                            <p class="text-sm text-gray-600">Rejected Invitation</p>
                                            <p class="text-sm text-red-600">{{ $sharedCase->receivingDoctor->specialization ?? 'N/A' }}</p>
                                            <p class="text-sm text-gray-500">{{ $sharedCase->receivingDoctor->email }}</p>
                                        </div>
                                    </div>
                                    <!-- Show remove button only for primary doctors -->
                                    @if(isset($selectedPatient->attendingPhysician) && isset($doctor) && $selectedPatient->attendingPhysician->doctor_id == $doctor->id)
                                    <button class="text-gray-600 hover:text-gray-800 text-sm remove-rejected-btn" data-shared-case-id="{{ $sharedCase->id }}">Remove from List</button>
                                    @endif
                                </div>
                            @empty
                                <p class="text-gray-500">No rejected invitations.</p>
                            @endforelse
                        </div>
                        @endif
                      
                </div>
            </div>
            @endif
        </div>
<!-- Right Sidebar Panel for Appointments -->
<div class="w-1/4 p-4 bg-white">
    <h2 class="text-2xl font-bold text-emerald-600 border-emerald-600 mb-4">Appointments</h2>
    <div class="space-y-4">
        @if(isset($selectedPatient))
            <button id="openAppointmentModal" class="w-full bg-emerald-600 text-white p-2 rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 mb-4"><i classs="fas fa-calendar-alt"></i> Book New Appointment</button>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
// Ensure selectedPatientData is always defined
let selectedPatientData = JSON.parse('{!! addslashes(json_encode($selectedPatient ?? new stdClass())) !!}');
// Fallback in case the PHP expression doesn't produce a valid JavaScript object
if (typeof selectedPatientData !== 'object' || selectedPatientData === null) {
    selectedPatientData = {};
}
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
        // Use a default filter value when selectedPatientData is empty or doesn't have filter property
        const currentFilter = (selectedPatientData && selectedPatientData.filter) ? selectedPatientData.filter : 'my-patients';
        console.log(`Patient list item clicked: patientId=${patientId}, currentFilter=${currentFilter}`);
        if (patientId) {
            window.location.href = `{{ route('doctor.patients.view') }}/${patientId}?filter=${currentFilter}`;
        }
    });
});

// Event listeners for My Patients and Shared Cases buttons
const myPatientsBtn = document.getElementById('my-patients-btn');
const sharedCasesBtn = document.getElementById('shared-cases-btn');

if (myPatientsBtn) {
    myPatientsBtn.addEventListener('click', function() {
        console.log("My Patients button clicked.");
        window.location.href = `{{ route('doctor.patients.view') }}?filter=my-patients`;
    });
}

if (sharedCasesBtn) {
    sharedCasesBtn.addEventListener('click', function() {
        console.log("Shared Cases button clicked.");
        window.location.href = `{{ route('doctor.patients.view') }}?filter=shared-cases`;
    });
}

// Set initial active tab (Basic Information tab) only if a patient is selected
const initialActiveTab = document.querySelector('button[data-tab="basic-information"]');
const initialActivePane = document.getElementById('basic-information-tab');
if (initialActiveTab && initialActivePane && selectedPatientData) {
    initialActiveTab.click(); // This will handle setting the active class and displaying the pane
}

// Handle URL hash for tab selection
const hash = window.location.hash.substring(1); // Remove the '#'
if (hash) {
    const tabButton = document.querySelector(`button[data-tab="${hash}"]`);
    if (tabButton) {
        tabButton.click();
    }
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

// Add Doctor Modal functionality
const openAddDoctorModalBtn = document.getElementById('openAddDoctorModalBtn');
const closeAddDoctorModalBtn = document.getElementById('closeAddDoctorModal');
const addDoctorModal = document.getElementById('addDoctorModal');

if (openPatientNoteModalBtn) {
    openPatientNoteModalBtn.addEventListener('click', function() {
        patientNoteModal.classList.remove('hidden');
    });
}

if (openAddDoctorModalBtn) {
    openAddDoctorModalBtn.addEventListener('click', function() {
        addDoctorModal.classList.remove('hidden');
    });
}

if (closePatientNoteModalBtn) {
    closePatientNoteModalBtn.addEventListener('click', function() {
        patientNoteModal.classList.add('hidden');
    });
}

if (closeAddDoctorModalBtn) {
    closeAddDoctorModalBtn.addEventListener('click', function() {
        addDoctorModal.classList.add('hidden');
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

// Close add doctor modal when clicking outside
if (addDoctorModal) {
    addDoctorModal.addEventListener('click', function(event) {
        if (event.target === addDoctorModal) {
            addDoctorModal.classList.add('hidden');
        }
    });
}

// Handle Cancel Invite button clicks
document.querySelectorAll('.cancel-invite-btn').forEach(button => {
    button.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default button action
        const sharedCaseId = this.dataset.sharedCaseId;

        if (confirm('Are you sure you want to cancel this invitation?')) {
            fetch(`/doctor/shared-cases/${sharedCaseId}/cancel`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert(data.message || 'Invitation cancelled successfully!');
                    window.location.reload(); // Reload the page to reflect changes
                } else {
                    alert('Error: ' + (data.message || 'Could not cancel invitation.'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while cancelling the invitation.');
            });
        }
    });
});

// Handle Remove Doctor button clicks for accepted shared cases
document.querySelectorAll('.remove-doctor-btn').forEach(button => {
    button.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default button action
        const sharedCaseId = this.dataset.sharedCaseId;

        if (confirm('Are you sure you want to remove this doctor from the shared case?')) {
            fetch(`/doctor/shared-cases/${sharedCaseId}/remove`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert(data.message || 'Doctor removed successfully!');
                    window.location.reload(); // Reload the page to reflect changes
                } else {
                    alert('Error: ' + (data.message || 'Could not remove doctor.'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while removing the doctor.');
            });
        }
    });
});

// Handle Remove from List button clicks for rejected invitations
document.querySelectorAll('.remove-rejected-btn').forEach(button => {
    button.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default button action
        const sharedCaseId = this.dataset.sharedCaseId;

        if (confirm('Are you sure you want to remove this rejected invitation from the list?')) {
            fetch(`/doctor/shared-cases/${sharedCaseId}/remove-rejected`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert(data.message || 'Rejected invitation removed successfully!');
                    window.location.reload(); // Reload the page to reflect changes
                } else {
                    alert('Error: ' + (data.message || 'Could not remove rejected invitation.'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while removing the rejected invitation.');
            });
        }
    });
});
});

 
</script>
@endpush
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

<!-- Add Doctor Modal -->
<div id="addDoctorModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
<div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
<div class="flex justify-end">
    <button id="closeAddDoctorModal" class="text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>
</div>
@if(isset($selectedPatient))
    @include('doctor.components.add-doctor-form', ['selectedPatient' => $selectedPatient])
@endif
</div>
</div>


@endsection

 