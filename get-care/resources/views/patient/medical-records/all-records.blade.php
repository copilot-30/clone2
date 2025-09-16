@if($allRecords->isNotEmpty())
    @foreach($allRecords as $record)
        <div class="record-item border border-gray-200 rounded-lg mb-4  hover:bg-gray-50">
            <div class="flex justify-between items-start">
                <h4 class="w-full">
                    <div class="bg-emerald-600 text-white px-6 py-3   flex justify-between items-center">
                        <h2 class="text-lg font-semibold">@if($record->record_type == 'doctor-note')
                                        Doctor's Note
                                    @elseif($record->record_type == 'prescription')
                                        Prescription
                                    @elseif($record->record_type == 'lab-request')
                                        Lab Request
                                    @elseif($record->record_type == 'lab-result')
                                        Lab Result
                                    @endif</h2>
                        <a href="#" class="text-white hover:text-gray-500 transition-colors text-sm font-medium">{{ $record->created_at->format('M d, Y') }}</a>
                    </div>
                     
                </h4> 
            </div>
            <div class="mt-2 text-sm text-gray-700 px-6 py-3">
                @if($record->record_type == 'doctor-note' || $record->record_type == 'prescription' || $record->record_type == 'lab-request')
                    {{ $record->content }}
                @elseif($record->record_type == 'lab-result')
                <div class="flex justify-between items-start">
                    <div class="flex justify-between items-start w-4/5">
                        <a  href="{{$record -> result_file_url }}"  target="_blank" class="font-medium text-gray-800">
                            <i class="fas fa-file mr-2"></i> {{$record->result_data_parsed['file_name']}}
                        </a>
                        <div class="flex justify-end">
                        <a href="{{$record->result_file_url}}" download="{{ $record->result_data_parsed['file_name'] }}" class="text-blue-500 hover:text-blue-700 hover:underline">
                            <i class="fas fa-download mr-2"></i> Download
                        </a>
                    </div>
                </div> 
                </div>
    
                    @if ($record -> doctor)
                    <div class="mt-2 text-xs text-gray-500">
                        Dr. {{ $record->doctor->first_name ?? '' }} {{ $record->doctor->last_name ?? '' }}
                    </div>
                    @endif
                @endif
            </div>
            @if($record->doctor)
                <div class="mt-2 text-xs text-gray-500 px-6 py-3 ">
                    Dr. {{ $record->doctor->first_name ?? '' }} {{ $record->doctor->last_name ?? '' }}
                </div>
            @endif
        </div>
    @endforeach
@else
    <p>No medical records found.</p>
@endif