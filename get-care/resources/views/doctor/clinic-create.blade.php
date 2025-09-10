@extends('admin_layout')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 p-4">
    <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-5xl">
        <h2 class="text-3xl font-bold text-emerald-600 mb-8 text-center">Create New Clinic</h2>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('doctor.clinics.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @csrf

            <!-- Left Column -->
            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Clinic Name</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2"
                        value="{{ old('name') }}"
                        required
                    />
                </div>
                <div>
                    <label for="address" class="block text-gray-700 text-sm font-bold mb-2">Address</label>
                    <input
                        type="text"
                        id="address"
                        name="address"
                        class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2"
                        value="{{ old('address') }}"
                        required
                    />
                </div>
                <div>
                    <label for="city" class="block text-gray-700 text-sm font-bold mb-2">City</label>
                    <input
                        type="text"
                        id="city"
                        name="city"
                        class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2"
                        value="{{ old('city') }}"
                        required
                    />
                </div>
                <div>
                    <label for="state" class="block text-gray-700 text-sm font-bold mb-2">State</label>
                    <input
                        type="text"
                        id="state"
                        name="state"
                        class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2"
                        value="{{ old('state') }}"
                        required
                    />
                </div>
                <div>
                    <label for="postal_code" class="block text-gray-700 text-sm font-bold mb-2">Postal Code</label>
                    <input
                        type="text"
                        id="postal_code"
                        name="postal_code"
                        class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2"
                        value="{{ old('postal_code') }}"
                        required
                    />
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <div>
                    <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Phone</label>
                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2"
                        value="{{ old('phone') }}"
                    />
                </div>
                <div>
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2"
                        value="{{ old('email') }}"
                    />
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Operating Hours</label>
                    <div id="operating_hours_container" class="space-y-2">
                        @php
                            $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                            $oldOperatingHours = json_decode(old('operating_hours', '{}'), true);
                        @endphp
                        @foreach($daysOfWeek as $day)
                            <div class="flex items-center space-x-2">
                                <label class="w-1/4 text-gray-600">{{ $day }}</label>
                                <input type="time" name="operating_hours[{{ $day }}][start]" class="w-1/3 border p-2 rounded" value="{{ $oldOperatingHours[$day]['start'] ?? '' }}">
                                <span class="text-gray-500">-</span>
                                <input type="time" name="operating_hours[{{ $day }}][end]" class="w-1/3 border p-2 rounded" value="{{ $oldOperatingHours[$day]['end'] ?? '' }}">
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Facilities</label>
                    <div id="facilities_container" class="space-y-2">
                        @php
                            $oldFacilities = old('facilities', []);
                            if (!is_array($oldFacilities)) {
                                $oldFacilities = json_decode($oldFacilities, true) ?? [];
                            }
                        @endphp
                        @foreach($oldFacilities as $facility)
                            <div class="flex items-center space-x-2 facility-item">
                                <input type="text" name="facilities[]" class="w-full border p-2 rounded" value="{{ $facility }}">
                                <button type="button" class="remove-facility-btn bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded text-sm">Remove</button>
                            </div>
                        @endforeach
                        <button type="button" id="add_facility_btn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded text-sm mt-2">Add Facility</button>
                    </div>
                </div>
                <div class="flex items-center">
                    <input
                        type="checkbox"
                        id="is_active"
                        name="is_active"
                        value="1"
                        class="form-checkbox h-5 w-5 text-emerald-600"
                        {{ old('is_active') ? 'checked' : '' }}
                    />
                    <label for="is_active" class="ml-2 block text-gray-700 text-sm font-bold">Is Active</label>
                </div>
                <div class="flex justify-end mt-8">
                    <button
                        type="submit"
                        class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 px-8 rounded-full shadow-lg transition-all duration-300"
                    >
                        Create Clinic
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Operating Hours - Not dynamic, just improved input fields
        
        // Facilities
        const addFacilityBtn = document.getElementById('add_facility_btn');
        const facilitiesContainer = document.getElementById('facilities_container');

        addFacilityBtn.addEventListener('click', function () {
            const newFacilityDiv = document.createElement('div');
            newFacilityDiv.classList.add('flex', 'items-center', 'space-x-2', 'facility-item');
            newFacilityDiv.innerHTML = `
                <input type="text" name="facilities[]" class="w-full border p-2 rounded" value="">
                <button type="button" class="remove-facility-btn bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded text-sm">Remove</button>
            `;
            facilitiesContainer.appendChild(newFacilityDiv); // Changed from insertBefore to appendChild
        });

        facilitiesContainer.addEventListener('click', function (event) {
            if (event.target.classList.contains('remove-facility-btn')) {
                event.target.closest('.facility-item').remove();
            }
        });
    });
</script>
@endpush