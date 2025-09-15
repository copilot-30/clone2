@foreach(auth()->user()->patient->patientPrescriptions as $p)
<div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50">
    <div class="flex justify-between items-start">
        <h4 class="font-medium text-gray-800">{{ $p->content }}</h4>
        <span class="text-xs text-gray-500">{{ $p->created_at->format('M j, Y') }}</span>
    </div>
    
    <div class="mt-2 text-xs text-gray-500">
        Dr. {{ $p->doctor->first_name ?? '' }} {{ $p->doctor->last_name ?? '' }}
    </div>
</div>
@endforeach