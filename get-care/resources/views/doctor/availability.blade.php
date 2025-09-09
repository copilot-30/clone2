@extends('admin_layout')

@section('content')
<div class="p-4">
    <div class="bg-white p-6 rounded-lg shadow-xl">
        <h2 class="text-2xl font-bold text-emerald-600 mb-6">Set Your Online Appointment Availability</h2>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('doctor.availability.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-6 flex space-x-4">
                <label class="inline-flex items-center">
                    <input type="radio" name="status" value="1" class="form-radio h-5 w-5 text-emerald-600" {{ $availability_status ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">ENABLED</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="status" value="0" class="form-radio h-5 w-5 text-gray-600" {{ !$availability_status ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">DISABLED</span>
                </label>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Days Available:</label>
                <div class="grid grid-cols-7 gap-4 text-center">
                    @php
                        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                        $shortDays = ['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN'];
                    @endphp
                    @foreach ($daysOfWeek as $index => $day)
                        <div class="flex flex-col items-center">
                            <span class="text-sm font-medium text-gray-700">{{ $shortDays[$index] }}</span>
                            <input type="checkbox" name="days_enabled[]" value="{{ $day }}" class="form-checkbox h-5 w-5 text-emerald-600 mt-2"
                                {{ count($organizedAvailability[$day]) > 0 || old('days_enabled.' . $day) ? 'checked' : '' }}>
                        </div>
                    @endforeach
                </div>
            </div>

            <div id="availability-slots-container">
                @php $uniqueSlotIndex = 0; @endphp
                @foreach ($daysOfWeek as $day)
                    <div class="mb-6 p-4 border rounded-lg bg-gray-50 day-slot-group" data-day="{{ $day }}">
                        <h4 class="text-lg font-semibold text-gray-800 mb-3">{{ $day }}</h4>
                        @php
                            $hasSlotsForDay = false;
                        @endphp
                        @foreach (($organizedAvailability[$day] ?? []) as $slot)
                            @include('components.availability-slot', ['day' => $day, 'uniqueSlotIndex' => $uniqueSlotIndex++, 'slot' => $slot])
                            @php $hasSlotsForDay = true; @endphp
                        @endforeach
                        
                        @if (!$hasSlotsForDay)
                            {{-- Initial empty slot for days without existing data --}}
                            @include('components.availability-slot', ['day' => $day, 'uniqueSlotIndex' => $uniqueSlotIndex++, 'slot' => ['start_time' => '09:00', 'end_time' => '17:00', 'is_available' => true, 'id' => null]])
                        @endif

                        <button type="button" class="add-new-slot-btn mt-3 bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2 px-4 rounded">
                            Add New
                        </button>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Save Availability
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const availabilitySlotsContainer = document.getElementById('availability-slots-container');
        // Initialize globalSlotIndex based on the maximum uniqueSlotIndex already rendered
        // This ensures new slots get a unique, incrementing index.
        let globalSlotIndex = {{ $uniqueSlotIndex }};

        // Function to create a new slot HTML
        function createNewSlotHtml(day, slot = { start_time: '09:00', end_time: '17:00', is_available: true, id: null }) {
            const currentUniqueIndex = globalSlotIndex++;
            return `
                <div class="flex items-center space-x-4 mb-3 availability-slot" data-slot-id="${slot.id || ''}">
                    <input type="hidden" name="availability[${currentUniqueIndex}][day_of_week]" value="${day}">
                    <input type="hidden" name="availability[${currentUniqueIndex}][id]" value="${slot.id || ''}">

                    <div class="w-1/4">
                        <label for="start_time_${day}_${currentUniqueIndex}" class="block text-gray-700 text-xs font-bold mb-1">Start Time</label>
                        <input type="time" name="availability[${currentUniqueIndex}][start_time]"
                               id="start_time_${day}_${currentUniqueIndex}"
                               class="w-full border p-2 rounded"
                               value="${slot.start_time}" required>
                    </div>
                    <div class="w-1/4">
                        <label for="end_time_${day}_${currentUniqueIndex}" class="block text-gray-700 text-xs font-bold mb-1">End Time</label>
                        <input type="time" name="availability[${currentUniqueIndex}][end_time]"
                               id="end_time_${day}_${currentUniqueIndex}"
                               class="w-full border p-2 rounded"
                               value="${slot.end_time}" required>
                    </div>
                    <div class="w-1/4">
                        <label for="is_available_${day}_${currentUniqueIndex}" class="block text-gray-700 text-xs font-bold mb-1">Available?</label>
                        <input type="checkbox" name="availability[${currentUniqueIndex}][is_available]"
                               id="is_available_${day}_${currentUniqueIndex}"
                               class="form-checkbox h-5 w-5 text-emerald-600 mt-2"
                               value="1" ${slot.is_available ? 'checked' : ''}>
                    </div>
                    <div class="w-1/4 flex justify-end items-end">
                        <button type="button" class="remove-slot-btn bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-3 rounded text-sm">
                            Remove
                        </button>
                    </div>
                </div>
            `;
        }

        // Add new slot button handler
        availabilitySlotsContainer.addEventListener('click', function (event) {
            if (event.target.classList.contains('add-new-slot-btn')) {
                const daySlotGroup = event.target.closest('.day-slot-group');
                const day = daySlotGroup.dataset.day;
                const newSlotHtml = createNewSlotHtml(day);
                daySlotGroup.querySelector('h4').insertAdjacentHTML('afterend', newSlotHtml); // Insert after the h4 for the day
            }
        });

        // Remove slot button handler
        availabilitySlotsContainer.addEventListener('click', function (event) {
            if (event.target.classList.contains('remove-slot-btn')) {
                event.target.closest('.availability-slot').remove();
            }
        });

        // Toggle day availability based on checkbox
        document.querySelectorAll('input[name="days_enabled[]"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const day = this.value;
                const daySlotGroup = document.querySelector(`.day-slot-group[data-day="${day}"]`);
                if (daySlotGroup) {
                    if (this.checked) {
                        daySlotGroup.style.display = 'block';
                        // Ensure there's at least one slot if enabling a day and none exist
                        if (daySlotGroup.querySelectorAll('.availability-slot').length === 0) {
                            const newSlotHtml = createNewSlotHtml(day);
                            daySlotGroup.querySelector('h4').insertAdjacentHTML('afterend', newSlotHtml);
                        }
                    } else {
                        daySlotGroup.style.display = 'none';
                    }
                }
            });

            // Initial state based on whether the day checkbox is checked
            const day = checkbox.value;
            const daySlotGroup = document.querySelector(`.day-slot-group[data-day="${day}"]`);
            if (daySlotGroup && !checkbox.checked) {
                daySlotGroup.style.display = 'none';
            }
        });
            });
</script>
@endsection