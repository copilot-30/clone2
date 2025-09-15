<div class="overflow-y-auto  w-full" id="soap-note-{{ $soapNote->id }}">
    <div class="  mx-auto p-5 border w-full max-w-4xl shadow-lg -md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-emerald-700">Author: Dr. {{ ucwords($soapNote->doctor->first_name .' ' .$soapNote->doctor->last_name)}}</h3>
            <div class="rounded bg-gray-500 text-gray-100 px-2 py-1">#{{$soapNote->id}}</div>
        </div> 
        <form id="soapNoteForm-{{ $soapNote->id }}" method="POST" action="{{ route('doctor.soap-notes.update', $soapNote->id) }}">
            @csrf
    
    
            <input type="hidden" name="patient_id" value="{{ $soapNote->patient_id }}">
            <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="date-{{ $soapNote->id }}">Date</label>
                <input type="date" value="{{ $soapNote->date->format('Y-m-d') ?? date('Y-m-d') }}" name="date" id="date-{{ $soapNote->id }}" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="grid grid-cols-1  gap-4 mb-4">
                <div>
                    <label class="block text-emerald-700 text-sm font-bold mb-2">Subjective</label> 
                    <textarea name="subjective" id="subjective-{{ $soapNote->id }}" placeholder="Subjective" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="2">{{$soapNote->subjective}}</textarea>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="chief_complaint-{{ $soapNote->id }}">Chief Complaint</label>
                    <textarea name="chief_complaint" id="chief_complaint-{{ $soapNote->id }}" placeholder="Chief complaint (CC)" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows=2>{{$soapNote->chief_complaint}}</textarea>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="history_of_illness-{{ $soapNote->id }}">History of Present Illness (HPI)</label>
                    <textarea name="history_of_illness" id="history_of_illness-{{ $soapNote->id }}" placeholder="History of present illness (HPI)" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="2">{{$soapNote->history_of_illness}}</textarea>
 
                </div>

                <div>
                    <label class="block text-emerald-700 text-sm font-bold mb-2">Objective</label> 
                    <textarea name="objective" id="objective-{{ $soapNote->id }}" placeholder="Enter objective findings." class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="3">{{$soapNote->objective}}</textarea>
                    <div class="border-b border-gray-200 mb-4">
                        <nav class="-mb-px flex space-x-4" aria-label="Tabs">
                            <a href="#" type="button" class="tab-button-c whitespace-nowrap py-2 px-3 border-b-2 font-medium text-sm text-emerald-600 border-emerald-600" data-target="vital-signs-tab-content-{{ $soapNote->id }}">Vital Signs</a>
                            <a href="#" type="button" class="tab-button-c whitespace-nowrap py-2 px-3 border-b-2 font-medium text-sm text-gray-600 hover:text-gray-900 border-transparent" data-target="lab-results-tab-content-{{ $soapNote->id }}">Lab Results & Files</a>
                            <a href="#" type="button" class="tab-button-c whitespace-nowrap py-2 px-3 border-b-2 font-medium text-sm text-gray-600 hover:text-gray-900 border-transparent" data-target="remarks-tab-content-{{ $soapNote->id }}">Remarks</a>
                        </nav>
                    </div>

                    <div id="objective-tab-content-{{ $soapNote->id }}">
                        <div id="vital-signs-tab-content-{{ $soapNote->id }}" class="tab-pane space-y-2">
                            <div class="grid grid-cols-5 gap-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Weight</label>
                                    <input type="text" name="weight" id="weight-{{ $soapNote->id }}" placeholder="kg" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Height</label>
                                    <input type="text" name="height" id="height-{{ $soapNote->id }}" placeholder="cm" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">BMI</label>
                                    <input type="text" name="bmi" id="bmi-{{ $soapNote->id }}" placeholder="kg/m²" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Blood Pressure </label>
                                    <input type="text" name="blood_pressure" id="blood_pressure-{{ $soapNote->id }}" placeholder="mmHg" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Oxygen Saturation (%)</label>
                                    <input type="text" name="oxygen_saturation" id="oxygen_saturation-{{ $soapNote->id }}" placeholder="%" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                            </div>
    
                            <div class="grid grid-cols-5 gap-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Respiratory Rate</label>
                                    <input type="text" name="respiratory_rate" id="respiratory_rate-{{ $soapNote->id }}" placeholder="breaths/min" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Heart Rate (bpm)</label>
                                    <input type="text" name="heart_rate" id="heart_rate-{{ $soapNote->id }}" placeholder="bpm" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Body Temperature</label>
                                    <input type="text" name="body_temperature" id="body_temperature-{{ $soapNote->id }}" placeholder="°C" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-xs font-bold mb-2">Capillary Blood Glucose</label>
                                    <input type="text" name="capillary_blood_glucose" id="capillary_blood_glucose-{{ $soapNote->id }}" placeholder="mg/dL" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                            </div>
                            <div class="">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="vitals_remark-{{ $soapNote->id }}">Additional Notes about Vitals</label>
                                <textarea name="vitals_remark" id="vitals_remark-{{ $soapNote->id }}" placeholder="Addtional notes about vitals." class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2"></textarea>
                            </div>
                      
                        </div>
                        <div id="lab-results-tab-content-{{ $soapNote->id }}" class="tab-pane space-y-2">
                           
                            <div class="mt-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Upload Files</label>
                                <div id="drop-area-{{ $soapNote->id }}" class="border-2 border-dashed border-gray-300 rounded-md p-6 text-center cursor-pointer hover:border-gray-400">
                                    <p class="text-gray-500">Drag & drop files here, or click to select</p>
                                    <input type="file" id="file-upload-{{ $soapNote->id }}" name="lab_files[]" multiple class="hidden">
                                </div>
                                <div id="file-list-{{ $soapNote->id }}" class="mt-2"></div>
                                
                            </div>
                             <label class="block text-gray-700 text-sm font-bold mb-2" for="file_remarks-{{ $soapNote->id }}">File Description</label>
                             <textarea name="file_remarks" id="file_remarks-{{ $soapNote->id }}" placeholder="File description" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="7">{{$soapNote->file_remarks}}</textarea>
                            
                        </div>
                        <div id="remarks-tab-content-{{ $soapNote->id }}" class="tab-pane hidden space-y-2">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="remarks-{{ $soapNote->id }}">Remarks</label>
                            <textarea name="remarks" id="remarks-{{ $soapNote->id }}" placeholder="Remarks" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2">{{$soapNote->remarks}}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1  gap-4 mb-4">
                <div>
                    <label class="block text-emerald-700 text-sm font-bold mb-2">Assessment</label> 
                    <textarea name="assessment" id="assessment-{{ $soapNote->id }}" placeholder="Enter Assessment" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="3">{{$soapNote->assessment}}</textarea>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="diagnosis-{{ $soapNote->id }}">Diagnosis</label>
                    <textarea name="diagnosis" id="diagnosis-{{ $soapNote->id }}" placeholder="Enter Diagnosis" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="2">{{$soapNote->diagnosis}}</textarea>
                </div>
            </div>
            <div class="grid grid-cols-1  gap-4 mb-4">
                <div>
                    <label class="block text-emerald-700 text-sm font-bold mb-2">Plan</label>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="plan-{{ $soapNote->id }}">Treatment Plan</label>
                    <textarea name="plan" id="plan-{{ $soapNote->id }}" placeholder="Enter treatment plan" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="3">{{$soapNote->plan}}</textarea>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="prescription-{{ $soapNote->id }}">Prescription Details</label>
                    <textarea name="prescription" id="prescription-{{ $soapNote->id }}" placeholder="Enter Prescription details" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="2">{{$soapNote->prescription}}</textarea>
                    @if(strlen($soapNote->prescription)> 0)
                        <button class="send-to-patient-btn bg-emerald-700 text-sm p-2 text-white font-semibold rounded-md hover:bg-emerald-800 float-right mx-2 my-2" data-type="prescription" data-patient-id="{{ $soapNote->patient_id }}" data-doctor-id="{{ $doctor->id }}" data-soap-note-id="{{ $soapNote->id }}" data-content-id="prescription-{{ $soapNote->id }}"><i class="fas fa-paper-plane"></i> Send Prescription to Patient</button>
                    @endif
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="test_request-{{ $soapNote->id }}">Test Request</label>
                    <textarea name="test_request" id="test_request-{{ $soapNote->id }}" placeholder="Test Request" class="shadow appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="2">{{$soapNote->test_request}}</textarea>
                    @if(strlen($soapNote->test_request)> 0)
                        <button class="send-to-patient-btn bg-emerald-700 text-sm p-2 text-white font-semibold rounded-md hover:bg-emerald-800 float-right mx-2 my-2" data-type="test_request" data-patient-id="{{ $soapNote->patient_id }}" data-doctor-id="{{ $doctor->id }}" data-soap-note-id="{{ $soapNote->id }}" data-content-id="test_request-{{ $soapNote->id }}"><i class="fas fa-paper-plane"></i> Send Test Request to Patient</button>
                    @endif
                </div>
            </div>

            <div class="flex items-center justify-between mt-4">
                 <button class="px-4 py-2 bg-emerald-600 text-white font-semibold -md hover:bg-emerald-700"><i class="fas fa-calendar-alt"></i> Schedule Follow up</button>
                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white font-semibold -md hover:bg-emerald-700"><i class="fas fa-save"></i> Update Note</button>
            </div>
        </form>
    </div>
