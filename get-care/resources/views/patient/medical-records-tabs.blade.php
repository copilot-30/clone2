<div class="bg-white shadow rounded-lg p-6 mb-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold text-gray-800">My Medical Records</h2>
        <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded inline-flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Download
        </button>
    </div>
<div class="text-gray-600 mb-6">
    <span class="text-gray-800 font-semibold">{{ ucwords(auth()->user()->patient->first_name . ' ' . auth()->user()->patient->last_name) }}</span>
     • Patient ID: {{ auth()->user()->patient->id ?? 'N/A' }}
</div>

<div class="border-b border-gray-200">
    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
        <a href="#" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab-target="all-records">All Records</a>
        <a href="#" class="tab-button border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab-target="doctor-notes">Doctor Notes</a>
        <a href="#" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab-target="prescriptions">Prescriptions</a>
        <a href="#" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab-target="lab-requests">Lab Requests</a>
        <a href="#" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab-target="lab-results">Lab Results</a>
    </nav>
</div>

<div class="mt-6">
    <div id="all-records" class="tab-content hidden">
        @include('patient.medical-records.all-records')
    </div>
    <div id="doctor-notes" class="tab-content">
        @include('patient.medical-records.doctor-notes')
    </div>
    <div id="prescriptions" class="tab-content hidden">
        @include('patient.medical-records.prescriptions')
    </div>
    <div id="lab-requests" class="tab-content hidden">
        @include('patient.medical-records.lab-requests')
    </div>
    <div id="lab-results" class="tab-content hidden">
        @include('patient.medical-records.lab-results')
    </div>
</div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            // Deactivate all tab buttons
            tabButtons.forEach(btn => {
                btn.classList.remove('border-blue-500', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            });

            // Hide all tab contents
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });

            // Activate the clicked tab button
            this.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            this.classList.add('border-blue-500', 'text-blue-600');

            // Show the corresponding tab content
            const targetId = this.dataset.tabTarget;
            const targetContent = document.getElementById(targetId);
            if (targetContent) {
                targetContent.classList.remove('hidden');
            }
        });
    });

    // Set default active tab (Doctor Notes)
    const defaultActiveTab = document.querySelector('[data-tab-target="doctor-notes"]');
    if (defaultActiveTab) {
        defaultActiveTab.click();
    }
});
</script>
@endpush

