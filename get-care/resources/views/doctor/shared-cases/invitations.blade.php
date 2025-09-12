@extends('admin_layout')

@section('content')
<div class="p-4">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Shared Case Invitations</h1>

    @if ($invitations->isEmpty())
        <p class="text-gray-600">No pending shared case invitations.</p>
    @else
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Patient
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Sharing Doctor
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Description
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Permissions
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Expires At
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($invitations as $invitation)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $invitation->patient->first_name }} {{ $invitation->patient->last_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                Dr. {{ $invitation->sharingDoctor->first_name }} {{ $invitation->sharingDoctor->last_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $invitation->case_description }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if (is_array($invitation->permissions))
                                    {{ implode(', ', $invitation->permissions) }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $invitation->expires_at ? $invitation->expires_at->format('M d, Y') : 'Never' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <form action="{{ route('doctor.shared-cases.accept', $invitation->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-emerald-600 hover:text-emerald-900">Accept</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection