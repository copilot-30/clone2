<div class="p-4 bg-gray-50 rounded-lg shadow-sm border border-gray-200">
    <h3 class="text-xl font-bold text-gray-800 mb-4">Book New Appointment</h3>
    <form action="{{ route('doctor.appointments.store') }}" method="POST">
        @csrf
        <input type="hidden" name="patient_id" value="{{ $selectedPatient->id }}">
        
        <div class="mb-4">
            <label for="type" class="block text-sm font-medium text-gray-700">Appointment Type</label>
            <select name="type" id="appointment_type" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500" required>
                <option value="">Select Type</option>
                <option value="online">Online</option>
                <option value="clinic">Clinic</option>
            </select>
        </div>
        <div class="mb-4 hidden" id="google_link_message_container">
            <p class="text-red-600">Google account not linked. <a href="{{ route('google.auth.redirect') }}" class="text-blue-600 hover:underline">Link your Google account</a> to book online appointments.</p>
        </div>
        <div class="mb-4 hidden" id="clinic_selection_container">
            <label for="clinic_id" class="block text-sm font-medium text-gray-700">Clinic</label>
            <select name="clinic_id" id="clinic_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500">
                <option value="">Select Clinic</option>
                {{-- Clinics should be passed from the controller --}}
                @foreach ($clinics as $clinic)
                    <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="mb-4">
            <label for="subtype" class="block text-sm font-medium text-gray-700">Subtype</label>
            <select name="subtype" id="subtype" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500">
                <option value="">Select Subtype</option>
                <option value="consultation">Consultation</option>
                <option value="follow-up">Follow-up</option>
            </select>
        </div>

                <div class="mb-4 hidden" id="soap_note_id_container">
            <label for="soap_note_id" class="block text-sm font-medium text-gray-700">Select SOAP Note (Chief Complaint)</label>
            <select name="soap_note_id" id="soap_note_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500">
                <option value="">Select SOAP Note</option>
                @if(isset($selectedPatient) && $selectedPatient->soapNotes->isNotEmpty())
                    @foreach ($selectedPatient->soapNotes()->orderBy('date', 'desc')->get() as $soapNote)
                        <option value="{{ $soapNote->id }}">CC: {{ $soapNote->chief_complaint }} ({{ $soapNote->date->format('M d, Y') }})</option>
                    @endforeach
                @else
                    <option value="" disabled>No SOAP notes available</option>
                @endif
            </select>
        </div>


        <div class="mb-4">
            <label for="appointment_datetime" class="block text-sm font-medium text-gray-700">Date and Time</label>
            <input type="datetime-local" name="appointment_datetime" id="appointment_datetime" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500" required>
        </div>

        <div class="mb-4">
            <label for="duration_minutes" class="block text-sm font-medium text-gray-700">Duration (minutes)</label>
            <input type="number" name="duration_minutes" id="duration_minutes" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500" min="1" value="30" required>
        </div>
 
        <div class="mb-4">
            <label for="chief_complaint" class="block text-sm font-medium text-gray-700">Chief Complaint</label>
            <textarea name="chief_complaint" id="chief_complaint" rows="3" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500"></textarea>
        </div>

        <div class="mb-4">
            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
            <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500"></textarea>
        </div>




        <button type="submit" class="w-full bg-emerald-600 text-white p-2 rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">Book Appointment</button>
    </form>
</div>