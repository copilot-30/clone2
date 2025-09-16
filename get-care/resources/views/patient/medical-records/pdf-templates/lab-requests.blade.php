<!DOCTYPE html>
<html>
<head>
    <title>Lab Requests for {{ $patient->full_name ?? 'Patient' }}</title>
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
        <h1>Lab Requests</h1>
        <p><strong>Patient:</strong> {{ $patient->full_name ?? 'N/A' }}</p>
        <p><strong>Patient ID:</strong> {{ $patient->id ?? 'N/A' }}</p>
        <p><strong>Date Generated:</strong> {{ now()->format('M d, Y H:i') }}</p>
    </div>

    @if($patientTestRequests->isNotEmpty())
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Doctor</th>
                    <th>Request</th>
                </tr>
            </thead>
            <tbody>
                @foreach($patientTestRequests as $request)
                    <tr>
                        <td>{{ $request->created_at->format('M d, Y') }}</td>
                        <td>Dr. {{ $request->doctor->first_name ?? '' }} {{ $request->doctor->last_name ?? '' }}</td>
                        <td>{{ $request->content }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No lab requests available for this patient.</p>
    @endif
</body>
</html>