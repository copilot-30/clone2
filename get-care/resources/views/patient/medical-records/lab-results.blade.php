@foreach(auth()->user()->patient->labResults as $p)
<div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50">
    <div class="flex justify-between items-start">
         <div class="flex justify-between items-start w-4/5">
        <a  href="{{$p -> result_file_url }}"  target="_blank" class="font-medium text-gray-800">
           <i class="fas fa-file mr-2"></i> {{$p->result_data['file_name']}}
        </a>
        <div class="flex justify-end">
            <a href="{{$p->result_file_url}}" download="{{ $p->result_data['file_name'] }}" class="text-blue-500 hover:text-blue-700 hover:underline">
                <i class="fas fa-download mr-2"></i> Download
            </a>
        </div>
        </div>
        <span class="text-xs text-gray-500">{{ $p->result_date->format('M j, Y') }}</span>
    </div>
    
    @if ($p -> doctor)
    <div class="mt-2 text-xs text-gray-500">
        Dr. {{ $p->doctor->first_name ?? '' }} {{ $p->doctor->last_name ?? '' }}
    </div>
    @endif
</div>
@endforeach