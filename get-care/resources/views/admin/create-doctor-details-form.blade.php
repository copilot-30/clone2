 
<!-- <div class="min-h-screen flex items-center justify-center bg-gray-100 p-4"> -->
    <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-5xl">
        <h2 class="text-3xl font-bold text-emerald-600 mb-8 text-center">Doctor Information</h2> 
        <form action="{{ route('admin.doctors.store_details', ['user_id' => $user->id]) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @csrf {{-- Laravel CSRF token --}}

            <!-- Left Column -->
            <div class="space-y-6">
                <div>
                    <label for="first_name" class="block text-gray-700 text-sm font-bold mb-2">First Name</label>
                    <input
                        type="text"
                        id="first_name"
                        name="first_name"
                        class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2"
                        value="{{ old('first_name') }}"
                        required
                    />
                </div>
                <div>
                    <label for="last_name" class="block text-gray-700 text-sm font-bold mb-2">Last Name</label>
                    <input
                        type="text"
                        id="last_name"
                        name="last_name"
                        class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2"
                        value="{{ old('last_name') }}"
                        required
                    />
                </div>
                <div>
                    <label for="middle_name" class="block text-gray-700 text-sm font-bold mb-2">Middle Name</label>
                    <input
                        type="text"
                        id="middle_name"
                        name="middle_name"
                        class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2"
                        value="{{ old('middle_name') }}"
                    />
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
                        <option value="Male" {{ (old('sex') == 'Male') ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ (old('sex') == 'Female') ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ (old('sex') == 'Other') ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div>
                    <label for="phone_number" class="block text-gray-700 text-sm font-bold mb-2">Phone Number</label>
                    <input
                        type="text"
                        id="phone_number"
                        name="phone_number"
                        class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2"
                        value="{{ old('phone_number') }}"
                    />
                </div>
                <div>
                    <label for="specialization" class="block text-gray-700 text-sm font-bold mb-2">Specialization</label>
                    <input
                        type="text"
                        id="specialization"
                        name="specialization"
                        class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2"
                        value="{{ old('specialization') }}"
                        required
                    />
                </div>
                <div>
                    <label for="years_of_experience" class="block text-gray-700 text-sm font-bold mb-2">Years of Experience</label>
                    <input
                        type="number"
                        id="years_of_experience"
                        name="years_of_experience"
                        class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2"
                        value="{{ old('years_of_experience') }}"
                    />
                </div>
                <div>
                    <label for="prc_license_number" class="block text-gray-700 text-sm font-bold mb-2">PRC License Number</label>
                    <input
                        type="text"
                        id="prc_license_number"
                        name="prc_license_number"
                        class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2"
                        value="{{ old('prc_license_number') }}"
                        required
                    />
                </div>
                <div>
                    <label for="ptr_license_number" class="block text-gray-700 text-sm font-bold mb-2">PTR License Number</label>
                    <input
                        type="text"
                        id="ptr_license_number"
                        name="ptr_license_number"
                        class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2"
                        value="{{ old('ptr_license_number') }}"
                        required
                    />
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <div>
                    <label for="certifications" class="block text-gray-700 text-sm font-bold mb-2">Certifications</label>
                    <textarea
                        id="certifications"
                        name="certifications"
                        class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2 h-24 resize-none"
                    >{{ old('certifications') }}</textarea>
                </div>
                <div>
                    <label for="affiliated_hospital" class="block text-gray-700 text-sm font-bold mb-2">Affiliated Hospital</label>
                    <input
                        type="text"
                        id="affiliated_hospital"
                        name="affiliated_hospital"
                        class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2"
                        value="{{ old('affiliated_hospital') }}"
                    />
                </div>
                <div>
                    <label for="training" class="block text-gray-700 text-sm font-bold mb-2">Training</label>
                    <textarea
                        id="training"
                        name="training"
                        class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2 h-24 resize-none"
                    >{{ old('training') }}</textarea>
                </div>
                <div class="flex justify-end mt-8">
                    <button
                        type="submit"
                        class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 px-8 rounded-full shadow-lg transition-all duration-300"
                    >
                        Save Information
                    </button>
                </div>
            </div>
        </form>
    </div>