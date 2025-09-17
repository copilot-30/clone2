<div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
    <div class="bg-emerald-600 text-white px-6 py-3   flex justify-between items-center">
        <h2 class="text-lg font-semibold">PROFILE</h2>
        <a href="{{ route('patient-details') }}" class="text-white hover:text-emerald-100 transition-colors text-sm font-medium">EDIT</a>
    </div>

    <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="flex flex-col items-center justify-center">
            <div class="w-40 h-40 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden border border-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                
            
            </div>
            <p class="text-sm text-gray-500 mt-4">ID: {{ Auth::user()->patient->id ?? 'N/A' }}</p>
            <h3 class="text-xl font-bold text-gray-800 mt-1">{{ Auth::user()->patient->first_name ?? '' }} {{ Auth::user()->patient->last_name ?? '' }}</h3>
            <p class="text-gray-600 text-sm">{{ Auth::user()->email }}</p>
         
                @if(Auth::user()->patient->is_member)
                    <span class="bg-green-100 text-green-800 text-lg font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-600">
                        {{ Auth::user()->patient->subscription->name ?? 'N/A' }}
                    </span>
                @else 
                    <p class="bg-red-100 text-white text-lg font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-600">
                        <i class="fa fa-user"></i> Free Member

                    </p>
                    <div>
                        <a href="{{ route('patient.plans') }}" class="text-emerald-500 hover:text-emerald-600 transition-colors text-lg font-medium">
                            <i class="fa fa-unlock"></i> Unlock Features, Clich here to view Plans
                        </a>
                    </div>
                @endif
 
        </div>

        <div class="col-span-2 grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500">Full Name</p>
                <p class="font-bold text-gray-800">
 
                    {{ ucwords(str_replace(['  ', '   '], ' ', trim(Auth::user()->patient->first_name ?? '' . ' ' . Auth::user()->patient->middle_name ?? ''))) }} {{ ucfirst(Auth::user()->patient->last_name) ?? '' }} {{ ucfirst(Auth::user()->patient->suffix) ?? '' }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Blood Type</p>
                <p class="font-bold text-gray-800">{{ Auth::user()->patient->blood_type ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Sex</p>
                <p class="font-bold text-gray-800">{{ Auth::user()->patient->sex ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Date of Birth</p>
                <p class="font-bold text-gray-800">{{ Auth::user()->patient->date_of_birth ? \Carbon\Carbon::parse(Auth::user()->patient->date_of_birth)->format('F j Y') : 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Age</p>
                <p class="font-bold text-gray-800">{{ Auth::user()->patient->date_of_birth ? \Carbon\Carbon::parse(Auth::user()->patient->date_of_birth)->age : 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Mobile Number</p>
                <p class="font-bold text-gray-800">{{ Auth::user()->patient->primary_mobile ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Address</p>
                <p class="font-bold text-gray-800">{{ Auth::user()->patient->address ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Civil Status</p>
                <p class="font-bold text-gray-800">{{ Auth::user()->patient->civil_status ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="col-span-3 border-t border-gray-200 pt-6 mt-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">KNOWN MEDICAL CONDITION</p>
                    <p class="font-bold text-gray-800">{{ Auth::user()->patient->medical_conditions ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">ALLERGIES</p>
                    <p class="font-bold text-gray-800">{{ Auth::user()->patient->allergies ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">PREVIOUS SURGERIES</p>
                    <p class="font-bold text-gray-800">{{ Auth::user()->patient->surgeries ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">FAMILY HISTORY</p>
                    <p class="font-bold text-gray-800">{{ Auth::user()->patient->family_history ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">MEDICATION</p>
                    <p class="font-bold text-gray-800">{{ Auth::user()->patient->medications ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">SUPPLEMENTS</p>
                    <p class="font-bold text-gray-800">{{ Auth::user()->patient->supplements ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>