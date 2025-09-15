<div class="overflow-y-auto h-full w-full ">
    <div class="  mx-auto p-5 border w-full max-w-4xl shadow-lg -md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-emerald-700">Author: Dr. {{ ucwords($soapNote->doctor->first_name .' ' .$soapNote->doctor->last_name)}}</h3>
            
        </div> 
        <form id="soapNoteForm" method="POST" action="{{ route('doctor.soap-notes.update', $soapNote->id) }}">
            @csrf 
    
    
            <input type="hidden" name="patient_id" value="{{ $soapNote->patient_id }}">
            <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="follow_up_date">Date</label>
                <input type="date" value="{{ $soapNote->date->format('Y-m-d') ?? date('Y-m-d') }}" name="follow_up_date" id="follow_up_date" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="grid grid-cols-1  gap-4 mb-4">
                <div>
                    <label class="block text-emerald-700 text-sm font-bold mb-2">Subjective</label>
                    <textarea name="subjective" id="subjective" placeholder="Subjective" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="2">{{$soapNote->subjective}}</textarea>
                    <textarea name="chief_complaint" id="chief_complaint" placeholder="Chief complaint (CC)" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows=2>{{$soapNote->chief_complaint}}</textarea>
                    <textarea name="history_of_illness" id="history_of_illness" placeholder="History of present illness (HPI)" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="2">{{$soapNote->history_of_illness}}</textarea>
 
                </div>

                <div>
                    <label class="block text-emerald-700 text-sm font-bold mb-2">Objective</label>
                    <textarea name="objective" id="objective" placeholder="Enter objective findings." class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="3">{{$soapNote->objective}}</textarea>
                    <div class="border-b border-gray-200 mb-4">
                        <nav class="-mb-px flex space-x-4" aria-label="Tabs">
                            <button type="button" class="tab-button whitespace-nowrap py-2 px-3 border-b-2 font-medium text-sm text-emerald-600 border-emerald-600" data-tab-target="vital-signs-tab-content-{{ $soapNote->id }}">Vital Signs</button>
                            <button type="button" class="tab-button whitespace-nowrap py-2 px-3 border-b-2 font-medium text-sm text-gray-600 hover:text-gray-900 border-transparent" data-tab-target="lab-results-tab-content-{{ $soapNote->id }}">Lab Results & Files</button>
                            <button type="button" class="tab-button whitespace-nowrap py-2 px-3 border-b-2 font-medium text-sm text-gray-600 hover:text-gray-900 border-transparent" data-tab-target="remarks-tab-content-{{ $soapNote->id }}">Remarks</button>
                        </nav>
                    </div>

                    <div id="objective-tab-content">
                        <div id="vital-signs-tab-content-{{ $soapNote->id }}" class="tab-pane space-y-2">
                            <div class="grid grid-cols-5 gap-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Weight</label>
                                    <input type="text" name="weight" id="weight" placeholder="kg" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Height</label>
                                    <input type="text" name="height" id="height" placeholder="cm" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">BMI</label>
                                    <input type="text" name="bmi" id="bmi" placeholder="kg/m²" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Blood Pressure </label>
                                    <input type="text" name="blood_pressure" id="blood_pressure" placeholder="mmHg" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Oxygen Saturation (%)</label>
                                    <input type="text" name="oxygen_saturation" id="oxygen_saturation" placeholder="%" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                            </div>
    
                            <div class="grid grid-cols-5 gap-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Respiratory Rate</label>
                                    <input type="text" name="respiratory_rate" id="respiratory_rate" placeholder="breaths/min" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Heart Rate (bpm)</label>
                                    <input type="text" name="heart_rate" id="heart_rate" placeholder="bpm" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Body Temperature</label>
                                    <input type="text" name="body_temperature" id="body_temperature" placeholder="°C" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-xs font-bold mb-2">Capillary Blood Glucose</label>
                                    <input type="text" name="capillary_blood_glucose" id="capillary_blood_glucose" placeholder="mg/dL" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                            </div>
                            <div class="">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Addtional notes about vitals.</label>
                                <textarea name="vitals_remark" placeholder="Addtional notes about vitals." class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2"></textarea>
                            </div>
                      
                        </div>
                        <div id="lab-results-tab-content-{{ $soapNote->id }}" class="tab-pane hidden space-y-2">
                           
                            <div class="mt-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Upload Files</label>
                                <div id="drop-area" class="border-2 border-dashed border-gray-300 rounded-md p-6 text-center cursor-pointer hover:border-gray-400">
                                    <p class="text-gray-500">Drag & drop files here, or click to select</p>
                                    <input type="file" id="file-upload" name="lab_files[]" multiple class="hidden">
                                </div>
                                <div id="file-list" class="mt-2"></div>
                            </div>
                             <textarea name="file_remarks" id="file_remarks" placeholder="File description" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="7"></textarea>
                            
                        </div>
                        <div id="remarks-tab-content-{{ $soapNote->id }}" class="tab-pane hidden space-y-2">
                            <textarea name="remarks" id="remarks" placeholder="Remarks" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1  gap-4 mb-4">
                <div>
                    <label class="block text-emerald-700 text-sm font-bold mb-2">Assessment</label>
                    <textarea name="assessment" id="assessment" placeholder="Enter Assessment" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="3">{{$soapNote->assessment}}</textarea>
                    <textarea name="diagnosis" id="diagnosis" placeholder="Enter Diagnosis" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="2">{{$soapNote->diagnosis}}</textarea>
                </div>
            </div>
            <div class="grid grid-cols-1  gap-4 mb-4">
                <div>
                    <label class="block text-emerald-700 text-sm font-bold mb-2">Plan</label>
                    <textarea name="plan" id="plan" placeholder="Enter treatment plan" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="3">{{$soapNote->plan}}</textarea>
                    <textarea name="prescription" id="prescription" placeholder="Enter Prescription details" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="2">{{$soapNote->prescription}}</textarea>
                    <textarea name="test_request" id="test_request" placeholder="Test Request" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="2">{{$soapNote->test_request}}</textarea>
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
            'file_url' => $labResult->result_file_url,
            'is_existing' => true // Mark as existing file
        ];
    }
