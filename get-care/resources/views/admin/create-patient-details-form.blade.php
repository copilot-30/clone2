 
<!-- <div class="min-h-screen flex items-center justify-center bg-gray-100 p-4"> -->
    <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-5xl">
        <h2 class="text-3xl font-bold text-emerald-600 mb-8 text-center">Create Patient Profile</h2>
        <h4 class="text-1l font-bold  text-center">{{ $user->email }}</h4>
        <br/>
        <hr/>
        <br/>
        <form action="{{ route('admin.patients.store_details', ['user_id' => $user->id]) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @csrf {{-- Laravel CSRF token --}}

            <!-- Left Column -->
            <div class="space-y-6">
                <h3 class="text-2xl font-semibold text-emerald-700 border-b pb-2 mb-4">Personal Information</h3>
                <div>
                    <label for="firstName" class="block text-gray-700 text-sm font-bold mb-2">First Name</label>
                    <input
                        type="text"
                        id="firstName"
                        name="firstName"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition duration-200"
                        value="{{ old('firstName') }}"
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
                        value="{{ old('lastName') }}"
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
                        value="{{ old('middleName') }}"
                    />
                </div>
                <div>
                    <label for="suffix" class="block text-gray-700 text-sm font-bold mb-2">Suffix</label>
                    <input
                        type="text"
                        id="suffix"
                        name="suffix"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition duration-200"
                        value="{{ old('suffix') }}"
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
                    >{{ old('address') }}</textarea>
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
                            <option value="Male" {{ (old('sex') == 'Male') ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ (old('sex') == 'Female') ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ (old('sex') == 'Other') ? 'selected' : '' }}>Other</option>
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
                            <option value="Single" {{ (old('civilStatus') == 'Single') ? 'selected' : '' }}>Single</option>
                            <option value="Married" {{ (old('civilStatus') == 'Married') ? 'selected' : '' }}>Married</option>
                            <option value="Widowed" {{ (old('civilStatus') == 'Widowed') ? 'selected' : '' }}>Widowed</option>
                            <option value="Separated" {{ (old('civilStatus') == 'Separated') ? 'selected' : '' }}>Separated</option>
                            <option value="Divorced" {{ (old('civilStatus') == 'Divorced') ? 'selected' : '' }}>Divorced</option>
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
                        value="{{ old('dateOfBirth') }}"
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
                        value="{{ old('mobileNumber') }}"
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
                        <option value="A+" {{ (old('bloodType') == 'A+') ? 'selected' : '' }}>A+</option>
                        <option value="A-" {{ (old('bloodType') == 'A-') ? 'selected' : '' }}>A-</option>
                        <option value="B+" {{ (old('bloodType') == 'B+') ? 'selected' : '' }}>B+</option>
                        <option value="B-" {{ (old('bloodType') == 'B-') ? 'selected' : '' }}>B-</option>
                        <option value="AB+" {{ (old('bloodType') == 'AB+') ? 'selected' : '' }}>AB+</option>
                        <option value="AB-" {{ (old('bloodType') == 'AB-') ? 'selected' : '' }}>AB-</option>
                        <option value="O+" {{ (old('bloodType') == 'O+') ? 'selected' : '' }}>O+</option>
                        <option value="O-" {{ (old('bloodType') == 'O-') ? 'selected' : '' }}>O-</option>
                    </select>
                </div>
                <div>
                    <label for="philhealthNo" class="block text-gray-700 text-sm font-bold mb-2">PHILHEALTH NO.</label>
                    <input
                        type="text"
                        id="philhealthNo"
                        name="philhealthNo"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition duration-200"
                        value="{{ old('philhealthNo') }}"
                    />
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <h3 class="text-2xl font-semibold text-emerald-700 border-b pb-2 mb-4">Medical Information</h3>
                <div>
                    <label for="knownMedicalCondition" class="block text-gray-700 text-sm font-bold mb-2">KNOWN MEDICAL CONDITION</label>
                    <textarea
                        id="knownMedicalCondition"
                        name="knownMedicalCondition"
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition duration-200 resize-y"
                    >{{ old('knownMedicalCondition') }}</textarea>
                </div>
                <div>
                    <label for="allergies" class="block text-gray-700 text-sm font-bold mb-2">ALLERGIES</label>
                    <textarea
                        id="allergies"
                        name="allergies"
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition duration-200 resize-y"
                    >{{ old('allergies') }}</textarea>
                </div>
                <div>
                    <label for="previousSurgeries" class="block text-gray-700 text-sm font-bold mb-2">PREVIOUS SURGERIES</label>
                    <textarea
                        id="previousSurgeries"
                        name="previousSurgeries"
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition duration-200 resize-y"
                    >{{ old('previousSurgeries') }}</textarea>
                </div>
                <div>
                    <label for="familyHistory" class="block text-gray-700 text-sm font-bold mb-2">FAMILY HISTORY</label>
                    <textarea
                        id="familyHistory"
                        name="familyHistory"
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition duration-200 resize-y"
                    >{{ old('familyHistory') }}</textarea>
                </div>
                <div>
                    <label for="medication" class="block text-gray-700 text-sm font-bold mb-2">MEDICATION</label>
                    <textarea
                        id="medication"
                        name="medication"
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition duration-200 resize-y"
                    >{{ old('medication') }}</textarea>
                </div>
                <div>
                    <label for="supplements" class="block text-gray-700 text-sm font-bold mb-2">SUPPLEMENTS</label>
                    <textarea
                        id="supplements"
                        name="supplements"
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition duration-200 resize-y"
                    >{{ old('supplements') }}</textarea>
                </div>
                <div class="flex justify-end mt-8">
                    <button
                        type="submit"
                        class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 px-8 rounded-full shadow-lg transition-all duration-300"
                    >
                       <i class="fas fa-save mr-2"></i> Create Patient
                    </button>
                </div>
            </div>
        </form>
    </div>