<div class="p-4 bg-gray-50 rounded-lg shadow-sm border border-gray-200">
    <h3 class="text-xl font-bold text-gray-800 mb-4">Book New Appointment</h3>
    <form action="{{ route('doctor.appointments.store') }}" method="POST">
        @csrf
        <input type="hidden" name="patient_id" value="{{ $selectedPatient->id }}">
        <div class="mb-4">
            <label for="appointment_date" class="block text-sm font-medium text-gray-700">Date</label>
            <input type="date" name="appointment_date" id="appointment_date" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500" required>
        </div>
        <div class="mb-4">
            <label for="appointment_time" class="block text-sm font-medium text-gray-700">Time</label>
            <input type="time" name="appointment_time" id="appointment_time" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500" required>
        </div>
        <div class="mb-4">
            <label for="reason" class="block text-sm font-medium text-gray-700">Reason</label>
            <textarea name="reason" id="reason" rows="3" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500"></textarea>
        </div>
        <button type="submit" class="w-full bg-emerald-600 text-white p-2 rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">Book Appointment</button>
    </form>
</div>