@endphp

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching logic for soap notes
    const tabButtons = document.querySelectorAll('#soapNoteForm .tab-button');
    const tabContents = document.querySelectorAll('#soapNoteForm .tab-pane');

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
            const targetId = button.dataset.tabTarget;
            document.getElementById(targetId).classList.remove('hidden');
        });
    });

    // Automatically click the first tab button on load
    if (tabButtons.length > 0) {
        tabButtons[0].click();
    }

    // Initialize vital signs fields
    document.getElementById('weight').value = "{{ $vs['weight'] ?? '' }}";
    document.getElementById('height').value = "{{ $vs['height'] ?? '' }}";
    document.getElementById('bmi').value = "{{ $vs['bmi'] ?? '' }}";
    document.getElementById('blood_pressure').value = "{{ $vs['blood_pressure'] ?? '' }}";
    document.getElementById('oxygen_saturation').value = "{{ $vs['oxygen_saturation'] ?? '' }}";
    document.getElementById('respiratory_rate').value = "{{ $vs['respiratory_rate'] ?? '' }}";
    document.getElementById('heart_rate').value = "{{ $vs['heart_rate'] ?? '' }}";
    document.getElementById('body_temperature').value = "{{ $vs['body_temperature'] ?? '' }}";
    document.getElementById('capillary_blood_glucose').value = "{{ $vs['capillary_blood_glucose'] ?? '' }}";
    document.querySelector('textarea[name="vitals_remark"]').value = "{{ $vs['vitals_remark'] ?? '' }}";

    // File Upload Logic for SOAP Note form
    const dropArea = document.getElementById('drop-area');
    const fileInput = document.getElementById('file-upload');
    const fileListContainer = document.getElementById('file-list');
    let uploadedFiles = [];
    let existingFiles = {!! json_encode($labFiles) !!};
    let deletedFileIds = [];

    // Display existing files
    displayFiles(existingFiles);

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

        // Display existing files
        currentExistingFiles.forEach((file, index) => {
            const fileElement = document.createElement('div');
            fileElement.className = 'flex justify-between items-center bg-gray-100 p-2 rounded-md mb-1';
            fileElement.innerHTML = `
                <span>${file.file_name}</span>
                <button type="button" data-file-id="${file.id}" class="remove-existing-file-button text-red-500 hover:text-red-700">&times;</button>
            `;
            fileListContainer.appendChild(fileElement);
        });

        // Display newly uploaded files
        newFiles.forEach((file, index) => {
            const fileElement = document.createElement('div');
            fileElement.className = 'flex justify-between items-center bg-gray-100 p-2 rounded-md mb-1';
            fileElement.innerHTML = `
                <span>${file.name}</span>
                <button type="button" data-index="${index}" class="remove-new-file-button text-red-500 hover:text-red-700">&times;</button>
            `;
            fileListContainer.appendChild(fileElement);
        });

        // Add event listeners for remove buttons
        document.querySelectorAll('.remove-existing-file-button').forEach(button => {
            button.addEventListener('click', function() {
                const fileId = this.dataset.fileId;
                removeExistingFile(fileId);
            });
        });

        document.querySelectorAll('.remove-new-file-button').forEach(button => {
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
    const soapNoteForm = document.getElementById('soapNoteForm');
    if (soapNoteForm) {
        soapNoteForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'PUT'); // Add this for PUT request
            formData.append('patient_id', this.querySelector('input[name="patient_id"]').value);
            formData.append('doctor_id', this.querySelector('input[name="doctor_id"]').value);
            formData.append('subjective', document.getElementById('subjective').value);
            formData.append('chief_complaint', document.getElementById('chief_complaint').value);
            formData.append('history_of_illness', document.getElementById('history_of_illness').value);
            formData.append('objective', document.getElementById('objective').value);
            formData.append('weight', document.getElementById('weight').value);
            formData.append('height', document.getElementById('height').value);
            formData.append('bmi', document.getElementById('bmi').value);
            formData.append('blood_pressure', document.getElementById('blood_pressure').value);
            formData.append('oxygen_saturation', document.getElementById('oxygen_saturation').value);
            formData.append('respiratory_rate', document.getElementById('respiratory_rate').value);
            formData.append('heart_rate', document.getElementById('heart_rate').value);
            formData.append('body_temperature', document.getElementById('body_temperature').value);
            formData.append('capillary_blood_glucose', document.getElementById('capillary_blood_glucose').value);
            formData.append('vitals_remark', document.querySelector('textarea[name="vitals_remark"]').value);
            formData.append('file_remarks', document.getElementById('file_remarks').value);
            formData.append('assessment', document.getElementById('assessment').value);
            formData.append('diagnosis', document.getElementById('diagnosis').value);
            formData.append('plan', document.getElementById('plan').value);
            formData.append('prescription', document.getElementById('prescription').value);
            formData.append('test_request', document.getElementById('test_request').value);
            formData.append('remarks', document.getElementById('remarks').value);
            formData.append('follow_up_date', document.getElementById('follow_up_date').value);
            
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
                    window.location.reload(); // Reload the page to reflect changes
                } else {
                    alert('Error: ' + (data.message || 'Could not update SOAP Note.'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the SOAP note.');
            });
        });
    }
});
</script>
@endpush