@extends('admin_layout')
@section('content') 
<div id="soapNoteModal" class="  overflow-y-auto h-full w-full ">
    <div class="  mx-auto p-5 border w-[90%] max-w-4xl shadow-lg -md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900">Add SOAP Note</h3>
            <button id="closeSoapNoteModal" class="text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>
        </div> 
        <form id="soapNoteForm">
            @csrf 
            <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="follow_up_date">Date</label>
                <input type="date" value="{{ date('Y-m-d') }}" name="follow_up_date" id="follow_up_date" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="grid grid-cols-1  gap-4 mb-4">
                <div>
                    <label class="block text-emerald-700 text-sm font-bold mb-2">Subjective</label>
                    <textarea name="subjective" id="subjective" placeholder="Subjective" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="2"></textarea>
                    <textarea name="chief_complaint" id="chief_complaint" placeholder="Chief complaint (CC)" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows=2></textarea>
                    <textarea name="history_of_illness" id="history_of_illness" placeholder="History of present illness (HPI)" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="2"></textarea>
 
                </div>

                <div>
                    <label class="block text-emerald-700 text-sm font-bold mb-2">Objective</label>
                    <textarea name="objective" id="objective" placeholder="Enter objective findings." class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="3"></textarea>
                    <div class="border-b border-gray-200 mb-4">
                        <nav class="-mb-px flex space-x-4" aria-label="Tabs">
                            <button type="button" class="tab-button whitespace-nowrap py-2 px-3 border-b-2 font-medium text-sm text-emerald-600 border-emerald-600" data-tab-target="vital-signs-tab-content">Vital Signs</button>
                            <button type="button" class="tab-button whitespace-nowrap py-2 px-3 border-b-2 font-medium text-sm text-gray-600 hover:text-gray-900 border-transparent" data-tab-target="lab-results-tab-content">Lab Results & Files</button>
                            <button type="button" class="tab-button whitespace-nowrap py-2 px-3 border-b-2 font-medium text-sm text-gray-600 hover:text-gray-900 border-transparent" data-tab-target="remarks-tab-content">Remarks</button>
                        </nav>
                    </div>

                    <div id="objective-tab-content">
                        <div id="vital-signs-tab-content" class="tab-pane space-y-2">
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
                                <textarea name="vitals_remark" placeholder="" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2"></textarea>
                            </div>
                      
                        </div>
                        <div id="lab-results-tab-content" class="tab-pane hidden space-y-2">
                            <textarea name="laboratory_results" id="laboratory_results" placeholder="Laboratory results" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2"></textarea>
                            <textarea name="imaging_results" id="imaging_results" placeholder="Imaging results" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2"></textarea>
                        </div>
                        <div id="remarks-tab-content" class="tab-pane hidden space-y-2">
                            <textarea name="remarks" id="remarks" placeholder="Remarks" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2"></textarea>
                            <textarea name="remarks_note" id="remarks_note" placeholder="Remarks Note" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2"></textarea>
                            <textarea name="remarks_template" id="remarks_template" placeholder="Remarks Template" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1  gap-4 mb-4">
                <div>
                    <label class="block text-emerald-700 text-sm font-bold mb-2">Assessment</label>
                    <textarea name="assessment" id="assessment" placeholder="Enter Assessment" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="3"></textarea>
                    <textarea name="diagnosis" id="diagnosis" placeholder="Enter Diagnosis" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="2"></textarea>
                </div>
            </div>
            <div class="grid grid-cols-1  gap-4 mb-4">
                <div>
                    <label class="block text-emerald-700 text-sm font-bold mb-2">Plan</label>
                    <textarea name="plan" id="plan" placeholder="Enter treatment plan" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="3"></textarea>
                    <textarea name="prescription" id="prescription" placeholder="Enter Prescription details" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="2"></textarea>
                    <textarea name="test_request" id="test_request" placeholder="Test Request" class="shadow appearance-none border  w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="2"></textarea>
                    <button class="px-4 py-2 bg-emerald-600 text-white font-semibold -md hover:bg-emerald-700"><i class="fas fa-calendar-alt"></i> Schedule Follow up</button>
                </div>
            </div>

            <div class="flex items-center justify-between mt-4">
                <button type="button" id="cancelSoapNoteBtn" class="px-4 py-2 text-gray-700 font-semibold -md hover:bg-gray-100">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white font-semibold -md hover:bg-emerald-700">Save Note</button>
            </div>
        </form> 
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const openSoapNoteModalBtn = document.getElementById('openSoapNoteModal');
    const closeSoapNoteModalBtn = document.getElementById('closeSoapNoteModal');
    const cancelSoapNoteBtn = document.getElementById('cancelSoapNoteBtn');
    const soapNoteModal = document.getElementById('soapNoteModal');
    const soapNoteForm = document.getElementById('soapNoteForm');

    if (openSoapNoteModalBtn) {
        openSoapNoteModalBtn.addEventListener('click', function() {
            soapNoteModal.classList.remove('hidden');
        });
    }

    if (closeSoapNoteModalBtn) {
        closeSoapNoteModalBtn.addEventListener('click', function() {
            soapNoteModal.classList.add('hidden');
        });
    }

    if (cancelSoapNoteBtn) {
        cancelSoapNoteBtn.addEventListener('click', function() {
            soapNoteModal.classList.add('hidden');
        });
    }

    if (soapNoteModal) {
        soapNoteModal.addEventListener('click', function(event) {
            if (event.target === soapNoteModal) {
                soapNoteModal.classList.add('hidden');
            }
        });
    }

    if (soapNoteForm) {
        soapNoteForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const subjective = document.getElementById('subjective').value;
            const chiefComplaint = document.getElementById('chief_complaint').value;
            const historyOfIllness = document.getElementById('history_of_illness').value;
            const reviewOfSystems = document.getElementById('review_of_systems').value;
            const pastMedicalHistory = document.getElementById('past_medical_history').value;
            const socialHistory = document.getElementById('social_history').value;
            const familyHistory = document.getElementById('family_history').value;

            const objective = document.getElementById('objective').value;
            const weight = document.getElementById('weight').value;
            const height = document.getElementById('height').value;
            const bmi = document.getElementById('bmi').value;
            const bloodPressure = document.getElementById('blood_pressure').value;
            const oxygenSaturation = document.getElementById('oxygen_saturation').value;
            const respiratoryRate = document.getElementById('respiratory_rate').value;
            const heartRate = document.getElementById('heart_rate').value;
            const bodyTemperature = document.getElementById('body_temperature').value;
            const capillaryBloodGlucose = document.getElementById('capillary_blood_glucose').value;

            const objective = document.getElementById('objective').value;
            const physicalExamFindings = document.getElementById('physical_exam_findings').value;
            const laboratoryResults = document.getElementById('laboratory_results').value;
            const imagingResults = document.getElementById('imaging_results').value;
            const otherDiagnosticData = document.getElementById('other_diagnostic_data').value;

            const assessment = document.getElementById('assessment').value;
            const diagnosis = document.getElementById('diagnosis').value;
            const problemList = document.getElementById('problem_list').value;
            const differentialDiagnoses = document.getElementById('differential_diagnoses').value;
            const patientProgress = document.getElementById('patient_progress').value;
            const overallStatus = document.getElementById('overall_status').value;

            const plan = document.getElementById('plan').value;
            const prescription = document.getElementById('prescription').value;
            const testRequest = document.getElementById('test_request').value;
            const remarks = document.getElementById('remarks').value;
            const remarksNote = document.getElementById('remarks_note').value;
            const remarksTemplate = document.getElementById('remarks_template').value;
            const followUpDate = document.getElementById('follow_up_date').value;
            
            const formData = new FormData();
            formData.append('patient_id', this.querySelector('input[name="patient_id"]').value);
            formData.append('doctor_id', this.querySelector('input[name="doctor_id"]').value);
            formData.append('subjective', subjective);
            formData.append('chief_complaint', chiefComplaint);
            formData.append('history_of_illness', historyOfIllness);
            formData.append('objective', objective);
            formData.append('weight', weight);
            formData.append('height', height);
            formData.append('bmi', bmi);
            formData.append('blood_pressure', bloodPressure);
            formData.append('oxygen_saturation', oxygenSaturation);
            formData.append('respiratory_rate', respiratoryRate);
            formData.append('heart_rate', heartRate);
            formData.append('body_temperature', bodyTemperature);
            formData.append('capillary_blood_glucose', capillaryBloodGlucose);
            formData.append('physical_exam_findings', physicalExamFindings);
            formData.append('laboratory_results', laboratoryResults);
            formData.append('imaging_results', imagingResults);
            formData.append('other_diagnostic_data', otherDiagnosticData);
            formData.append('assessment', assessment);
            formData.append('diagnosis', diagnosis);
            formData.append('problem_list', problemList);
            formData.append('differential_diagnoses', differentialDiagnoses);
            formData.append('patient_progress', patientProgress);
            formData.append('overall_status', overallStatus);
            formData.append('plan', plan);
            formData.append('prescription', prescription);
            formData.append('test_request', testRequest);
            formData.append('remarks', remarks);
            formData.append('remarks_note', remarksNote);
            formData.append('remarks_template', remarksTemplate);
            formData.append('follow_up_date', followUpDate);
            formData.append('review_of_systems', reviewOfSystems);
            formData.append('past_medical_history', pastMedicalHistory);
            formData.append('social_history', socialHistory);
            formData.append('family_history', familyHistory);


            fetch('/doctor/soap-notes/store', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('SOAP note added successfully!');
                    soapNoteModal.classList.add('hidden');
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Could not add SOAP note.'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while adding the SOAP note.');
            });

            document.getElementById('subjective').value = '';
            document.getElementById('chief_complaint').value = '';
            document.getElementById('history_of_illness').value = '';
            document.getElementById('review_of_systems').value = '';
            document.getElementById('past_medical_history').value = '';
            document.getElementById('social_history').value = '';
            document.getElementById('family_history').value = '';

            document.getElementById('objective').value = '';
            document.getElementById('weight').value = '';
            document.getElementById('height').value = '';
            document.getElementById('bmi').value = '';
            document.getElementById('blood_pressure').value = '';
            document.getElementById('oxygen_saturation').value = '';
            document.getElementById('respiratory_rate').value = '';
            document.getElementById('heart_rate').value = '';
            document.getElementById('body_temperature').value = '';
            document.getElementById('capillary_blood_glucose').value = '';
            document.getElementById('physical_exam_findings').value = '';
            document.getElementById('laboratory_results').value = '';
            document.getElementById('imaging_results').value = '';
            document.getElementById('other_diagnostic_data').value = '';

            document.getElementById('assessment').value = '';
            document.getElementById('diagnosis').value = '';
            document.getElementById('problem_list').value = '';
            document.getElementById('differential_diagnoses').value = '';
            document.getElementById('patient_progress').value = '';
            document.getElementById('overall_status').value = '';

            document.getElementById('plan').value = '';
            document.getElementById('prescription').value = '';
            document.getElementById('test_request').value = '';
            document.getElementById('remarks').value = '';
            document.getElementById('remarks_note').value = '';
            document.getElementById('remarks_template').value = '';
            document.getElementById('follow_up_date').value = '';
        });
    }

    // Tab switching logic
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-pane');

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
});
</script>
@endpush
