@extends('admin_layout')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 h-12 w-12 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 font-bold text-xl mr-3">
                DR
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Shared Cases</h1>
                <p class="text-gray-600">Dr. Maria Rodriguez • Internal Medicine</p>
            </div>
        </div>
        <div class="flex space-x-4">
            <div class="text-center">
                <p class="text-lg font-bold">8</p>
                <p class="text-sm text-gray-500">Pending</p>
            </div>
            <div class="text-center">
                <p class="text-lg font-bold">24</p>
                <p class="text-sm text-gray-500">Active</p>
            </div>
            <div class="text-center">
                <p class="text-lg font-bold">156</p>
                <p class="text-sm text-gray-500">Total</p>
            </div>
        </div>
    </div>

    <div class="border-b border-gray-200 mb-6">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <a href="#" class="border-indigo-500 text-indigo-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" aria-current="page">
                All Requests
            </a>
            <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Pending (8)
            </a>
            <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Accepted
            </a>
            <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Declined
            </a>
        </nav>
    </div>

    <div class="space-y-6">
        <!-- Urgent Case Card -->
        <div class="bg-white shadow-md rounded-lg p-6 relative">
            <div class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                Urgent
            </div>
            <div class="absolute top-4 right-4 text-gray-500 text-sm">
                2 hours ago
            </div>

            <div class="flex items-center mb-4 mt-8">
                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 font-bold text-lg">
                    DC
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">Dr. Lisa Chen</p>
                    <p class="text-sm text-gray-500">Cardiology • City General Hospital</p>
                </div>
            </div>

            <h3 class="text-lg font-semibold text-gray-800 mb-2">
                Patient: Sarah Johnson (F, 45)
            </h3>

            <p class="text-gray-700 mb-4">
                <strong class="font-medium">Collaboration Request:</strong> Complex case involving chest pain with abnormal ECG findings. Patient has history of diabetes and hypertension. Requesting internal medicine consultation for comprehensive metabolic evaluation and medication management.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-2 mb-4">
                <div>
                    <p class="text-sm font-medium text-gray-600">Symptoms:</p>
                    <p class="text-sm text-gray-800">Chest pain, shortness of breath, fatigue</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Duration:</p>
                    <p class="text-sm text-gray-800">3 weeks</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Tests Done:</p>
                    <p class="text-sm text-gray-800">ECG, Chest X-ray, Basic metabolic panel</p>
                </div>
            </div>

            <div class="flex space-x-2 mb-4">
                <a href="#" class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    ECG_Results.pdf
                </a>
                <a href="#" class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Lab_Results.pdf
                </a>
            </div>

            <div class="flex justify-end space-x-3 mt-4">
                <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    View Details
                </button>
                <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Decline
                </button>
                <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                    Accept Collaboration
                </button>
            </div>
        </div>

        <!-- Normal Case Card -->
        <div class="bg-white shadow-md rounded-lg p-6 relative">
            <div class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                Normal
            </div>
            <div class="absolute top-4 right-4 text-gray-500 text-sm">
                5 hours ago
            </div>

            <div class="flex items-center mb-4 mt-8">
                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 font-bold text-lg">
                    DJ
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">Dr. Michael Johnson</p>
                    <p class="text-sm text-gray-500">Orthopedics • Metro Medical Center</p>
                </div>
            </div>

            <h3 class="text-lg font-semibold text-gray-800 mb-2">
                Patient: Robert Martinez (M, 62)
            </h3>

            <p class="text-gray-700 mb-4">
                <strong class="font-medium">Collaboration Request:</strong> Routine follow-up for post-surgical recovery. Patient is stable but requires monitoring for rehabilitation progress and pain management.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-2 mb-4">
                <div>
                    <p class="text-sm font-medium text-gray-600">Symptoms:</p>
                    <p class="text-sm text-gray-800">Mild pain, limited mobility</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Duration:</p>
                    <p class="text-sm text-gray-800">Ongoing</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Tests Done:</p>
                    <p class="text-sm text-gray-800">Physical therapy assessment, X-ray</p>
                </div>
            </div>

            <div class="flex space-x-2 mb-4">
                <a href="#" class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    PT_Report.pdf
                </a>
                <a href="#" class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    XRay_Scan.pdf
                </a>
            </div>

            <div class="flex justify-end space-x-3 mt-4">
                <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    View Details
                </button>
                <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Decline
                </button>
                <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                    Accept Collaboration
                </button>
            </div>
        </div>
    </div>
</div>
@endsection