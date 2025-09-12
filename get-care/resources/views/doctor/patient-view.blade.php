@extends('admin_layout')

@section('content')
<div class="p-4">
    <div class="flex bg-gray-100 rounded-lg shadow-xl min-h-[80vh]">
        <!-- Left Patient List Panel -->
        <div class="w-1/4 bg-white border-r border-gray-200 p-4">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Patients</h2>
            <div class="mb-4">
                <div class="relative">
                    <input type="text" placeholder="Search patients..." class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <div class="flex border-b border-gray-200 mb-4">
                <button class="px-4 py-2 text-emerald-600 border-b-2 border-emerald-600 font-semibold">My Patients</button>
                <button class="px-4 py-2 text-gray-600 hover:text-emerald-600">Shared Cases</button>
            </div>

            <!-- Patient List Items (Example) -->
            <div class="space-y-3">
                <div class="flex items-center p-3 bg-emerald-50 rounded-lg shadow-sm cursor-pointer border border-emerald-300">
                    <div class="w-10 h-10 bg-purple-200 rounded-full flex items-center justify-center text-purple-700 font-bold mr-3">JS</div>
                    <div>
                        <p class="font-semibold text-gray-800">John Smith</p>
                        <p class="text-sm text-gray-600">40 years old • Male • A+</p>
                    </div>
                </div>
                <div class="flex items-center p-3 bg-white rounded-lg shadow-sm cursor-pointer hover:bg-gray-50">
                    <div class="w-10 h-10 bg-blue-200 rounded-full flex items-center justify-center text-blue-700 font-bold mr-3">MG</div>
                    <div>
                        <p class="font-semibold text-gray-800">Maria Garcia</p>
                        <p class="text-sm text-gray-600">35 years old • Female • O+</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Patient Details Panel -->
        <div class="w-3/4 p-6 bg-white">
            <div class="flex items-center mb-6">
                <div class="w-16 h-16 bg-purple-200 rounded-full flex items-center justify-center text-purple-700 font-bold text-2xl mr-4">JS</div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">John Smith</h1>
                    <p class="text-lg text-gray-700">40 years old • Male • A+</p>
                </div>
            </div>

            <!-- Tabs for Patient Information -->
            <div class="border-b border-gray-200 mb-6">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-gray-600 hover:text-gray-900 border-transparent" data-tab="basic-information">Basic Information</button>
                    <button class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-gray-600 hover:text-gray-900 border-transparent" data-tab="medical-background">Medical Background</button>
                    <button class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-gray-600 hover:text-gray-900 border-transparent" data-tab="soap-notes">SOAP Notes</button>
                    <button class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-gray-600 hover:text-gray-900 border-transparent" data-tab="notes">Notes</button>
                    <button class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-emerald-600 border-emerald-600" data-tab="doctors">Doctors</button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div id="tab-content">
                <!-- Doctors Tab Content -->
                <div id="doctors-tab" class="tab-pane">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Doctors</h3>
                    <div class="space-y-4">
                        <!-- Primary Doctor -->
                        <div class="flex items-center p-4 bg-white rounded-lg shadow-sm border border-gray-200">
                            <div class="w-10 h-10 bg-yellow-200 rounded-full flex items-center justify-center text-yellow-700 font-bold mr-3">Y</div>
                            <div>
                                <p class="font-semibold text-gray-800">You (Dr. Daniel)</p>
                                <p class="text-sm text-gray-600">Primary Doctor</p>
                            </div>
                        </div>

                        <!-- Referring Doctor 1 -->
                        <div class="flex items-center p-4 bg-white rounded-lg shadow-sm border border-gray-200 justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-purple-200 rounded-full flex items-center justify-center text-purple-700 font-bold mr-3">DJ</div>
                                <div>
                                    <p class="font-semibold text-gray-800">Dr. Jane Doe</p>
                                    <p class="text-sm text-gray-600">Referring Doctor</p>
                                    <p class="text-sm text-emerald-600">Cardiology</p>
                                    <p class="text-sm text-gray-500">jane.doe@email.com</p>
                                </div>
                            </div>
                            <button class="text-red-600 hover:text-red-800 text-sm">Remove</button>
                        </div>

                        <!-- Referring Doctor 2 -->
                        <div class="flex items-center p-4 bg-white rounded-lg shadow-sm border border-gray-200 justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-purple-200 rounded-full flex items-center justify-center text-purple-700 font-bold mr-3">DJ</div>
                                <div>
                                    <p class="font-semibold text-gray-800">Dr. John Smith</p>
                                    <p class="text-sm text-gray-600">Referring Doctor</p>
                                    <p class="text-sm text-emerald-600">Neurology</p>
                                    <p class="text-sm text-gray-500">john.smith@email.com</p>
                                </div>
                            </div>
                            <button class="text-red-600 hover:text-red-800 text-sm">Remove</button>
                        </div>
                    </div>
                    <button class="mt-6 px-6 py-3 bg-gray-800 text-white font-semibold rounded-lg shadow hover:bg-gray-700">Add Doctor</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('nav button');
        const tabContent = document.getElementById('tab-content');

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs
                tabs.forEach(t => {
                    t.classList.remove('border-emerald-600', 'text-emerald-600');
                    t.classList.add('border-transparent', 'text-gray-600', 'hover:text-gray-900');
                });

                // Add active class to clicked tab
                this.classList.add('border-emerald-600', 'text-emerald-600');
                this.classList.remove('border-transparent', 'text-gray-600', 'hover:text-gray-900');

                // Hide all tab panes
                Array.from(tabContent.children).forEach(pane => {
                    pane.style.display = 'none';
                });

                // Show the corresponding tab pane
                const targetTab = this.dataset.tab;
                const targetPane = document.getElementById(targetTab + '-tab');
                if (targetPane) {
                    targetPane.style.display = 'block';
                }
            });
        });

        // Set initial active tab (Doctors tab in this case, based on the image)
        const initialActiveTab = document.querySelector('button[data-tab="doctors"]');
        if (initialActiveTab) {
            initialActiveTab.click();
        }
    });
</script>
@endsection