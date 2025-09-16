<!DOCTYPE html>
<html>
<head>
    <title>All Medical Records for {{ $patient->full_name ?? 'Patient' }}</title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1, h2 { color: #333; }
        .header { margin-bottom: 20px; }
        .record-section { margin-bottom: 20px; border: 1px solid #eee; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Medical Records</h1>
        <p><strong>Patient:</strong> {{ $patient->full_name ?? 'N/A' }}</p>
        <p><strong>Patient ID:</strong> {{ $patient->id ?? 'N/A' }}</p>
        <p><strong>Date Generated:</strong> {{ now()->format('M d, Y H:i') }}</p>
    </div>

    @if($patientNotes->isNotEmpty())
        <div class="record-section">
            <h2>Doctor Notes</h2>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Doctor</th>
                        <th>Content</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patientNotes as $note)
                        <tr>
                            <td>{{ $note->created_at->format('M d, Y') }}</td>
                            <td>Dr. {{ $note->doctor->first_name ?? '' }} {{ $note->doctor->last_name ?? '' }}</td>
                            <td>{{ $note->content }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if($patientPrescriptions->isNotEmpty())
        <div class="record-section">
            <h2>Prescriptions</h2>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Doctor</th>
                        <th>Medication</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patientPrescriptions as $prescription)
                        <tr>
                            <td>{{ $prescription->created_at->format('M d, Y') }}</td>
                            <td>Dr. {{ $prescription->doctor->first_name ?? '' }} {{ $prescription->doctor->last_name ?? '' }}</td>
                            <td>{{ $prescription->content }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if($patientTestRequests->isNotEmpty())
        <div class="record-section">
            <h2>Lab Requests</h2>
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
        </div>
    @endif

    @if($labResults->isNotEmpty())
        <div class="record-section">
            <h2>Lab Results</h2>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Doctor</th>
                        <th>Result Details</th>
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
        </div>
    @endif
</body>
</html>