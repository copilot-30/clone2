<!-- SOAP Note Modal -->
<div id="soapNoteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full ">
    <div class="relative top-20 mx-auto p-5 border w-[90%] max-w-4xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900">Add SOAP Note</h3>
            <button id="closeSoapNoteModal" class="text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>
        </div>
        @if(isset($selectedPatient) && isset($doctor))
        <form id="soapNoteForm">
            @csrf
            <input type="hidden" name="patient_id" value="{{ $selectedPatient->id }}">
            <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Subjective</label>
                    <textarea name="subjective" id="subjective" placeholder="Subjective" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="3"></textarea>
                    <input type="text" name="chief_complaint" id="chief_complaint" placeholder="Chief complaint (CC)" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2">
                    <textarea name="history_of_illness" id="history_of_illness" placeholder="History of present illness (HPI)" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="2"></textarea>
                    <textarea name="review_of_systems" id="review_of_systems" placeholder="Review of systems (ROS)" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="2"></textarea>
                    <textarea name="past_medical_history" id="past_medical_history" placeholder="Past medical history (PMH)" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="2"></textarea>
                    <textarea name="social_history" id="social_history" placeholder="Social history (SH)" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="2"></textarea>
                    <textarea name="family_history" id="family_history" placeholder="Family history (FH)" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="2"></textarea>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Objective</label>
                    <textarea name="objective" id="objective" placeholder="Objective" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="3"></textarea>
                    <div class="border-b border-gray-200 mb-4">
                        <nav class="-mb-px flex space-x-4" aria-label="Tabs">
                            <button type="button" class="tab-button whitespace-nowrap py-2 px-3 border-b-2 font-medium text-sm text-emerald-600 border-emerald-600" data-tab-target="vital-signs-tab-content">Vital Signs</button>
                            <button type="button" class="tab-button whitespace-nowrap py-2 px-3 border-b-2 font-medium text-sm text-gray-600 hover:text-gray-900 border-transparent" data-tab-target="lab-results-tab-content">Lab Results & Files</button>
                            <button type="button" class="tab-button whitespace-nowrap py-2 px-3 border-b-2 font-medium text-sm text-gray-600 hover:text-gray-900 border-transparent" data-tab-target="remarks-tab-content">Remarks</button>
                        </nav>
                    </div>

                    <div id="objective-tab-content">
                        <div id="vital-signs-tab-content" class="tab-pane space-y-2">
                            <input type="text" name="vital_signs" id="vital_signs" placeholder="Vital signs" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <textarea name="physical_exam_findings" id="physical_exam_findings" placeholder="Physical examination findings" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2"></textarea>
                            <textarea name="other_diagnostic_data" id="other_diagnostic_data" placeholder="Other diagnostic data" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2"></textarea>
                        </div>
                        <div id="lab-results-tab-content" class="tab-pane hidden space-y-2">
                            <textarea name="laboratory_results" id="laboratory_results" placeholder="Laboratory results" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2"></textarea>
                            <textarea name="imaging_results" id="imaging_results" placeholder="Imaging results" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2"></textarea>
                        </div>
                        <div id="remarks-tab-content" class="tab-pane hidden space-y-2">
                            <textarea name="remarks" id="remarks" placeholder="Remarks" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2"></textarea>
                            <textarea name="remarks_note" id="remarks_note" placeholder="Remarks Note" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2"></textarea>
                            <textarea name="remarks_template" id="remarks_template" placeholder="Remarks Template" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Assessment</label>
                    <textarea name="assessment" id="assessment" placeholder="Assessment" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="3"></textarea>
                    <textarea name="diagnosis" id="diagnosis" placeholder="Diagnosis" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="2"></textarea>
                    <textarea name="problem_list" id="problem_list" placeholder="Problem list" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="2"></textarea>
                    <textarea name="differential_diagnoses" id="differential_diagnoses" placeholder="Differential diagnoses" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="2"></textarea>
                    <textarea name="patient_progress" id="patient_progress" placeholder="Patient's progress" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="2"></textarea>
                    <textarea name="overall_status" id="overall_status" placeholder="Overall status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="2"></textarea>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Plan</label>
                    <textarea name="plan" id="plan" placeholder="Plan" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="3"></textarea>
                    <textarea name="prescription" id="prescription" placeholder="Prescription" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="2"></textarea>
                    <textarea name="test_request" id="test_request" placeholder="Test Request" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="2"></textarea>
                    <textarea name="follow_up_instructions" id="follow_up_instructions" placeholder="Follow-up instructions" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" rows="2"></textarea>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="follow_up_date">Follow Up Date</label>
                        <input type="date" name="follow_up_date" id="follow_up_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between mt-4">
                <button type="button" id="cancelSoapNoteBtn" class="px-4 py-2 text-gray-700 font-semibold rounded-md hover:bg-gray-100">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white font-semibold rounded-md hover:bg-emerald-700">Save SOAP Note</button>
            </div>
        </form>
        @endif
    </div>
</div>

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
            const vitalSigns = document.getElementById('vital_signs').value;
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
            formData.append('vital_signs', vitalSigns);
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
            document.getElementById('vital_signs').value = '';
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
