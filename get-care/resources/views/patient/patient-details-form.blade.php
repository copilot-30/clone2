@extends('patient_layout')

 

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 p-4">
    <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-5xl">
        <h2 class="text-3xl font-bold text-emerald-600 mb-8 text-center">Patient Profile</h2>
        <h4 class="text-1xl text-mb-4 text-center">Please fill up to continue</h4>
        <br/>
        <hr/>
        <br/>
        <form action="{{ route('patient.profile.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @csrf {{-- Laravel CSRF token --}}

            <!-- Left Column -->
            <div class="space-y-6">
                <div>
                     
                    <input
                        <label for="firstName" class="block text-gray-700 text-sm font-bold mb-2">First Name</label>
                        <input
                            type="text"
                            id="firstName"
                            name="firstName"
                            class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2"
                            value="{{ old('firstName', $patient->first_name ?? '') }}"
                            required
                        />
                    </div>
                    <div>
                        <label for="lastName" class="block text-gray-700 text-sm font-bold mb-2">Last Name</label>
                        <input
                            type="text"
                            id="lastName"
                            name="lastName"
                            class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2"
                            value="{{ old('lastName', $patient->last_name ?? '') }}"
                            required
                        />
                    </div>
                    <div>
                        <label for="middleName" class="block text-gray-700 text-sm font-bold mb-2">Middle Name</label>
                        <input
                            type="text"
                            id="middleName"
                            name="middleName"
                            class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2"
                            value="{{ old('middleName', $patient->middle_name ?? '') }}"
                        />
                    </div>
                    <div>
                        <label for="suffix" class="block text-gray-700 text-sm font-bold mb-2">Suffix</label>
                        <input
                            type="text"
                            id="suffix"
                            name="suffix"
                            class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2"
                            value="{{ old('suffix', $patient->suffix ?? '') }}"
                        />
                    </div>
                    <div>
                        <label for="address" class="block text-gray-700 text-sm font-bold mb-2">Address</label>
                        <textarea
                            id="address"
                            name="address"
                            class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2 h-24 resize-none"
                            required
                        >{{ old('address', $patient->address ?? '') }}</textarea>
                    </div>
                    <div>
                        <label for="sex" class="block text-gray-700 text-sm font-bold mb-2">Sex</label>
                        <select
                            id="sex"
                            name="sex"
                            class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2"
                            required
                        >
                            <option value="">Select</option>
                            <option value="Male" {{ (old('sex', $patient->sex ?? '') == 'Male') ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ (old('sex', $patient->sex ?? '') == 'Female') ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ (old('sex', $patient->sex ?? '') == 'Other') ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div>
                        <label for="civilStatus" class="block text-gray-700 text-sm font-bold mb-2">Civil Status</label>
                        <select
                            id="civilStatus"
                            name="civilStatus"
                            class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2"
                            required
                        >
                            <option value="">Select</option>
                            <option value="Single" {{ (old('civilStatus', $patient->civil_status ?? '') == 'Single') ? 'selected' : '' }}>Single</option>
                            <option value="Married" {{ (old('civilStatus', $patient->civil_status ?? '') == 'Married') ? 'selected' : '' }}>Married</option>
                            <option value="Widowed" {{ (old('civilStatus', $patient->civil_status ?? '') == 'Widowed') ? 'selected' : '' }}>Widowed</option>
                            <option value="Separated" {{ (old('civilStatus', $patient->civil_status ?? '') == 'Separated') ? 'selected' : '' }}>Separated</option>
                            <option value="Divorced" {{ (old('civilStatus', $patient->civil_status ?? '') == 'Divorced') ? 'selected' : '' }}>Divorced</option>
                        </select>
                    </div>
                    <div>
                        <label for="dateOfBirth" class="block text-gray-700 text-sm font-bold mb-2">Date Of Birth</label>
                        <input
                            type="date"
                            id="dateOfBirth"
                            name="dateOfBirth"
                            class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2"
                            value="{{ old('dateOfBirth', $patient->date_of_birth ? $patient->date_of_birth->format('Y-m-d') : '') }}"
                            required
                        />
                    </div>
                    <div>
                        <label for="mobileNumber" class="block text-gray-700 text-sm font-bold mb-2">Mobile Number</label>
                        <input
                            type="text"
                            id="mobileNumber"
                            name="mobileNumber"
                            class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2"
                            value="{{ old('mobileNumber', $patient->primary_mobile ?? '') }}"
                            required
                        />
                    </div>
                    <div>
                        <label for="bloodType" class="block text-gray-700 text-sm font-bold mb-2">Blood Type</label>
                        <select
                            id="bloodType"
                            name="bloodType"
                            class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2"
                        >
                            <option value="">Select</option>
                            <option value="A+" {{ (old('bloodType', $patient->blood_type ?? '') == 'A+') ? 'selected' : '' }}>A+</option>
                            <option value="A-" {{ (old('bloodType', $patient->blood_type ?? '') == 'A-') ? 'selected' : '' }}>A-</option>
                            <option value="B+" {{ (old('bloodType', $patient->blood_type ?? '') == 'B+') ? 'selected' : '' }}>B+</option>
                            <option value="B-" {{ (old('bloodType', $patient->blood_type ?? '') == 'B-') ? 'selected' : '' }}>B-</option>
                            <option value="AB+" {{ (old('bloodType', $patient->blood_type ?? '') == 'AB+') ? 'selected' : '' }}>AB+</option>
                            <option value="AB-" {{ (old('bloodType', $patient->blood_type ?? '') == 'AB-') ? 'selected' : '' }}>AB-</option>
                            <option value="O+" {{ (old('bloodType', $patient->blood_type ?? '') == 'O+') ? 'selected' : '' }}>O+</option>
                            <option value="O-" {{ (old('bloodType', $patient->blood_type ?? '') == 'O-') ? 'selected' : '' }}>O-</option>
                        </select>
                    </div>
                    <div>
                        <label for="philhealthNo" class="block text-gray-700 text-sm font-bold mb-2">PHILHEALTH NO.</label>
                        <input
                            type="text"
                            id="philhealthNo"
                            name="philhealthNo"
                            class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2"
                            value="{{ old('philhealthNo', $patient->philhealth_no ?? '') }}"
                        />
                    </div>
                </div>
    
                <!-- Right Column -->
                <div class="space-y-6">
                    <div>
                        <label for="knownMedicalCondition" class="block text-gray-700 text-sm font-bold mb-2">KNOWN MEDICAL CONDITION</label>
                        <textarea
                            id="knownMedicalCondition"
                            name="knownMedicalCondition"
                            class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2 h-24 resize-none"
                        >{{ old('knownMedicalCondition', $patient->medical_conditions ?? '') }}</textarea>
                    </div>
                    <div>
                        <label for="allergies" class="block text-gray-700 text-sm font-bold mb-2">ALLERGIES</label>
                        <textarea
                            id="allergies"
                            name="allergies"
                            class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2 h-24 resize-none"
                        >{{ old('allergies', $patient->allergies ?? '') }}</textarea>
                    </div>
                    <div>
                        <label for="previousSurgeries" class="block text-gray-700 text-sm font-bold mb-2">PREVIOUS SURGERIES</label>
                        <textarea
                            id="previousSurgeries"
                            name="previousSurgeries"
                            class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2 h-24 resize-none"
                        >{{ old('previousSurgeries', $patient->surgeries ?? '') }}</textarea>
                    </div>
                    <div>
                        <label for="familyHistory" class="block text-gray-700 text-sm font-bold mb-2">FAMILY HISTORY</label>
                        <textarea
                            id="familyHistory"
                            name="familyHistory"
                            class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2 h-24 resize-none"
                        >{{ old('familyHistory', $patient->family_history ?? '') }}</textarea>
                    </div>
                    <div>
                        <label for="medication" class="block text-gray-700 text-sm font-bold mb-2">MEDICATION</label>
                        <textarea
                            id="medication"
                            name="medication"
                            class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2 h-24 resize-none"
                        >{{ old('medication', $patient->medications ?? '') }}</textarea>
                    </div>
                    <div>
                        <label for="supplements" class="block text-gray-700 text-sm font-bold mb-2">SUPPLEMENTS</label>
                        <textarea
                            id="supplements"
                            name="supplements"
                            class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2 h-24 resize-none"
                        >{{ old('supplements', $patient->supplements ?? '') }}</textarea>
                    </div>
                    <div class="flex justify-end mt-8">
                        <button
                            type="submit"
                            class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 px-8 rounded-full shadow-lg transition-all duration-300"
                        >
                            SUBMIT
                        </button>
                    </div>
                </div>
</div>
@endsection