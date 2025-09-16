<!DOCTYPE html>
<html>
<head>
    <title>Doctor Notes for {{ $patient->full_name ?? 'Patient' }}</title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1, h2 { color: #333; }
        .header { margin-bottom: 20px; }
        .record-item { margin-bottom: 15px; padding: 10px; border: 1px solid #eee; border-radius: 5px; }
        .record-item h3 { margin-top: 0; margin-bottom: 5px; color: #555; }
        .record-item p { margin-bottom: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Doctor Notes</h1>
        <p><strong>Patient:</strong> {{ $patient->full_name ?? 'N/A' }}</p>
        <p><strong>Patient ID:</strong> {{ $patient->id ?? 'N/A' }}</p>
        <p><strong>Date Generated:</strong> {{ now()->format('M d, Y H:i') }}</p>
    </div>

    @if($patientNotes->isNotEmpty())
        @foreach($patientNotes as $note)
            <div class="record-item">
                <h3>Note from Dr. {{ $note->doctor->first_name ?? '' }} {{ $note->doctor->last_name ?? '' }} ({{ $note->created_at->format('M d, Y') }})</h3>
                <p>{{ $note->content }}</p>
            </div>
        @endforeach
    @else
        <p>No doctor notes available for this patient.</p>
    @endif
</body>
</html>