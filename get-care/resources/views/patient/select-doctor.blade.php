@extends('patient_layout')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-6 text-center">Select Your Doctor</h2>
        <p class="text-center text-gray-600 mb-8">Choose from your assigned doctors or search for a specialist</p>

        <!-- Search Bar -->
        <div class="mb-6 flex items-center border border-gray-300 rounded-lg shadow-sm overflow-hidden">
            <input type="text" placeholder="Search doctors by name or specialty..." class="w-full px-4 py-2 text-gray-700 focus:outline-none" id="doctorSearch">
            <div class="p-2">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>

        <!-- Filter Buttons -->
        <div class="flex flex-wrap justify-center gap-2 mb-8">
            <button class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-700 focus:outline-none">All Doctors</button>
            <!-- Example filter buttons - dynamically generate from specialties later -->
            <button class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-300 focus:outline-none">Cardiology</button>
            <button class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-300 focus:outline-none">Neurology</button>
            <button class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-300 focus:outline-none">Orthopedics</button>
            <button class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-300 focus:outline-none">Pediatrics</button>
            <button class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-300 focus:outline-none">Dermatology</button>
            <button class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-300 focus:outline-none">Psychiatry</button>
        </div>

         <div class="flex items-center justify-center">
                @if (count($doctors) == 0)
                    <p class="text-gray-600 text-center">No doctors found.</p>
                @endif
            </div>

        <!-- Doctor List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
           


            @foreach ($doctors as $doctor)
                <div class="doctor-card bg-white border border-gray-200 rounded-lg shadow-sm p-4 cursor-pointer hover:shadow-md transition-shadow duration-200" data-doctor-id="{{ $doctor->id }}">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 flex items-center justify-center bg-blue-500 text-white rounded-full text-lg font-bold mr-4">
                            {{ substr($doctor->first_name, 0, 1) }}{{ substr($doctor->last_name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</h3>
                            <p class="text-gray-500 text-sm">{{ $doctor->specialization ?? 'General Practitioner' }}</p>
                        </div>
                    </div>
                    <div class="text-gray-600 text-sm mb-2">
                        <p class="flex items-center mb-1">
                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 16v.01M6 16v.01M12 16v.01M6 10h.01M12 10h.01M16 10h.01M6 14h.01M12 14h.01M16 14h.01"></path></svg>
                            {{ $doctor->years_of_experience ?? 'N/A' }} years experience
                        </p>
                        <p class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ $doctor->doctorClinics->count() }} clinics available
                        </p>
                    </div>
                    {{-- Reviews - Placeholder for now --}}
                    {{-- <div class="flex items-center text-yellow-500 text-sm">
                        ★★★★☆ 4.9 ({{ $doctor->reviews->count() }} reviews)
                    </div> --}}
                </div>
            @endforeach
        </div>

        <!-- Pagination/Navigation (Next/Cancel buttons) -->
        <form id="selectDoctorForm" method="POST" action="{{ route('patient.storeAttendingPhysician') }}">
            @csrf
            <input type="hidden" name="doctor_id" id="selectedDoctorId">
            <div class="flex justify-between mt-8">
                <button type="button" class="bg-gray-300 text-gray-800 px-6 py-2 rounded-lg font-semibold hover:bg-gray-400 focus:outline-none">Cancel</button>
                <button type="submit" id="nextButton" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 focus:outline-none" disabled>Next</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const doctorCards = document.querySelectorAll('.doctor-card');
        const selectedDoctorIdInput = document.getElementById('selectedDoctorId');
        const selectDoctorForm = document.getElementById('selectDoctorForm');
        const nextButton = document.getElementById('nextButton');

        doctorCards.forEach(card => {
            card.addEventListener('click', function() {
                doctorCards.forEach(dc => dc.classList.remove('border-blue-500', 'ring-2', 'ring-blue-500'));
                this.classList.add('border-blue-500', 'ring-2', 'ring-blue-500');
                selectedDoctorIdInput.value = this.dataset.doctorId;
                nextButton.disabled = false;
            });
        });
        // No explicit click listener on nextButton needed here,
        // as it's a submit button for the form.

    });
</script>
@endpush