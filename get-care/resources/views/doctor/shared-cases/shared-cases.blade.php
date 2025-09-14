@extends('admin_layout')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 h-12 w-12 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 font-bold text-xl mr-3">
                DR
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Shared Cases</h1>
                <p class="text-gray-600">Dr. {{ $doctor->first_name }} {{ $doctor->last_name }} • {{ $doctor->specialization }}</p>
            </div>
        </div> 
    </div>

    <div class="border-b border-gray-200 mb-6">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <a href="{{ route('doctor.shared-cases.list', ['filter' => 'ALL']) }}" class="{{ $filter === 'ALL' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                All Requests ({{ $totalSharedCases }})
            </a>
            <a href="{{ route('doctor.shared-cases.list', ['filter' => 'PENDING']) }}" class="{{ $filter === 'PENDING' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Pending ({{ $pendingSharedCasesCount }})
            </a>
            <a href="{{ route('doctor.shared-cases.list', ['filter' => 'ACCEPTED']) }}" class="{{ $filter === 'ACCEPTED' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Accepted ({{ $acceptedSharedCasesCount }})
            </a>
            <a href="{{ route('doctor.shared-cases.list', ['filter' => 'DECLINED']) }}" class="{{ $filter === 'DECLINED' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Declined ({{ $declinedSharedCasesCount }})
            </a>

             <a href="{{ route('doctor.shared-cases.list', ['filter' => 'CANCELLED']) }}" class="{{ $filter === 'CANCELLED' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Cancelled ({{ $cancelledSharedCasesCount }})
            </a>

             <a href="{{ route('doctor.shared-cases.list', ['filter' => 'REVOKED']) }}" class="{{ $filter === 'REVOKED' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Revoked ({{ $revokedSharedCasesCount }})
            </a>
             
        </nav>
    </div>

    <div class="space-y-6">
        @forelse ($sharedCases as $sharedCase)
            <div class="bg-white shadow-md rounded-lg p-6 relative">
                <div class="absolute top-4 left-4 ">
                    Urgency: 
                    <span class="rounded-full text-xs font-semibold px-3 py-1
                            @if ($sharedCase->urgency === 'high')
                                bg-red-100 text-red-800
                            @elseif ($sharedCase->urgency === 'medium')
                                bg-yellow-100 text-yellow-800
                            @else
                                bg-blue-100 text-white-800
                            @endif">
                    @if ($sharedCase->urgency === 'high')
                        <i class="fas fa-exclamation-triangle"></i>
                    @elseif ($sharedCase->urgency === 'medium')
                        <i class="fas fa-exclamation"></i>
                    @else
                        <i class="fas fa-arrow-down"></i>
                    @endif
                    {{ ucfirst($sharedCase->urgency) }}  
                    </span>
                     
                
                    <span class="rounded-full  text-xs font-semibold px-3 py-1
                        @if ($sharedCase->status === 'PENDING')
                            bg-gray-100 text-yellow-800
                        @elseif ($sharedCase->status === 'ACCEPTED')
                            bg-gray-100 text-green-800
                        @elseif ($sharedCase->status === 'DECLINED')
                            bg-gray-100 text-red-800
                        @elseif ($sharedCase->status === 'CANCELLED')
                            bg-gray-100 text-red-800
                        @elseif ($sharedCase->status === 'REVOKED')
                            bg-gray-100 text-red-800
                        @endif
                    ">
                     {{ Str::ucfirst($sharedCase->status) }}
                    </span>
                </div>
 
           
                <div class="absolute top-4 right-4 text-gray-500 text-sm">
                    {{ $sharedCase->created_at->diffForHumans() }}
                </div>

                <div class="flex items-center mb-4 mt-8 bg-gray-100 rounded-lg p-4">
                    @if ($sharedCase -> receiving_doctor_id == auth()->user()->doctor->id)
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-100 font-bold text-lg">
                            {{ substr($sharedCase->sharingDoctor->first_name, 0, 1) }}{{ substr($sharedCase->sharingDoctor->last_name, 0, 1) }}
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-white-900">Dr. {{ $sharedCase->sharingDoctor->first_name }} {{ $sharedCase->sharingDoctor->last_name }} has invited you to collaborate</p> 
                            <p class="text-sm text-gray-500">{{ $sharedCase->sharingDoctor->specialization }} • {{ $sharedCase->sharingDoctor->affiliated_hospital ?? 'N/A' }}</p>
                        </div>
                    @else
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-100 font-bold text-lg">
                            {{ substr($sharedCase->receivingDoctor->first_name, 0, 1) }}{{ substr($sharedCase->receivingDoctor->last_name, 0, 1) }}
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Dr. {{ $sharedCase->receivingDoctor->first_name }} {{ $sharedCase->receivingDoctor->last_name }}</p> has been invited to collaborate with you
                            <p class="text-sm text-gray-500">{{ $sharedCase->receivingDoctor->specialization }} • {{ $sharedCase->receivingDoctor->affiliated_hospital ?? 'N/A' }}</p>
                        </div>
                    @endif
                </div>

                <h3 class="text-lg font-semibold text-gray-800 mb-2">
                    Patient: {{ $sharedCase->patient->first_name }} {{ $sharedCase->patient->last_name }} ({{ substr($sharedCase->patient->sex, 0, 1) }}, {{ $sharedCase->patient_age }})
                </h3>

                <p class="text-gray-700 mb-4">
                    <strong class="font-medium">Collaboration Request:</strong> {{ $sharedCase->case_description }}
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-2 mb-4">
                    @if ($sharedCase->parsed_description['symptoms'])
                        <div>
                            <p class="text-sm font-medium text-gray-600">Symptoms:</p>
                            <p class="text-sm text-gray-800">{{ $sharedCase->parsed_description['symptoms'] }}</p>
                        </div>
                    @endif
                    @if ($sharedCase->parsed_description['duration'])
                        <div>
                            <p class="text-sm font-medium text-gray-600">Duration:</p>
                            <p class="text-sm text-gray-800">{{ $sharedCase->parsed_description['duration'] }}</p>
                        </div>
                    @endif
                    @if ($sharedCase->parsed_description['tests_done'])
                        <div>
                            <p class="text-sm font-medium text-gray-600">Tests Done:</p>
                            <p class="text-sm text-gray-800">{{ $sharedCase->parsed_description['tests_done'] }}</p>
                        </div>
                    @endif
                </div>

                <div class="flex space-x-2 mb-4">
                    {{-- Assuming shared_data can contain file attachments --}}
                    @if (isset($sharedCase->shared_data['file_attachments']) && is_array($sharedCase->shared_data['file_attachments']))
                        @foreach ($sharedCase->shared_data['file_attachments'] as $attachment)
                            <a href="{{ $attachment['url'] ?? '#' }}" target="_blank" class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                {{ $attachment['name'] ?? 'File' }}
                            </a>
                        @endforeach
                    @endif
                </div>

                <div class="flex justify-end space-x-3 mt-4">
                    <a href="#" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        View Details
                    </a>
                    @if ($sharedCase->sharing_doctor_id === Auth::user()->doctor->id)
                        {{-- Actions for shared by me cases --}}
                        @if ($sharedCase->status === 'PENDING')
                            <form action="{{ route('doctor.shared-cases.cancel', $sharedCase->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    Cancel Request
                                </button>
                            </form>
                        @elseif ($sharedCase->status === 'ACCEPTED')
                            <form action="{{ route('doctor.shared-cases.remove', $sharedCase->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    Revoke Shared Case
                                </button>
                            </form>
                        @elseif ($sharedCase->status === 'DECLINED')
                            <form action="{{ route('doctor.shared-cases.remove-rejected', $sharedCase->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                    Remove From List
                                </button>
                            </form>
                        @endif
                    @elseif ($sharedCase->receiving_doctor_id === Auth::user()->doctor->id)
                        {{-- Actions for cases received by me --}}
                        @if ($sharedCase->status === 'PENDING')
                            <form action="{{ route('doctor.shared-cases.decline', $sharedCase->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    Decline
                                </button>
                            </form>
                            <form action="{{ route('doctor.shared-cases.accept', $sharedCase->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                    Accept Collaboration
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
                     <div class="text-gray-100 absolute left-4 bottom-4  px-3 py-1 rounded-full text-xs font-semibold bg-gray-800"> {{$sharedCase->id}}</div>
            </div>
        @empty
            <p class="text-gray-600 text-center">No shared cases found for this filter.</p>
        @endforelse
    </div>
</div>
@endsection