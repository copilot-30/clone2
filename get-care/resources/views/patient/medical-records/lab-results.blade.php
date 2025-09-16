@foreach(auth()->user()->patient->labResults as $p)
<div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50">
    <div class="flex justify-between items-start">
        <a  href="{{$p -> result_file_url }}"  target="_blank" class="font-medium text-gray-800">View</a>
        <span class="text-xs text-gray-500">{{ $p->result_date->format('M j, Y') }}</span>
    </div>
    
    @if ($p -> doctor)
    <div class="mt-2 text-xs text-gray-500">
        Dr. {{ $p->doctor->first_name ?? '' }} {{ $p->doctor->last_name ?? '' }}
    </div>
    @endif
</div>
@endforeach