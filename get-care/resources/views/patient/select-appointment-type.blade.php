@extends('admin_layout')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-6 text-center">Select Appointment Type</h2>
        <p class="text-center text-gray-600 mb-8">
            <span class="font-semibold">Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</span> - Online Consultation via Google Meet
        </p>

        <form action="{{ route('patient.show-date-time-selection') }}" method="GET">
            @csrf
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
                    <!-- Clinic Selection - Initially hidden -->
                    <div id="clinicSelection" class="mt-4 hidden">
                        <label for="clinic_id" class="block text-sm font-medium text-gray-700">Select Clinic:</label>
                        <select name="clinic_id" id="clinic_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="">-- Select a Clinic --</option>
                            @foreach ($doctor->doctorClinics as $clinic)
                                <option value="{{ $clinic->clinic_id }}">{{ $clinic->clinic->name }} - {{ $clinic->clinic->address }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <input type="hidden" name="appointment_type" id="appointmentTypeInput">

            <div class="flex justify-between mt-8">
                <a href="{{ route('patient.select-doctor') }}" class="bg-gray-300 text-gray-800 px-6 py-2 rounded-lg font-semibold hover:bg-gray-400 focus:outline-none">Previous</a>
                <button type="submit" id="nextButton" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 focus:outline-none" disabled>Next</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeCards = document.querySelectorAll('.appointment-type-card');
        const appointmentTypeInput = document.getElementById('appointmentTypeInput');
        const clinicSelectionDiv = document.getElementById('clinicSelection');
        const clinicSelect = document.getElementById('clinic_id');
        const nextButton = document.getElementById('nextButton');

        let selectedType = null;

        typeCards.forEach(card => {
            card.addEventListener('click', function() {
                typeCards.forEach(tc => tc.classList.remove('border-blue-500', 'ring-2', 'ring-blue-500'));
                this.classList.add('border-blue-500', 'ring-2', 'ring-blue-500');
                
                selectedType = this.dataset.type;
                appointmentTypeInput.value = selectedType;

                if (selectedType === 'clinic') {
                    clinicSelectionDiv.classList.remove('hidden');
                } else {
                    clinicSelectionDiv.classList.add('hidden');
                    clinicSelect.value = ''; // Reset clinic selection for online
                }
                updateNextButtonState();
            });
        });

        clinicSelect.addEventListener('change', updateNextButtonState);

        function updateNextButtonState() {
            if (selectedType === 'clinic') {
                nextButton.disabled = !clinicSelect.value;
            } else if (selectedType === 'online') {
                nextButton.disabled = false;
            } else {
                nextButton.disabled = true;
            }
        }
    });
</script>
@endpush