</div>

@php
    $vs = $soapNote->vital_signs ?? [];
    $labFiles = [];
    $patientId = $soapNote->patient_id;

    // Fetch LabResults associated with this SOAP note
    $associatedLabResults = \App\LabResult::where('patient_id', $patientId)
        ->where('notes', 'LIKE', '%Consultation ID: ' . $soapNote->id . '%')
        ->get();

    foreach ($associatedLabResults as $labResult) {
        $labFiles[] = [
            'id' => $labResult->id, // Use LabResult ID for deletion
            'file_name' => json_decode($labResult->result_data)->file_name ?? 'Unknown File',
            'result_file_url' => $labResult->result_file_url,
            'is_existing' => true // Mark as existing file
        ];
    }
@endphp

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    // Tab switching logic for soap notes
    const soapNoteForm = document.getElementById('soapNoteForm-{{ $soapNote->id }}');
    if (!soapNoteForm) return; // Exit if form doesn't exist for this instance

    const tabButtons = soapNoteForm.querySelectorAll('.tab-button-c');
    const tabContents = soapNoteForm.querySelectorAll('.tab-pane');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Deactivate all buttons and hide all content
            tabButtons.forEach(btn => {
                btn.classList.remove('border-emerald-600', 'text-emerald-600');
                btn.classList.add('border-transparent', 'text-gray-600', 'hover:text-gray-900');
            });
            tabContents.forEach(content => content.classList.add('hidden'));

            // Activate clicked button
            button.classList.add('border-emerald-600', 'text-emerald-600');
            button.classList.remove('border-transparent', 'text-gray-600', 'hover:text-gray-900');

            // Show target content
            const targetId = button.dataset.target;

            if (document.getElementById(targetId)) {
                document.getElementById(targetId).classList.remove('hidden');
                console.log('Tab content shown:', targetId);
            }else{
                console.error('Tab content not found:', targetId);
            }

            
        });
    });

    // Automatically click the first tab button on load
    if (tabButtons.length > 0) {
        tabButtons[0].click();
    }

    // Initialize vital signs fields
    document.getElementById('weight-{{ $soapNote->id }}').value = "{{ $vs['weight'] ?? '' }}";
    document.getElementById('height-{{ $soapNote->id }}').value = "{{ $vs['height'] ?? '' }}";
    document.getElementById('bmi-{{ $soapNote->id }}').value = "{{ $vs['bmi'] ?? '' }}";
    document.getElementById('blood_pressure-{{ $soapNote->id }}').value = "{{ $vs['blood_pressure'] ?? '' }}";
    document.getElementById('oxygen_saturation-{{ $soapNote->id }}').value = "{{ $vs['oxygen_saturation'] ?? '' }}";
    document.getElementById('respiratory_rate-{{ $soapNote->id }}').value = "{{ $vs['respiratory_rate'] ?? '' }}";
    document.getElementById('heart_rate-{{ $soapNote->id }}').value = "{{ $vs['heart_rate'] ?? '' }}";
    document.getElementById('body_temperature-{{ $soapNote->id }}').value = "{{ $vs['body_temperature'] ?? '' }}";
    document.getElementById('capillary_blood_glucose-{{ $soapNote->id }}').value = "{{ $vs['capillary_blood_glucose'] ?? '' }}";
    document.getElementById('vitals_remark-{{ $soapNote->id }}').value = "{{ $vs['vitals_remark'] ?? '' }}";

    // File Upload Logic for SOAP Note form
    const dropArea = document.getElementById('drop-area-{{ $soapNote->id }}');
    const fileInput = document.getElementById('file-upload-{{ $soapNote->id }}');
    const fileListContainer = document.getElementById('file-list-{{ $soapNote->id }}');
    let uploadedFiles = [];
    let existingFiles = {!! json_encode($labFiles) !!};
    let deletedFileIds = [];

    // Display existing files
    displayFiles(uploadedFiles, existingFiles);

    if (dropArea && fileInput && fileListContainer) { // Check if elements exist before adding listeners
        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
        });

        // Highlight drop area when item is dragged over it
        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, unhighlight, false);
        });

        // Handle dropped files
        dropArea.addEventListener('drop', handleDrop, false);
        fileInput.addEventListener('change', handleFileSelect, false);
        dropArea.addEventListener('click', () => fileInput.click()); // Open file dialog on click
    }

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function highlight() {
        dropArea.classList.add('border-emerald-600');
    }

    function unhighlight() {
        dropArea.classList.remove('border-emerald-600');
    }

    function handleFileSelect(e) {
        const files = e.target.files;
        handleFiles(files);
    }

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        handleFiles(files);
    }

    function handleFiles(files) {
        for (let i = 0; i < files.length; i++) {
            uploadedFiles.push(files[i]);
        }
        displayFiles(uploadedFiles, existingFiles);
    }

    function displayFiles(newFiles = [], currentExistingFiles = []) {
        fileListContainer.innerHTML = ''; // Clear previous list
        fileListContainer.className= 'flex justify-between items-center  p-2 rounded-md mb-1 gap-4';
        
        // Display existing files
        currentExistingFiles.forEach((file, index) => {
  
            const fileElement = document.createElement('div');
            fileElement.className = 'flex justify-between items-center p-2 rounded-md mb-1 border border-blue-500';
            fileElement.innerHTML = `
                <a href='#' class="view-file-button text-sm text-blue-500 hover:text-blue-700" data-file-url="${file.result_file_url}" data-file-id="${file.id}"><i class="fas fa-file mr-2"></i>  ${file.file_name}</a> 
                <button type="button" data-file-id="${file.id}" class="remove-existing-file-button text-red-500 hover:text-red-700"> <i class="fa fa-times"></i></button>
            `;
            fileListContainer.appendChild(fileElement);
        });

        // Display newly uploaded files
        newFiles.forEach((file, index) => {
            const fileElement = document.createElement('div');
            fileElement.className = 'flex justify-between items-center bg-gray-100 p-2 rounded-md mb-1';
            fileElement.innerHTML = `
                ${file.name}
                <button type="button" data-index="${index}" class="remove-new-file-button text-red-500 hover:text-red-700"> <i class="fa fa-times"></i></button>
            `;
            fileListContainer.appendChild(fileElement);
        });

        soapNoteForm.querySelectorAll('.view-file-button').forEach(button => {
            button.addEventListener('click', function() { 
                const loc = this.dataset.fileUrl;
                console.log(loc);
                window.open(window.location.origin+ loc, '_blank');
            });
        })
 


        // Add event listeners for remove buttons
        soapNoteForm.querySelectorAll('.remove-existing-file-button').forEach(button => {
            button.addEventListener('click', function() {
                const fileId = this.dataset.fileId;
                removeExistingFile(fileId);
            });
        });

        soapNoteForm.querySelectorAll('.remove-new-file-button').forEach(button => {
            button.addEventListener('click', function() {
                const index = parseInt(this.dataset.index);
                removeNewFile(index);
            });
        });
    }

    function removeExistingFile(fileId) {
        deletedFileIds.push(fileId);
        existingFiles = existingFiles.filter(file => file.id !== fileId);
        displayFiles(uploadedFiles, existingFiles); // Re-render the list
    }

    function removeNewFile(index) {
        uploadedFiles.splice(index, 1);
        displayFiles(uploadedFiles, existingFiles); // Re-render the list
    }

    // Handle SOAP note form submission
    if (soapNoteForm) {
        soapNoteForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'PUT'); // Add this for PUT request
            formData.append('patient_id', soapNoteForm.querySelector('input[name="patient_id"]').value);
            formData.append('doctor_id', soapNoteForm.querySelector('input[name="doctor_id"]').value);
            formData.append('subjective', soapNoteForm.querySelector('textarea[name="subjective"]').value);
            formData.append('chief_complaint', soapNoteForm.querySelector('textarea[name="chief_complaint"]').value);
            formData.append('history_of_illness', soapNoteForm.querySelector('textarea[name="history_of_illness"]').value);
            formData.append('objective', soapNoteForm.querySelector('textarea[name="objective"]').value);
            formData.append('weight', soapNoteForm.querySelector('input[name="weight"]').value);
            formData.append('height', soapNoteForm.querySelector('input[name="height"]').value);
            formData.append('bmi', soapNoteForm.querySelector('input[name="bmi"]').value);
            formData.append('blood_pressure', soapNoteForm.querySelector('input[name="blood_pressure"]').value);
            formData.append('oxygen_saturation', soapNoteForm.querySelector('input[name="oxygen_saturation"]').value);
            formData.append('respiratory_rate', soapNoteForm.querySelector('input[name="respiratory_rate"]').value);
            formData.append('heart_rate', soapNoteForm.querySelector('input[name="heart_rate"]').value);
            formData.append('body_temperature', soapNoteForm.querySelector('input[name="body_temperature"]').value);
            formData.append('capillary_blood_glucose', soapNoteForm.querySelector('input[name="capillary_blood_glucose"]').value);
            formData.append('vitals_remark', soapNoteForm.querySelector('textarea[name="vitals_remark"]').value);
            formData.append('file_remarks', soapNoteForm.querySelector('textarea[name="file_remarks"]').value);
            formData.append('assessment', soapNoteForm.querySelector('textarea[name="assessment"]').value);
            formData.append('diagnosis', soapNoteForm.querySelector('textarea[name="diagnosis"]').value);
            formData.append('plan', soapNoteForm.querySelector('textarea[name="plan"]').value);
            formData.append('prescription', soapNoteForm.querySelector('textarea[name="prescription"]').value);
            formData.append('test_request', soapNoteForm.querySelector('textarea[name="test_request"]').value);
            formData.append('remarks', soapNoteForm.querySelector('textarea[name="remarks"]').value);
            formData.append('date', soapNoteForm.querySelector('input[name="date"]').value);

            console.log('formData', formData);
            
            uploadedFiles.forEach(file => {
                formData.append('lab_files[]', file);
            });

            deletedFileIds.forEach(id => {
                formData.append('deleted_file_ids[]', id);
            });

            fetch("{{ route('doctor.soap-notes.update', $soapNote->id) }}", {
                method: 'POST', // Use POST with _method PUT for Laravel
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message || 'SOAP Note updated successfully!');
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Could not update SOAP Note.'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the SOAP note.');
            });
        });

        // Handle "Send to Patient" button clicks
        soapNoteForm.querySelectorAll('.send-to-patient-btn').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                const type = this.dataset.type; // 'prescription' or 'test_request'
                const patientId = this.dataset.patientId;
                const doctorId = this.dataset.doctorId;
                const soapNoteId = this.dataset.soapNoteId;
                const contentId = this.dataset.contentId;
                const content = soapNoteForm.querySelector(`#${contentId}`).value;

                if (!content) {
                    alert(`Please enter content for the ${type} before sending.`);
                    return;
                }

                const url = type === 'prescription' ? "{{ route('doctor.patient-prescriptions.store') }}" : "{{ route('doctor.patient-test-requests.store') }}";

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        patient_id: patientId,
                        doctor_id: doctorId,
                        soap_note_id: soapNoteId,
                        content: content
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                    } else {
                        alert('Error: ' + (data.message || 'Could not send ' + type + '.'));
                        if (data.errors) {
                            console.error('Validation Errors:', data.errors);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while sending the ' + type + '.');
                });
            });
        });
    }
});
</script>
@endpush