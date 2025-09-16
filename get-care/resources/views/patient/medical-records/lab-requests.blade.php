@foreach(auth()->user()->patient->patientTestRequests()->orderBy('created_at', 'desc')->get() as $p)
<div class="record-item border border-gray-200 mb-4 pb-4">
    <div class="flex justify-between items-start">
        <div class="w-full">
            <div class=" flex justify-between items-items-center 
                @if($p->labResults->isEmpty())
                    bg-yellow-100
                @else
                    bg-emerald-700
                @endif 
                px-6 py-3   flex justify-between items-center
            ">
                <span class="font-medium 
                @if($p->labResults->isEmpty())
                    text-black
                @else
                    text-white
                @endif ">
                @if($p->labResults->isEmpty())
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                @else
                    <i class="fas fa-check mr-2"></i>
                @endif 
                {{ $p->content }}
            
            </span>
        
                <span class="text-xs 
                @if($p->labResults->isEmpty())
                    text-black
                @else
                    text-white
                @endif
                ">{{ $p->created_at->format('M j, Y') }}</span>
            </div>
        </div>
       
    </div>
    <div class=" px-4">

    @if($p->labResults->isEmpty())
    <form action="{{ route('patient.lab-results.upload', $p->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mt-2">
            <input type="file" name="lab_result_file" id="lab_result_file_{{ $p->id }}" class="mt-1 block w-full text-md p-4 text-gray-900 border border-gray-300  cursor-pointer ">
            <p class="mt-1  my-4 text-xs text-gray-500 p-4 bg-blue-50">PDF, JPEG, PNG, JPG (Max 2MB)</p>
        </div>
        <div class="mt-2"> 
            <textarea name="notes" placeholder="Notes (Optional)" id="notes_{{ $p->id }}" rows="2" class="mt-1 block w-full p-4 text-md text-gray-900 border border-gray-500 focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"></textarea>
        </div>
        <button type="submit" class="mt-3 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fas fa-upload mr-2"></i> Upload Lab Result
        </button>
    </form>
    @else
        <div class="mt-2 text-sm text-gray-600">
            <div class="mb-2 font-semibold">Lab Results: </div>
            <div class="">
            @foreach($p->labResults as $result)
                <div class=" m-4 gap-2 "> 
                    @if($result->result_file_url)
                        <a href="{{ asset($result->result_file_url) }}" target="_blank" 
                            class="p-2 mb-2 text-blue-500 rounded-md hover:underline hover:text-blue-700 border border-blue-300">
                            <i class="fas fa-file mr-2"></i> {{ isset($result->result_data_parsed['file_name']) ? $result->result_data_parsed['file_name'] : 'N/A' }}
                        </a>
                    @else
                        {{ isset($result->result_data_parsed['file_name']) ? $result->result_data_parsed['file_name'] : 'N/A' }}
                    @endif
                    @if($result->notes)
                        <p class="text-gray-700 text-sm my-4 bg-blue-50 p-2">{{ $result->notes }}</p>
                    @endif
                    
                </div>
            @endforeach
            </div>
        </div>
    @endif
    
    <div class="mt-2 text-xs ">
        <span class="text-gray-500">Requested By:</span> Dr. <span class="text-gray-800 font-semibold">{{ $p->doctor->first_name ?? '' }} {{ $p->doctor->last_name ?? '' }}</span>
    </div>
</div>
</div>
@endforeach