<div class="bg-white rounded-lg shadow-xl overflow-hidden mb-8">
    <div class="bg-gradient-to-r from-emerald-500 to-green-600 text-white px-6 py-4 flex justify-between items-center">
        <h2 class="text-xl font-bold">Patient Profile</h2>
        <a href="{{ route('patient-details') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-emerald-700 hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
            <i class="fas fa-edit mr-2"></i> Edit Profile
        </a>
    </div>

    <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Profile Picture and Membership --}}
        <div class="flex flex-col items-center justify-center border-r border-gray-200 lg:col-span-1">
            <div class="w-40 h-40 rounded-full bg-gray-100 flex items-center justify-center overflow-hidden border-4 border-emerald-300 shadow-lg">
                {{-- Placeholder for avatar or actual image --}}
                <svg class="w-24 h-24 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM12 12A6 6 0 1012 0a6 6 0 000 12z"/>
                </svg>
            </div>
            <p class="text-sm text-gray-500 mt-4">Patient ID: <span class="font-medium text-gray-700">{{ Auth::user()->patient->id ?? 'N/A' }}</span></p>
            <h3 class="text-2xl font-bold text-gray-800 mt-2 text-center">{{ Auth::user()->patient->first_name ?? '' }} {{ Auth::user()->patient->last_name ?? '' }}</h3>
            <p class="text-gray-600 text-sm">{{ Auth::user()->email }}</p>
            
            <div class="mt-4">
                @if(Auth::user()->patient && Auth::user()->patient->subscriptions)
                    <p class="bg-emerald-100 text-emerald-800 text-base font-medium px-3 py-1 rounded-full">
                        {{ Auth::user()->patient->subscriptions->plan->name ?? 'N/A' }} Member
</p>
                @else 
                    <p class="bg-gray-200 text-gray-800 text-base text-center font-medium px-3 py-1 rounded-full">
                        Free Member
</p>
                    <p class="mt-2 text-center">
                        <a href="{{ route('patient.plans') }}" class="text-indigo-600 hover:text-indigo-800 transition-colors text-base font-semibold">
                            <i class="fas fa-lock-open mr-1"></i> Unlock Premium Features
                        </a>
</p>
                @endif
            </div>
        </div>

        {{-- Personal Details --}}
        <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
            <h2 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-2 col-span-full">Personal Information</h2>
            <dl>
                <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                <dd class="mt-1 text-gray-900 font-semibold">
                    {{ Auth::user()->patient->full_name ?? 'N/A' }}
                </dd>
            </dl>
            <dl>
                <dt class="text-sm font-medium text-gray-500">Blood Type</dt>
                <dd class="mt-1 text-gray-900 font-semibold">{{ Auth::user()->patient->blood_type ?? 'N/A' }}</dd>
            </dl>
            <dl>
                <dt class="text-sm font-medium text-gray-500">Sex</dt>
                <dd class="mt-1 text-gray-900 font-semibold">{{ Auth::user()->patient->sex ?? 'N/A' }}</dd>
            </dl>
            <dl>
                <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                <dd class="mt-1 text-gray-900 font-semibold">{{ Auth::user()->patient->date_of_birth ? \Carbon\Carbon::parse(Auth::user()->patient->date_of_birth)->format('F j, Y') : 'N/A' }}</dd>
            </dl>
            <dl>
                <dt class="text-sm font-medium text-gray-500">Age</dt>
                <dd class="mt-1 text-gray-900 font-semibold">{{ Auth::user()->patient->age ?? 'N/A' }}</dd>
            </dl>
            <dl>
                <dt class="text-sm font-medium text-gray-500">Mobile Number</dt>
                <dd class="mt-1 text-gray-900 font-semibold">{{ Auth::user()->patient->primary_mobile ?? 'N/A' }}</dd>
            </dl>
            <dl>
                <dt class="text-sm font-medium text-gray-500">Address</dt>
                <dd class="mt-1 text-gray-900 font-semibold">{{ Auth::user()->patient->address ?? 'N/A' }}</dd>
            </dl>
            <dl>
                <dt class="text-sm font-medium text-gray-500">Civil Status</dt>
                <dd class="mt-1 text-gray-900 font-semibold">{{ Auth::user()->patient->civil_status ?? 'N/A' }}</dd>
            </dl>
        </div>
    </div>

    {{-- Medical Information --}}
    <div class="px-6 py-8 border-t border-gray-200">
        <h2 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-2">Medical Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
            <dl>
                <dt class="text-sm font-medium text-gray-500">Known Medical Conditions</dt>
                <dd class="mt-1 text-gray-900">{{ Auth::user()->patient->medical_conditions ?? 'N/A' }}</dd>
            </dl>
            <dl>
                <dt class="text-sm font-medium text-gray-500">Allergies</dt>
                <dd class="mt-1 text-gray-900">{{ Auth::user()->patient->allergies ?? 'N/A' }}</dd>
            </dl>
            <dl>
                <dt class="text-sm font-medium text-gray-500">Previous Surgeries</dt>
                <dd class="mt-1 text-gray-900">{{ Auth::user()->patient->surgeries ?? 'N/A' }}</dd>
            </dl>
            <dl>
                <dt class="text-sm font-medium text-gray-500">Family History</dt>
                <dd class="mt-1 text-gray-900">{{ Auth::user()->patient->family_history ?? 'N/A' }}</dd>
            </dl>
            <dl>
                <dt class="text-sm font-medium text-gray-500">Medications</dt>
                <dd class="mt-1 text-gray-900">{{ Auth::user()->patient->medications ?? 'N/A' }}</dd>
            </dl>
            <dl>
                <dt class="text-sm font-medium text-gray-500">Supplements</dt>
                <dd class="mt-1 text-gray-900">{{ Auth::user()->patient->supplements ?? 'N/A' }}</dd>
            </dl>
        </div>
    </div>
</div>