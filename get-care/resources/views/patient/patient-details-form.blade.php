@extends('patient_layout')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-2xl overflow-hidden p-8 lg:p-12">
        <h2 class="text-3xl font-bold text-emerald-600 mb-8 text-center">Patient Profile</h2>
        <h4 class="text-xl text-gray-700 mb-8 text-center">Please fill up to continue your journey with GetCare.</h4>
        <hr class="mb-8 border-gray-200">

        <form action="{{ route('patient.profile.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
            @csrf {{-- Laravel CSRF token --}}

            <!-- Left Column: Personal Information -->
            <div class="space-y-6">
                <h3 class="text-2xl font-semibold text-emerald-700 border-b pb-2 mb-4">Personal Information</h3>

                <div>
                    <label for="firstName" class="block text-gray-700 text-sm font-bold mb-2">First Name</label>
                    <input
                        type="text"
                        id="firstName"
                        name="firstName"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition duration-200"
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
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition duration-200"
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
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition duration-200"
                        value="{{ old('middleName', $patient->middle_name ?? '') }}"
                    />
                </div>
                <div>
                    <label for="suffix" class="block text-gray-700 text-sm font-bold mb-2">Suffix</label>
                    <input
                        type="text"
                        id="suffix"
                        name="suffix"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition duration-200"
                        value="{{ old('suffix', $patient->suffix ?? '') }}"
                    />
                </div>
                <div>
                    <label for="address" class="block text-gray-700 text-sm font-bold mb-2">Address</label>
                    <textarea
                        id="address"
                        name="address"
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition duration-200 resize-y"
                        required
                    >{{ old('address', $patient->address ?? '') }}</textarea>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="sex" class="block text-gray-700 text-sm font-bold mb-2">Sex</label>
                        <select
                            id="sex"
                            name="sex"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition duration-200"
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
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition duration-200"
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
                </div>
                <div>
                    <label for="dateOfBirth" class="block text-gray-700 text-sm font-bold mb-2">Date Of Birth</label>
                    <input
                        type="date"
                        id="dateOfBirth"
                        name="dateOfBirth"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition duration-200"
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
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition duration-200"
                        value="{{ old('mobileNumber', $patient->primary_mobile ?? '') }}"
                        required
                    />
                </div>
                <div>
                    <label for="bloodType" class="block text-gray-700 text-sm font-bold mb-2">Blood Type</label>
                    <select
                        id="bloodType"
                        name="bloodType"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition duration-200"
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
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition duration-200"
                        value="{{ old('philhealthNo', $patient->philhealth_no ?? '') }}"
                    />
                </div>
            </div>

            <!-- Right Column: Medical Information -->
            <div class="space-y-6">
                <h3 class="text-2xl font-semibold text-emerald-700 border-b pb-2 mb-4">Medical Information</h3>

                <div>
                    <label for="knownMedicalCondition" class="block text-gray-700 text-sm font-bold mb-2">KNOWN MEDICAL CONDITION</label>
                    <textarea
                        id="knownMedicalCondition"
                        name="knownMedicalCondition"
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition duration-200 resize-y"
                    >{{ old('knownMedicalCondition', $patient->medical_conditions ?? '') }}</textarea>
                </div>
                <div>
                    <label for="allergies" class="block text-gray-700 text-sm font-bold mb-2">ALLERGIES</label>
                    <textarea
                        id="allergies"
                        name="allergies"
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition duration-200 resize-y"
                    >{{ old('allergies', $patient->allergies ?? '') }}</textarea>
                </div>
                <div>
                    <label for="previousSurgeries" class="block text-gray-700 text-sm font-bold mb-2">PREVIOUS SURGERIES</label>
                    <textarea
                        id="previousSurgeries"
                        name="previousSurgeries"
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition duration-200 resize-y"
                    >{{ old('previousSurgeries', $patient->surgeries ?? '') }}</textarea>
                </div>
                <div>
                    <label for="familyHistory" class="block text-gray-700 text-sm font-bold mb-2">FAMILY HISTORY</label>
                    <textarea
                        id="familyHistory"
                        name="familyHistory"
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition duration-200 resize-y"
                    >{{ old('familyHistory', $patient->family_history ?? '') }}</textarea>
                </div>
                <div>
                    <label for="medication" class="block text-gray-700 text-sm font-bold mb-2">MEDICATION</label>
                    <textarea
                        id="medication"
                        name="medication"
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition duration-200 resize-y"
                    >{{ old('medication', $patient->medications ?? '') }}</textarea>
                </div>
                <div>
                    <label for="supplements" class="block text-gray-700 text-sm font-bold mb-2">SUPPLEMENTS</label>
                    <textarea
                        id="supplements"
                        name="supplements"
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition duration-200 resize-y"
                    >{{ old('supplements', $patient->supplements ?? '') }}</textarea>
                </div>
            </div>

            <!-- Submit Button (Full Width) -->
            <div class="md:col-span-2 flex justify-center mt-8">
                <button
                    type="submit"
                    class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 px-10 rounded-full shadow-lg transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-opacity-50"
                >
                    <i class="fas fa-save mr-2"></i> Submit
                </button>
            </div>
        </form>
    </div>
</div>
@endsection