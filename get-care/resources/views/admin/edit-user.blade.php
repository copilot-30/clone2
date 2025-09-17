@extends('admin_layout')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 p-4">
    <div class="
     @if ($user->role !== 'ADMIN')
    md:grid-cols-2 grid   w-full  gap-8  grid-cols-1
    @else
          mx-auto w-1/3 my-8
    @endif">
        <div class="bg-white p-8 rounded-lg shadow-xl">
            <h2 class="text-3xl font-bold text-emerald-600 mb-8 text-center">Edit User</h2>

           

            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                <div>
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2"
                        value="{{ old('email', $user->email) }}"
                        required
                    />
                </div>
                <div>
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">New Password (leave blank to keep current)</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2"
                    />
                </div>
                <div>
                    <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Confirm New Password</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2"
                    />
                </div>
                <div>
                    <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Role</label>
                    <select
                        id="role"
                        name="role"
                        class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2"
                        required
                    >
                        <option value="ADMIN" {{ old('role', $user->role) == 'ADMIN' ? 'selected' : '' }}>Admin</option>
                        <option value="DOCTOR" {{ old('role', $user->role) == 'DOCTOR' ? 'selected' : '' }}>Doctor</option>
                        <option value="PATIENT" {{ old('role', $user->role) == 'PATIENT' ? 'selected' : '' }}>Patient</option>
                    </select>
                </div>
                <div class="flex items-center">
                    <input
                        type="checkbox"
                        id="is_active"
                        name="is_active"
                        value="1"
                        class="mr-2"
                        {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                    />
                    <label for="is_active" class="text-gray-700 text-sm font-bold">Is Active</label>
                </div>
                <div class="flex justify-end">
                    <button
                        type="submit"
                        class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 px-8 rounded-full shadow-lg transition-all duration-300"
                    >
                        <i class="fas fa-save mr-2"></i> Update User
                    </button>
                </div>
            </form>
        </div>

        @if ($user->role === 'PATIENT')
            @if ($patient)
                    @include('admin.edit-patient-details-form', ['user' => $user, 'patient' => $patient])
            @else
                    @include('admin.create-patient-details-form', ['user' => $user])
            @endif
        @elseif ($user->role === 'DOCTOR')
            @if ($doctor)
                    @include('admin.edit-doctor-details-form', ['user' => $user, 'doctor' => $doctor])
            @else
                    @include('admin.create-doctor-details-form', ['user' => $user])
            @endif
        @endif
    </div>
</div>
@endsection