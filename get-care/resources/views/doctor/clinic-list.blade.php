@extends('admin_layout')

@section('content')
<div class="p-4">
    <div class="bg-white p-6 rounded-lg shadow-xl">
        <h2 class="text-2xl font-bold text-emerald-600 mb-6">My Clinics</h2>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex justify-end mb-4">
            <a href="{{ route('doctor.clinics.create') }}" class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2 px-4 rounded">
                <i class="fa fa-plus"></i> Add New Clinic
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Address</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">City</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Phone</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Active</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Hospital</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Facilities</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clinics as $clinic)
                        <tr class="hover:bg-gray-100">
                            <td class="py-3 px-4 border-b border-gray-200">{{ $clinic->name }}</td>
                            <td class="py-3 px-4 border-b border-gray-200">{{ $clinic->address }}</td>
                            <td class="py-3 px-4 border-b border-gray-200">{{ $clinic->city }}</td>
                            <td class="py-3 px-4 border-b border-gray-200">{{ $clinic->phone }}</td>
                            <td class="py-3 px-4 border-b border-gray-200">{{ $clinic->email }}</td>
                            <td class="py-3 px-4 border-b border-gray-200">
                                @if ($clinic->is_active)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Yes</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">No</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 border-b border-gray-200">
                                @if ($clinic->is_hospital)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Yes</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">No</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 border-b border-gray-200">
                                @foreach ($clinic->facilities as $facility)
                                    <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">{{ ucwords(str_replace('-', ' ', strtolower($facility))) }}</span>
                                @endforeach
                            </td>


                            <td class="py-3 px-4 border-b border-gray-200 text-sm">
                                <a href="{{ route('doctor.clinics.edit', $clinic->id) }}" class=" text-blue-600 hover:text-blue-900 mr-3"><i class="fa fa-edit"></i> Edit</a>
                                <form action="{{ route('doctor.clinics.delete', $clinic->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this clinic?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900"><i class="fa fa-trash"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection