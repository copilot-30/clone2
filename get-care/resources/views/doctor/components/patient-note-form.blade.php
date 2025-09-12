<div class="p-4 bg-gray-50 rounded-lg shadow-sm border border-gray-200">
    <h3 class="text-xl font-bold text-gray-800 mb-4">Add New Patient Note</h3>
    <form action="{{ route('doctor.patient-notes.store') }}" method="POST">
        @csrf
        <input type="hidden" name="patient_id" value="{{ $selectedPatient->id }}">
        <div class="mb-4">
            <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
            <input type="text" name="subject" id="subject" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500" required>
        </div>
        <div class="mb-4">
            <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
            <textarea name="content" id="content" rows="5" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500" required></textarea>
        </div>
        <div class="mb-4">
            <label for="note_type" class="block text-sm font-medium text-gray-700">Note Type</label>
            <select name="note_type" id="note_type" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500">
                <option value="general">General</option>
                <option value="progress">Progress</option>
                <option value="assessment">Assessment</option>
                <option value="plan">Plan</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="visibility" class="block text-sm font-medium text-gray-700">Visibility</label>
            <select name="visibility" id="visibility" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500">
                <option value="private">Private (Only Doctor)</option>
                <option value="shared">Shared (Doctor and Patient)</option>
            </select>
        </div>
        <button type="submit" class="w-full bg-emerald-600 text-white p-2 rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">Add Note</button>
    </form>
</div>