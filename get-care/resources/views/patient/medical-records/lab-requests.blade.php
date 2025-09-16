@foreach(auth()->user()->patient->patientTestRequests()->orderBy('created_at', 'desc')->get() as $p)
<div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50 mb-4">
    <div class="flex justify-between items-start">
        <h4 class="font-medium text-gray-800">{{ $p->content }}</h4>
        <span class="text-xs text-gray-500">{{ $p->created_at->format('M j, Y') }}</span>
 
    </div>

    @if (!$p->lab_results)
    <form action="" method="POST" enctype="multipart/form-data">
    <div class="mt-2 text-xs text-gray-500">
        <a href="#" class="text-blue-500 p-1 text-sm rounded bg-blue-100 hover:text-blue-700 hover:underline"><i class="fas fa-upload mr-2"></i>Upload Lab Result</a>
    </div>
    </form>
    @endif
    
    <div class="mt-2 text-xs ">
        <span class="text-gray-500">Requested By:</span> Dr. <span class="text-gray-800 font-semibold">{{ $p->doctor->first_name ?? '' }} {{ $p->doctor->last_name ?? '' }}</span>
    </div>
</div>
@endforeach