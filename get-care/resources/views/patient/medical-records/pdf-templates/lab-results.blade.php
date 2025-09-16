<!DOCTYPE html>
<html>
<head>
    <title>Lab Results for {{ $patient->full_name ?? 'Patient' }}</title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1, h2 { color: #333; }
        .header { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Lab Results</h1>
        <p><strong>Patient:</strong> {{ $patient->full_name ?? 'N/A' }}</p>
        <p><strong>Patient ID:</strong> {{ $patient->id ?? 'N/A' }}</p>
        <p><strong>Date Generated:</strong> {{ now()->format('M d, Y H:i') }}</p>
    </div>

    @if($labResults->isNotEmpty())
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Doctor</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                @foreach($labResults as $result)
                    <tr>
                        <td>{{ $result->created_at->format('M d, Y') }}</td>
                        <td>Dr. {{ $result->doctor->first_name ?? '' }} {{ $result->doctor->last_name ?? '' }}</td>
                        <td>{{ $result->details }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No lab results available for this patient.</p>
    @endif
</body>
</html>