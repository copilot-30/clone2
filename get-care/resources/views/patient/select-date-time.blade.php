@extends('patient_layout')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-6 text-center">Select Date & Time</h2>
        <p class="text-center text-gray-600 mb-8">
            <span class="font-semibold">Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</span> - {{ ucfirst($appointmentType) }} Consultation
        </p>

        <form action="{{ route('patient.store-appointment') }}" method="POST">
            @csrf
            <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
            <input type="hidden" name="appointment_type" value="{{ $appointmentType }}">
            <input type="hidden" name="appointment_subtype" value="{{ $appointmentSubtype }}"> {{-- New: Add this line --}}
            @if($clinic)
                <input type="hidden" name="clinic_id" value="{{ $clinic->id }}">
            @endif
            <input type="hidden" name="appointment_datetime" id="selectedAppointmentDateTime">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Calendar Section -->
                <div>
                    <h3 class="text-xl font-semibold mb-4">Select Date</h3>
                    <div class="bg-gray-50 p-4 rounded-lg shadow-inner">
                        <div class="flex justify-between items-center mb-4">
                            <button type="button" id="prevMonth" class="px-3 py-1 rounded-lg bg-gray-200 hover:bg-gray-300 focus:outline-none"><</button>
                            <span id="currentMonthYear" class="font-bold text-lg"></span>
                            <button type="button" id="nextMonth" class="px-3 py-1 rounded-lg bg-gray-200 hover:bg-gray-300 focus:outline-none">></button>
                        </div>
                        <div class="grid grid-cols-7 text-center font-semibold text-gray-600 mb-2">
                            <span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span><span>Sun</span>
                        </div>
                        <div id="calendar" class="grid grid-cols-7 text-center">
                            <!-- Calendar days will be generated here by JS -->
                        </div>
                    </div>
                </div>

                <!-- Available Time Slots Section -->
                <div>
                    <h3 class="text-xl font-semibold mb-4">Available Time Slots</h3>
                    <div id="timeSlotsContainer" class="bg-gray-50 p-4 rounded-lg shadow-inner min-h-[200px]">
                        <p id="noSlotsMessage" class="text-gray-500 text-center py-8">Select a date to see available time slots.</p>
                        <!-- Time slots will be generated here by JS -->
                    </div>
                    <div class="mt-6">
                        <label for="chief_complaint" class="block text-sm font-medium text-gray-700">Chief Complaint (Optional):</label>
                        <textarea name="chief_complaint" id="chief_complaint" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                    </div>
                </div>
            </div>

            <div class="flex justify-between mt-8">
                <a href="{{ route('patient.select-appointment-type', ['doctor_id' => $doctor->id]) }}" class="bg-gray-300 text-gray-800 px-6 py-2 rounded-lg font-semibold hover:bg-gray-400 focus:outline-none">Previous</a>
                <button type="submit" id="confirmAppointmentButton" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 focus:outline-none" disabled>Confirm Appointment</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const slotsData = @json($slots);
        const calendarEl = document.getElementById('calendar');
        const currentMonthYearEl = document.getElementById('currentMonthYear');
        const prevMonthBtn = document.getElementById('prevMonth');
        const nextMonthBtn = document.getElementById('nextMonth');
        const timeSlotsContainer = document.getElementById('timeSlotsContainer');
        const noSlotsMessage = document.getElementById('noSlotsMessage');
        const selectedAppointmentDateTimeInput = document.getElementById('selectedAppointmentDateTime');
        const confirmAppointmentButton = document.getElementById('confirmAppointmentButton');

        let currentMonth = new Date().getMonth();
        let currentYear = new Date().getFullYear();
        let selectedDate = null;
        let selectedTime = null;

        function renderCalendar() {
            calendarEl.innerHTML = '';
            timeSlotsContainer.innerHTML = ''; // Clear time slots
            noSlotsMessage.classList.remove('hidden'); // Show no slots message

            const firstDayOfMonth = new Date(currentYear, currentMonth, 1);
            const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
            const startDay = (firstDayOfMonth.getDay() + 6) % 7; // Adjust to start on Monday

            currentMonthYearEl.textContent = new Date(currentYear, currentMonth).toLocaleString('default', { month: 'long', year: 'numeric' });

            for (let i = 0; i < startDay; i++) {
                const emptyDiv = document.createElement('div');
                calendarEl.appendChild(emptyDiv);
            }

            for (let day = 1; day <= daysInMonth; day++) {
                const date = new Date(currentYear, currentMonth, day);
                const dateString = date.toISOString().slice(0, 10); // YYYY-MM-DD
                const dayDiv = document.createElement('div');
                dayDiv.textContent = day;
                dayDiv.classList.add('p-2', 'cursor-pointer', 'rounded-lg', 'hover:bg-blue-200');

                if (date <= new Date(new Date().setHours(0,0,0,0))) { // Disable today and past dates (1-day lead time)
                    dayDiv.classList.add('text-gray-400', 'cursor-not-allowed');
                } else if (slotsData[dateString] && slotsData[dateString].length > 0) { // Highlight dates with available slots
                    dayDiv.classList.add('font-semibold', 'bg-blue-100');
                    dayDiv.dataset.date = dateString;
                    dayDiv.addEventListener('click', () => selectDate(dateString));
                } else {
                    dayDiv.classList.add('text-gray-500', 'cursor-not-allowed'); // Dates with no slots
                }

                if (selectedDate === dateString) {
                    dayDiv.classList.add('bg-blue-500', 'text-white');
                }

                calendarEl.appendChild(dayDiv);
            }
        }

        function selectDate(dateString) {
            selectedDate = dateString;
            selectedTime = null; // Reset selected time
            updateSelectedDateTimeInput();
            renderCalendar(); // Re-render to highlight selected date
            displayTimeSlots(dateString);
        }

        function displayTimeSlots(dateString) {
            timeSlotsContainer.innerHTML = ''; // Clear previous slots
            if (slotsData[dateString] && slotsData[dateString].length > 0) {
                noSlotsMessage.classList.add('hidden');
                slotsData[dateString].sort().forEach(time => {
                    const timeDiv = document.createElement('button');
                    timeDiv.type = 'button';
                    timeDiv.textContent = time;
                    timeDiv.classList.add('px-3', 'py-1', 'rounded-lg', 'bg-blue-200', 'hover:bg-blue-300', 'm-1');
                    timeDiv.dataset.time = time;
                    timeDiv.addEventListener('click', () => selectTime(time));
                    if (selectedTime === time) {
                        timeDiv.classList.add('bg-blue-500', 'text-white');
                    }
                    timeSlotsContainer.appendChild(timeDiv);
                });
            } else {
                noSlotsMessage.classList.remove('hidden');
            }
        }

        function selectTime(timeString) {
            selectedTime = timeString;
            updateSelectedDateTimeInput();
            displayTimeSlots(selectedDate); // Re-render to highlight selected time
        }

        function updateSelectedDateTimeInput() {
            if (selectedDate && selectedTime) {
                selectedAppointmentDateTimeInput.value = `${selectedDate} ${selectedTime}`;
                confirmAppointmentButton.disabled = false;
            } else {
                selectedAppointmentDateTimeInput.value = '';
                confirmAppointmentButton.disabled = true;
            }
        }

        prevMonthBtn.addEventListener('click', () => {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            renderCalendar();
        });

        nextMonthBtn.addEventListener('click', () => {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            renderCalendar();
        });

        renderCalendar(); // Initial render
    });
</script>
@endpush