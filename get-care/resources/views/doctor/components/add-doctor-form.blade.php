<form method="POST" action="{{ route('doctor.shared-cases.store') }}">
    @csrf
    <input type="hidden" name="patient_id" value="{{ $selectedPatient->id }}">
    <h3 class="text-xl font-bold text-gray-800 mb-4">Share Case with Another Doctor</h3>

    <div class="mb-4">
        <label for="receiving_doctor_search" class="block text-gray-700 text-sm font-bold mb-2">Search Doctor:</label>
        <div class="relative">
            <input type="text" id="receiving_doctor_search" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline pr-10" placeholder="Search by name or email" autocomplete="off">
            <div id="doctor_search_spinner" class="absolute inset-y-0 right-0 pr-3 flex items-center hidden">
                <svg class="animate-spin h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </div>
        <input type="hidden" name="receiving_doctor_id" id="receiving_doctor_id" required>
        <div id="receiving_doctor_results" class="absolute bg-white border border-gray-300 w-full mt-1 max-h-48 overflow-y-auto z-10 hidden"></div>
        @error('receiving_doctor_id')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-4">
        <label for="case_description" class="block text-gray-700 text-sm font-bold mb-2">Case Description:</label>
        <textarea name="case_description" id="case_description" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
        @error('case_description')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-4">
        <label for="urgency" class="block text-gray-700 text-sm font-bold mb-2">Urgency:</label>
        <select name="urgency" id="urgency" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            <option value="high">High</option>
            <option value="medium">Medium</option>
            <option value="low">Low</option>
        </select>
        @error('urgency')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
    </div>
 
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2">Permissions (Select data to share):</label>
        <div class="flex flex-col">
            <label class="inline-flex items-center">
                <input type="checkbox" name="permissions[]" value="medical_background" class="form-checkbox text-emerald-600">
                <span class="ml-2 text-gray-700">Medical Background</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" name="permissions[]" value="soap_notes" class="form-checkbox text-emerald-600">
                <span class="ml-2 text-gray-700">SOAP Notes</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" name="permissions[]" value="patient_notes" class="form-checkbox text-emerald-600">
                <span class="ml-2 text-gray-700">Patient Notes</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" name="permissions[]" value="appointments" class="form-checkbox text-emerald-600">
                <span class="ml-2 text-gray-700">Appointments</span>
            </label>
        </div>
        @error('permissions')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-4">
        <label for="expires_at" class="block text-gray-700 text-sm font-bold mb-2">Expires At (Optional):</label>
        <input type="date" name="expires_at" id="expires_at" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        @error('expires_at')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center justify-between">
        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            Share Case
        </button>
        <button type="button" id="cancelAddDoctorModal" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            Cancel
        </button>
    </div>
</form>

@push('scripts')
<script>
    const addDoctorForm = document.querySelector('#addDoctorModal form');

    if (addDoctorForm) {
        addDoctorForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission for debugging

            const formData = new FormData(addDoctorForm);
            const formParams = {};
            for (let [key, value] of formData.entries()) {
                if (key.endsWith('[]')) { // Handle array inputs like permissions[]
                    const newKey = key.substring(0, key.length - 2);
                    if (!formParams[newKey]) {
                        formParams[newKey] = [];
                    }
                    formParams[newKey].push(value);
                } else {
                    formParams[key] = value;
                }
            }
            console.log('Form Parameters:', formParams); // Keep for debugging

            if (!receivingDoctorId.value) {
                alert('Please select a doctor from the search results.');
                doctorSearchSpinner.classList.add('hidden');
                return;
            }

            // If receiving_doctor_id is set, allow the form to submit
            addDoctorForm.submit();
        });
    }

    document.getElementById('cancelAddDoctorModal').addEventListener('click', function() {
        document.getElementById('addDoctorModal').classList.add('hidden');
    });

    const receivingDoctorSearch = document.getElementById('receiving_doctor_search');
    const receivingDoctorId = document.getElementById('receiving_doctor_id');
    const receivingDoctorResults = document.getElementById('receiving_doctor_results');
    let searchTimeout;

    const doctorSearchSpinner = document.getElementById('doctor_search_spinner');

    receivingDoctorSearch.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value;
        
        // Clear the hidden ID input if the search query changes
        receivingDoctorId.value = '';

        if (query.length==0) { // Revert to original search query length
            receivingDoctorResults.innerHTML = '';
            receivingDoctorResults.classList.add('hidden');
            doctorSearchSpinner.classList.add('hidden'); // Hide spinner if query is too short
            return;
        }

        doctorSearchSpinner.classList.remove('hidden'); // Show spinner

        searchTimeout = setTimeout(() => {
            fetch(`/doctor/search-doctors?query=${query}&patient_id={{ $selectedPatient->id }}`)
                .then(response => response.json())
                .then(doctors => {
                    receivingDoctorResults.innerHTML = '';
                    if (doctors.length > 0) {
                        doctors.forEach(doctor => {
                            const div = document.createElement('div');
                            div.classList.add('p-2', 'cursor-pointer', 'hover:bg-gray-100');
                            div.textContent = `Dr. ${doctor.first_name} ${doctor.last_name} (${doctor.email})`;
                            div.dataset.doctorId = doctor.id;
                            div.addEventListener('click', function() {
                                receivingDoctorSearch.value = `Dr. ${doctor.first_name} ${doctor.last_name} (${doctor.email})`;
                                receivingDoctorId.value = doctor.id;
                                receivingDoctorResults.classList.add('hidden');
                                doctorSearchSpinner.classList.add('hidden'); // Hide spinner on selection
                            });
                            receivingDoctorResults.appendChild(div);
                        });
                        receivingDoctorResults.classList.remove('hidden');
                    } else {
                        receivingDoctorResults.classList.add('hidden');
                    }
                })
                .finally(() => {
                    doctorSearchSpinner.classList.add('hidden'); // Hide spinner after fetch completes
                });
        }, 300);
    });

    document.addEventListener('click', function(event) {
        if (!receivingDoctorSearch.contains(event.target) && !receivingDoctorResults.contains(event.target)) {
            receivingDoctorResults.classList.add('hidden');
        }
    });
</script>
@endpush