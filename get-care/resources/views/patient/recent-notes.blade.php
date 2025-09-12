<div class="bg-white rounded-lg shadow-sm border border-gray-200">
  <div class="bg-emerald-600 text-white px-6 py-4 rounded-t-lg flex justify-between items-center">
    <h2 class="text-lg font-semibold">Recent Notes</h2>

  </div>

  <div class="overflow-x-auto">
      @if(isset($recentNotes) && $recentNotes->count() > 0) 
      <div class="px-6 py-4"> 
        <div class="space-y-3">
          @foreach($recentNotes as $note)
            <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50">
              <div class="flex justify-between items-start">
                <h4 class="font-medium text-gray-800">{{ $note->subject ?? 'Untitled Note' }}</h4>
                <span class="text-xs text-gray-500">{{ $note->created_at->format('M j, Y') }}</span>
              </div>
              <p class="text-sm text-gray-600 mt-1 line-clamp-2">
                {{ Str::limit($note->content, 100) }}
              </p>
              <div class="mt-2 text-xs text-gray-500">
                Dr. {{ $note->doctor->first_name ?? '' }} {{ $note->doctor->last_name ?? '' }}
              </div>
            </div>
          @endforeach
        </div>
      </div> 
    @endif
    </div>
</div>

