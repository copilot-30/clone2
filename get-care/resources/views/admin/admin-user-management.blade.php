@extends('admin_layout')

@section('content')
<div class="p-4">
    <h1 class="text-2xl font-bold mb-4">User Management</h1>

    <div class="bg-white p-4 rounded shadow mb-6">
        <a href="{{ route('admin.users.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Add New User</a>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <table class="min-w-full divide-y divide-gray-200 mt-4">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Active</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($users as $user)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->role }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->is_active ? 'Yes' : 'No' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</a>
                        <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection