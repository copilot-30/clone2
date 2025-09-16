<div class="bg-white rounded-lg shadow-sm border border-gray-200">
 
  <div class="overflow-x-auto">
 
      <div class="px-6 py-4"> 
        <div class="space-y-3">
          @foreach(auth()->user()->patient->patientNotes as $note)
            <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50">
                <div class="mt-2 "> 
                <span class="text-lg text-gray-800 font-semibold">Dr. {{ $note->doctor->first_name ?? '' }} {{ $note->doctor->last_name ?? '' }}
                    </span> - {{ $note->doctor->specialization ?? '' }}
              </div>
              <div class="flex justify-between items-start">
                <h4 class="font-medium text-gray-800">{{ $note->subject ?? 'Untitled Note' }}</h4>
                <span class="text-xs text-gray-500">{{ $note->created_at->format('M j, Y') }}</span>
              </div>
              <p class="text-sm text-gray-600 mt-1 line-clamp-2">
                {{ Str::limit($note->content, 100) }}
              </p>
            </div>
          @endforeach
        </div>
      </div>  
    </div>
</div>