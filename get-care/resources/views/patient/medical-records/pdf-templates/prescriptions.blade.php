<!DOCTYPE html>
<html>
<head>
    <title>Prescriptions for {{ $patient->full_name ?? 'Patient' }}</title>
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
        <h1>Prescriptions</h1>
        <p><strong>Patient:</strong> {{ $patient->full_name ?? 'N/A' }}</p>
        <p><strong>Patient ID:</strong> {{ $patient->id ?? 'N/A' }}</p>
        <p><strong>Date Generated:</strong> {{ now()->format('M d, Y H:i') }}</p>
    </div>

    @if($patientPrescriptions->isNotEmpty())
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
    @else
        <p>No prescriptions available for this patient.</p>
    @endif
</body>
</html>