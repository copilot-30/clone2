<!-- SOAP Note Modal -->
<div id="soapNoteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
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
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Subjective
                    </label>
                    <div class="space-y-2">
                        <input type="text" name="subjective_cc" id="subjective_cc" placeholder="Chief complaint (CC)" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <textarea name="subjective_hpi" id="subjective_hpi" placeholder="History of present illness (HPI)" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2"></textarea>
                        <textarea name="subjective_ros" id="subjective_ros" placeholder="Review of systems (ROS)" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2"></textarea>
                        <textarea name="subjective_pmh" id="subjective_pmh" placeholder="Past medical history (PMH)" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2"></textarea>
                        <textarea name="subjective_sh" id="subjective_sh" placeholder="Social history (SH)" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2"></textarea>
                        <textarea name="subjective_fh" id="subjective_fh" placeholder="Family history (FH)" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2"></textarea>
                    </div>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Objective
                    </label>
                    <div class="space-y-2">
                        <input type="text" name="objective_vs" id="objective_vs" placeholder="Vital signs" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <textarea name="objective_pe" id="objective_pe" placeholder="Physical examination findings" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2"></textarea>
                        <textarea name="objective_lab" id="objective_lab" placeholder="Laboratory results" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2"></textarea>
                        <textarea name="objective_img" id="objective_img" placeholder="Imaging results" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2"></textarea>
                        <textarea name="objective_diag" id="objective_diag" placeholder="Other diagnostic data" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2"></textarea>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Assessment
                    </label>
                    <div class="space-y-2">
                        <textarea name="assessment_pl" id="assessment_pl" placeholder="Problem list" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2"></textarea>
                        <textarea name="assessment_dd" id="assessment_dd" placeholder="Differential diagnoses" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2"></textarea>
                        <textarea name="assessment_pp" id="assessment_pp" placeholder="Patient's progress" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2"></textarea>
                        <textarea name="assessment_os" id="assessment_os" placeholder="Overall status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2"></textarea>
                    </div>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Plan
                    </label>
                    <div class="space-y-2">
                        <textarea name="plan_fdt" id="plan_fdt" placeholder="Further diagnostic tests" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2"></textarea>
                        <textarea name="plan_therapeutics" id="plan_therapeutics" placeholder="Therapeutics" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2"></textarea>
                        <textarea name="plan_pe" id="plan_pe" placeholder="Patient education" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2"></textarea>
                        <textarea name="plan_fi" id="plan_fi" placeholder="Follow-up instructions" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="2"></textarea>
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

            const subjectiveCC = document.getElementById('subjective_cc').value;
            const subjectiveHPI = document.getElementById('subjective_hpi').value;
            const subjectiveROS = document.getElementById('subjective_ros').value;
            const subjectivePMH = document.getElementById('subjective_pmh').value;
            const subjectiveSH = document.getElementById('subjective_sh').value;
            const subjectiveFH = document.getElementById('subjective_fh').value;

            const objectiveVS = document.getElementById('objective_vs').value;
            const objectivePE = document.getElementById('objective_pe').value;
            const objectiveLab = document.getElementById('objective_lab').value;
            const objectiveImg = document.getElementById('objective_img').value;
            const objectiveDiag = document.getElementById('objective_diag').value;

            const assessmentPL = document.getElementById('assessment_pl').value;
            const assessmentDD = document.getElementById('assessment_dd').value;
            const assessmentPP = document.getElementById('assessment_pp').value;
            const assessmentOS = document.getElementById('assessment_os').value;

            const planFDT = document.getElementById('plan_fdt').value;
            const planTherapeutics = document.getElementById('plan_therapeutics').value;
            const planPE = document.getElementById('plan_pe').value;
            const planFI = document.getElementById('plan_fi').value;

            const subjective = `Chief Complaint: ${subjectiveCC}\nHistory of Present Illness: ${subjectiveHPI}\nReview of Systems: ${subjectiveROS}\nPast Medical History: ${subjectivePMH}\nSocial History: ${subjectiveSH}\nFamily History: ${subjectiveFH}`;
            const objective = `Vital Signs: ${objectiveVS}\nPhysical Exam: ${objectivePE}\nLaboratory Results: ${objectiveLab}\nImaging Results: ${objectiveImg}\nOther Diagnostic Data: ${objectiveDiag}`;
            const assessment = `Problem List: ${assessmentPL}\nDifferential Diagnoses: ${assessmentDD}\nPatient's Progress: ${assessmentPP}\nOverall Status: ${assessmentOS}`;
            const plan = `Further Diagnostic Tests: ${planFDT}\nTherapeutics: ${planTherapeutics}\nPatient Education: ${planPE}\nFollow-up Instructions: ${planFI}`;

            const formData = new FormData();
            formData.append('patient_id', this.querySelector('input[name="patient_id"]').value);
            formData.append('doctor_id', this.querySelector('input[name="doctor_id"]').value);
            formData.append('subjective', subjective);
            formData.append('objective', objective);
            formData.append('assessment', assessment);
            formData.append('plan', plan);

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

            document.getElementById('subjective_cc').value = '';
            document.getElementById('subjective_hpi').value = '';
            document.getElementById('subjective_ros').value = '';
            document.getElementById('subjective_pmh').value = '';
            document.getElementById('subjective_sh').value = '';
            document.getElementById('subjective_fh').value = '';

            document.getElementById('objective_vs').value = '';
            document.getElementById('objective_pe').value = '';
            document.getElementById('objective_lab').value = '';
            document.getElementById('objective_img').value = '';
            document.getElementById('objective_diag').value = '';

            document.getElementById('assessment_pl').value = '';
            document.getElementById('assessment_dd').value = '';
            document.getElementById('assessment_pp').value = '';
            document.getElementById('assessment_os').value = '';

            document.getElementById('plan_fdt').value = '';
            document.getElementById('plan_therapeutics').value = '';
            document.getElementById('plan_pe').value = '';
            document.getElementById('plan_fi').value = '';
        });
    }
});
</script>
@endpush