@extends('admin_layout')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 p-4">
    <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-2xl">
        <h2 class="text-3xl font-bold text-emerald-600 mb-8 text-center">Create New User</h2>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2"
                    value="{{ old('email') }}"
                    required
                />
            </div>
            <div>
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2"
                    required
                />
            </div>
            <div>
                <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Confirm Password</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="w-full border-b-2 border-gray-300 focus:border-emerald-500 outline-none p-2"
                    required
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
                    <option value="ADMIN" {{ old('role') == 'ADMIN' ? 'selected' : '' }}>Admin</option>
                    <option value="DOCTOR" {{ old('role') == 'DOCTOR' ? 'selected' : '' }}>Doctor</option>
                    <option value="PATIENT" {{ old('role') == 'PATIENT' ? 'selected' : '' }}>Patient</option>
                </select>
            </div>
            <div class="flex items-center">
                <input
                    type="checkbox"
                    id="is_active"
                    name="is_active"
                    value="1"
                    class="mr-2"
                    {{ old('is_active') ? 'checked' : '' }}
                />
                <label for="is_active" class="text-gray-700 text-sm font-bold">Is Active</label>
            </div>
            <div class="flex justify-end">
                <button
                    type="submit"
                    class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 px-8 rounded-full shadow-lg transition-all duration-300"
                >
                    Create User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection