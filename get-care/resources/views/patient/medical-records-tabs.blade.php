<div class="bg-white shadow rounded-lg p-6 mb-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold text-gray-800">My Medical Records</h2>
        <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded inline-flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Download
        </button>
    </div>

    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <a href="#" data-tab-target="doctor-notes-tab-content" class="border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Doctor Notes</a>
            <a href="#" data-tab-target="prescriptions" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Prescriptions</a>
            <a href="#" data-tab-target="lab_request" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Lab Requests</a>
            <a href="#" data-tab-target="lab_results" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Lab Results</a>
        </nav>
    </div>
 
    <div class="mt-6">
        <div id="doctor-notes-tab-content" class="tab-pane">
            @include('patient.recent-notes')
        </div>
        <div id="prescriptions" class="tab-pane hidden">    
            @foreach(auth()->user()->patient->patientPrescriptions as $p)
            <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50">
                <div class="flex justify-between items-start">
                    <h4 class="font-medium text-gray-800">{{ $p->content }}</h4>
                    <span class="text-xs text-gray-500">{{ $p->created_at->format('M j, Y') }}</span>
                </div>
                
                <div class="mt-2 text-xs text-gray-500">
                    Dr. {{ $p->doctor->first_name ?? '' }} {{ $p->doctor->last_name ?? '' }}
                </div>
            </div>
            @endforeach
        </div>
        <div id="lab_request" class="tab-pane hidden">
            @foreach(auth()->user()->patient->patientTestRequests as $p)
            <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50">
                <div class="flex justify-between items-start">
                    <h4 class="font-medium text-gray-800">{{ $p->content }}</h4>
                    <span class="text-xs text-gray-500">{{ $p->created_at->format('M j, Y') }}</span>
                </div>
                
                <div class="mt-2 text-xs text-gray-500">
                    Dr. {{ $p->doctor->first_name ?? '' }} {{ $p->doctor->last_name ?? '' }}
                </div>
            </div>
            @endforeach
        </div>
        <div id="lab_results" class="tab-pane hidden">
        </div>
    </div>
    
</div>


