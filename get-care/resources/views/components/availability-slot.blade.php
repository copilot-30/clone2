<div class="flex items-center space-x-4 mb-3 availability-slot" data-slot-id="{{ $slot['id'] ?? '' }}">
    <input type="hidden" name="availability[{{ $uniqueSlotIndex }}][day_of_week]" value="{{ $day }}">
    <input type="hidden" name="availability[{{ $uniqueSlotIndex }}][id]" value="{{ $slot['id'] ?? '' }}">
<div class="w-1/5">
    <label for="type_{{ $day }}_{{ $uniqueSlotIndex }}" class="block text-gray-700 text-xs font-bold mb-1">Type</label>
    <select name="availability[{{ $uniqueSlotIndex }}][type]"
            id="type_{{ $day }}_{{ $uniqueSlotIndex }}"
            class="w-full border p-2 rounded type-select" required onchange="toggleClinicField(this)">
        <option value="online_consultation" {{ ($slot['type'] ?? 'online_consultation') === 'online_consultation' ? 'selected' : '' }}>Online Consultation</option>
        <option value="face_to_face" {{ ($slot['type'] ?? 'online_consultation') === 'face_to_face' ? 'selected' : '' }}>Face to Face Consultation</option>
    </select>
</div>
<div class="w-1/5">
    <label for="start_time_{{ $day }}_{{ $uniqueSlotIndex }}" class="block text-gray-700 text-xs font-bold mb-1">Start Time</label>
    <input type="time" name="availability[{{ $uniqueSlotIndex }}][start_time]"
           id="start_time_{{ $day }}_{{ $uniqueSlotIndex }}"
           class="w-full border p-2 rounded"
           value="{{ $slot['start_time'] ?? '09:00' }}" required>
</div>
<div class="w-1/5">
    <label for="end_time_{{ $day }}_{{ $uniqueSlotIndex }}" class="block text-gray-700 text-xs font-bold mb-1">End Time</label>
    <input type="time" name="availability[{{ $uniqueSlotIndex }}][end_time]"
           id="end_time_{{ $day }}_{{ $uniqueSlotIndex }}"
           class="w-full border p-2 rounded"
           value="{{ $slot['end_time'] ?? '17:00' }}" required>
</div>
<div class="w-1/5 clinic-field" style="display: {{ ($slot['type'] ?? 'online_consultation') === 'face_to_face' ? 'block' : 'none' }};">
    <label for="clinic_id_{{ $day }}_{{ $uniqueSlotIndex }}" class="block text-gray-700 text-xs font-bold mb-1">Clinic</label>
    <select name="availability[{{ $uniqueSlotIndex }}][clinic_id]"
            id="clinic_id_{{ $day }}_{{ $uniqueSlotIndex }}"
            class="w-full border p-2 rounded">
        <option value="">Select Clinic</option>
        @foreach($clinics as $clinic)
            <option value="{{ $clinic->id }}" {{ ($slot['clinic_id'] ?? null) === $clinic->id ? 'selected' : '' }}>{{ $clinic->name }}</option>
        @endforeach
    </select>
</div>
<div class="w-1/5">
    <label for="is_active_{{ $day }}_{{ $uniqueSlotIndex }}" class="block text-gray-700 text-xs font-bold mb-1">Available?</label>
    <input type="checkbox" name="availability[{{ $uniqueSlotIndex }}][is_active]"
           id="is_active_{{ $day }}_{{ $uniqueSlotIndex }}"
           class="form-checkbox h-5 w-5 text-emerald-600 mt-2"
           value="1" {{ ($slot['is_active'] ?? true) ? 'checked' : '' }}>
</div>
<div class="w-1/5 flex justify-end items-end">
    <button type="button" class="remove-slot-btn bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-3 rounded text-sm">
        Remove
    </button>
</div>
</div>
</div>