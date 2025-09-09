<div class="flex items-center space-x-4 mb-3 availability-slot" data-slot-id="{{ $slot['id'] ?? '' }}">
    <input type="hidden" name="availability[{{ $uniqueSlotIndex }}][day_of_week]" value="{{ $day }}">
    <input type="hidden" name="availability[{{ $uniqueSlotIndex }}][id]" value="{{ $slot['id'] ?? '' }}">

    <div class="w-1/4">
        <label for="start_time_{{ $day }}_{{ $uniqueSlotIndex }}" class="block text-gray-700 text-xs font-bold mb-1">Start Time</label>
        <input type="time" name="availability[{{ $uniqueSlotIndex }}][start_time]"
               id="start_time_{{ $day }}_{{ $uniqueSlotIndex }}"
               class="w-full border p-2 rounded"
               value="{{ $slot['start_time'] ?? '09:00' }}" required>
    </div>
    <div class="w-1/4">
        <label for="end_time_{{ $day }}_{{ $uniqueSlotIndex }}" class="block text-gray-700 text-xs font-bold mb-1">End Time</label>
        <input type="time" name="availability[{{ $uniqueSlotIndex }}][end_time]"
               id="end_time_{{ $day }}_{{ $uniqueSlotIndex }}"
               class="w-full border p-2 rounded"
               value="{{ $slot['end_time'] ?? '17:00' }}" required>
    </div>
    <div class="w-1/4">
        <label for="is_available_{{ $day }}_{{ $uniqueSlotIndex }}" class="block text-gray-700 text-xs font-bold mb-1">Available?</label>
        <input type="checkbox" name="availability[{{ $uniqueSlotIndex }}][is_available]"
               id="is_available_{{ $day }}_{{ $uniqueSlotIndex }}"
               class="form-checkbox h-5 w-5 text-emerald-600 mt-2"
               value="1" {{ ($slot['is_available'] ?? true) ? 'checked' : '' }}>
    </div>
    <div class="w-1/4 flex justify-end items-end">
        <button type="button" class="remove-slot-btn bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-3 rounded text-sm">
            Remove
        </button>
    </div>
</div>