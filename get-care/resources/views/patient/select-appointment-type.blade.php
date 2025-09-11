@extends('patient_layout')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class=" font-bold mb-6 text-center">Select Appointment</h2>
        <p class="text-center text-gray-600 mb-8">
            <span class="font-semibold">Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</span> 
        </p>


        <div class="flex justify-between mt-8 move-here-container" id="move-here">
            <div id="selectedChoiceDisplay" class="hidden bg-gray-100 p-4 rounded-lg w-full">
                <p class="text-gray-700 font-semibold mb-2">Selected Appointment:</p>
                <p id="selectedAppointmentTypeText" class="text-lg text-gray-800"></p>
                <div id="selectedClinicDetails" class="mt-2 hidden">
                    <p class="text-gray-600 font-medium">Clinic:</p>
                    <p id="selectedClinicName" class="text-md text-gray-700"></p>
                    <p id="selectedClinicAddress" class="text-sm text-gray-500"></p>
                </div>
            </div>
        </div>

        <br/>

        <form action="{{ route('patient.show-date-time-selection') }}" method="GET">
            <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Online Consultation Card -->
                <div class="appointment-type-card bg-white border border-gray-200 rounded-lg shadow-sm p-6 cursor-pointer hover:shadow-md transition-shadow duration-200" data-type="online">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 flex items-center justify-center bg-green-500 text-white rounded-full text-lg font-bold mr-4">
                            <i class="fas fa-globe"></i> {{-- Placeholder icon for online --}}
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Online Consultation</h3>
                            <p class="text-gray-500 text-sm">Consult via video call (e.g., Google Meet)</p>
                        </div>
                    </div>
                </div>
                <!-- In-Clinic Consultation Card -->
                <div class="appointment-type-card bg-white border border-gray-200 rounded-lg shadow-sm p-6 cursor-pointer hover:shadow-md transition-shadow duration-200" data-type="clinic">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 flex items-center justify-center bg-purple-500 text-white rounded-full text-lg font-bold mr-4">
                            <i class="fas fa-clinic-medical"></i> {{-- Placeholder icon for clinic --}}
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">In-Clinic Consultation</h3>
                            <p class="text-gray-500 text-sm">Visit the doctor at their clinic location</p>
                        </div>
                    </div>
                </div>
            </div>

        <!-- Google OAuth Login Section (Initially hidden) -->
        @if (!Auth::user()->patient->google_access_token)
        <div id="googleAuthSection" class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg text-center hidden">
            <p class="text-sm text-gray-700 mb-4">
                To enable automatic Google Meet link generation for online consultations, please link your Google account.
            </p>
            <a href="{{ route('google.auth.redirect') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="w-5 h-5 mr-2" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M44.5 20H24V28.5H35.8C35.2 31.8 33.1 34.6 30.1 36.5L30 36.4L37.1 41.9L37.2 41.9C41.7 37.9 44.5 31.9 44.5 24C44.5 22.6 44.3 21.3 44.0 20Z" fill="#4285F4"/>
                    <path d="M24 45C30.5 45 36 42.8 39.8 39.1L30 31.4C27.3 33.3 23.9 34.5 20 34.5C13.2 34.5 7.6 30 5.5 23.6L5.4 23.9L-0.5 19.3L-0.3 19.4C1.9 28.5 12.1 34.5 24 34.5Z" fill="#34A853"/>
                    <path d="M10.2 27.6C9.6 25.8 9.2 23.9 9.2 22C9.2 20.1 9.6 18.2 10.2 16.4L10.1 16.1L3.9 11.3L3.8 11.4C1.8 15.4 0.6 19.6 0.6 24C0.6 28.4 1.8 32.6 3.8 36.6L10.2 27.6Z" fill="#FBBC04"/>
                    <path d="M24 9.5C27.5 9.5 30.5 10.8 32.8 13.1L37.3 8.6C34.5 5.9 30.5 4.5 24 4.5C12.1 4.5 1.9 10.5 0.3 19.4L6.6 24.3C7.6 17.9 13.2 13.5 20 13.5C21.7 13.5 23.3 13.8 24 14.2V9.5Z" fill="#EA4335"/>
                </svg>
                Login with Google
            </a>
        </div>
        @endif

        <!-- Clinic Cards Container - Initially hidden -->
        <div id="clinicSelection" class="mt-4 hidden">
            <h4 class="text-md font-semibold text-gray-700 mb-3">Select Clinic Location:</h4>
            <div id="clinicCardsContainer" class="grid grid-cols-1 gap-4">
                @foreach ($clinics as $doctorClinic)
                    @php
                        $clinic = $doctorClinic;
                    @endphp
                    <div class="clinic-card border border-gray-300 rounded-lg p-4 cursor-pointer hover:shadow-md transition-shadow duration-200"
                        data-clinic-id="{{ $clinic->id }}"
                        data-clinic-name="{{ $clinic->name }}"
                        data-clinic-address="{{ $clinic->address }}">
                        <h5 class="font-semibold text-gray-800">{{ $clinic->name }}</h5>
                        <p class="text-sm text-gray-600">{{ $clinic->address }}</p>
                        @if($clinic->opening_time && $clinic->closing_time)
                            <p class="text-xs text-gray-500 mt-1">
                                {{ \Carbon\Carbon::parse($clinic->opening_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($clinic->closing_time)->format('g:i A') }}
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        
            {{-- Hidden input to store selected clinic ID --}}
        <input type="hidden" name="clinic_id" id="clinic_id">
    </div>


<input type="hidden" name="appointment_type" id="appointmentTypeInput">

<div class="flex justify-between mt-8">
    <button type="button" onclick="window.history.back()" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
        Back
    </button>
    <button type="submit" id="nextButton" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50" disabled>
        Next
    </button>
</div>

            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        const hasGoogleAccessToken = {{ Auth::user()->patient->google_access_token ? 'true' : 'false' }};

        const typeCards = document.querySelectorAll('.appointment-type-card');
        const appointmentTypeInput = document.getElementById('appointmentTypeInput');
        const googleAuthSection = document.getElementById('googleAuthSection');
        const clinicSelectionDiv = document.getElementById('clinicSelection');
        const clinicCards = document.querySelectorAll('.clinic-card');
        const clinicIdInput = document.getElementById('clinic_id');
        const nextButton = document.getElementById('nextButton');
        const selectedChoiceDisplay = document.getElementById('selectedChoiceDisplay');
        const selectedAppointmentTypeText = document.getElementById('selectedAppointmentTypeText');
        const selectedClinicDetails = document.getElementById('selectedClinicDetails');
        const selectedClinicName = document.getElementById('selectedClinicName');
        const selectedClinicAddress = document.getElementById('selectedClinicAddress');

        let selectedType = null;
        let selectedClinicId = null;

        function updateSelectedChoiceDisplay() {
            console.log(selectedType);
            if (selectedChoiceDisplay && selectedAppointmentTypeText && selectedClinicDetails && selectedClinicName && selectedClinicAddress) {
                if (selectedType) { // Always update the text content if a type is selected
                    let appointmentTypeText = '';
                    let clinicNameText = '';
                    let clinicAddressText = '';

                    if (selectedType === 'online') {
                        appointmentTypeText = 'Online Consultation';
                        selectedClinicDetails.classList.add('hidden');
                    } else if (selectedType === 'clinic') {
                        appointmentTypeText = 'In-Clinic Consultation';
                        if (selectedClinicId) {
                            const clinicCard = document.querySelector(`.clinic-card[data-clinic-id="${selectedClinicId}"]`);
                            if (clinicCard) {
                                clinicNameText = clinicCard.dataset.clinicName;
                                clinicAddressText = clinicCard.dataset.clinicAddress;
                                selectedClinicDetails.classList.remove('hidden');
                            }
                        } else {
                            selectedClinicDetails.classList.add('hidden');
                        }
                    }
                    selectedAppointmentTypeText.textContent = appointmentTypeText;
                    selectedClinicName.textContent = clinicNameText;
                    selectedClinicAddress.textContent = clinicAddressText;
                } else { // If nothing selected, hide the display
                    selectedChoiceDisplay.classList.add('hidden');
                }
            }
        }

        typeCards.forEach(card => {
            card.addEventListener('click', function() {
                typeCards.forEach(tc => tc.classList.remove('border-blue-500', 'ring-2', 'ring-blue-500'));
                this.classList.add('border-blue-500', 'ring-2', 'ring-blue-500');

                selectedType = this.dataset.type;
                appointmentTypeInput.value = selectedType;

                const moveHereDiv = document.getElementById('move-here');
                if (!moveHereDiv) return; // Defensive check
    
                // Clear previous content in move-here div except for selectedChoiceDisplay
                Array.from(moveHereDiv.children).forEach(child => {
                    if (child !== selectedChoiceDisplay) {
                        child.remove();
                    }
                });

                // Show selected choice display
                selectedChoiceDisplay.classList.remove('hidden');

                if (selectedType === 'clinic') {
                    // Show clinic selection
                    if (clinicSelectionDiv) {
                        clinicSelectionDiv.classList.remove('hidden');
                    }
                    // Hide Google Auth section
                    if (googleAuthSection) {
                        googleAuthSection.classList.add('hidden');
                    }
                    // Retain selected clinic if previously selected for clinic type
                    if (selectedClinicId) {
                        const previouslySelectedClinicCard = document.querySelector(`.clinic-card[data-clinic-id="${selectedClinicId}"]`);
                        if (previouslySelectedClinicCard) {
                            previouslySelectedClinicCard.classList.add('border-blue-500', 'ring-2', 'ring-blue-500');
                        }
                    }
                } else if (selectedType === 'online') {
                    // Hide clinic selection
                    if (clinicSelectionDiv) {
                        clinicSelectionDiv.classList.add('hidden');
                    }
                    // Reset clinic selection
                    selectedClinicId = null;
                    clinicIdInput.value = '';
                    clinicCards.forEach(cc => cc.classList.remove('border-blue-500', 'ring-2', 'ring-blue-500'));
                    
                    // Show Google Auth section if user doesn't have token
                    if (googleAuthSection && !hasGoogleAccessToken) {
                        googleAuthSection.classList.remove('hidden');
                    }
                }
               updateSelectedChoiceDisplay();
               updateNextButtonState();
            });
        });

        clinicCards.forEach(card => {
            card.addEventListener('click', function() {
                clinicCards.forEach(cc => cc.classList.remove('border-blue-500', 'ring-2', 'ring-blue-500'));
                this.classList.add('border-blue-500', 'ring-2', 'ring-blue-500');
                selectedClinicId = this.dataset.clinicId;
                clinicIdInput.value = selectedClinicId;
                updateSelectedChoiceDisplay();
                updateNextButtonState();
            });
        });
        function updateNextButtonState() {
            if (selectedType === 'clinic') {
                nextButton.disabled = !selectedClinicId;
            } else if (selectedType === 'online') {
                nextButton.disabled = false;
            } else {
                nextButton.disabled = true;
            }
        }

        // Initial state update
        updateNextButtonState();
        updateSelectedChoiceDisplay();
    });
</script>
@endpush