
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking Activity Report</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; padding: 25px; }
        .container { max-width: 1200px; margin: auto; background: white; padding: 25px; border-radius: 10px; }
        input, button { padding: 9px; margin: 5px 5px 5px 0; }
        button { cursor: pointer; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #1f2937; color: white; }
        .print-button { background: #2563eb; color: white; border: 0; padding: 10px 15px; border-radius: 5px; }
        .nav { margin-bottom: 20px; }
        @media print {
            body { background: white; padding: 0; }
            .container { max-width: none; padding: 0; }
            .no-print { display: none !important; }
            th { background: #eee !important; color: black !important; }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="nav no-print">
        <a href="{{ route('reports.index') }}">Reports</a> |
        <a href="{{ route('parking.index') }}">Parking</a> |
        <a href="{{ route('payments.index') }}">Payments</a>
    </div>

    <h1>Parking Activity Report</h1>
    <p>Filter parking sessions by entry date.</p>

    <form method="GET" action="{{ route('reports.parking') }}" class="no-print">
        <label>From:
            <input type="date" name="date_from" value="{{ $validated['date_from'] ?? '' }}">
        </label>
        <label>To:
            <input type="date" name="date_to" value="{{ $validated['date_to'] ?? '' }}">
        </label>
        <button type="submit">Filter</button>
        <a href="{{ route('reports.parking') }}">Reset</a>
        <button type="button" class="print-button" onclick="window.print()">Print Report</button>
    </form>

    @if ($errors->any())
        <p style="color: #b91c1c;">{{ $errors->first() }}</p>
    @endif

    <p><strong>Records shown:</strong> {{ $records->total() }}</p>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Plate Number</th>
            <th>Owner</th>
            <th>Vehicle Type</th>
            <th>Parking Slot</th>
            <th>Entry Time</th>
            <th>Exit Time</th>
            <th>Fee</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($records as $record)
            <tr>
                <td>{{ $record->id }}</td>
                <td>{{ $record->vehicle->plate_number }}</td>
                <td>{{ $record->vehicle->owner_name }}</td>
                <td>{{ $record->vehicle->vehicle_type }}</td>
                <td>{{ $record->parkingSlot->slot_number }}</td>
                <td>{{ $record->entry_time->format('M d, Y h:i A') }}</td>
                <td>{{ $record->exit_time ? $record->exit_time->format('M d, Y h:i A') : 'Still parked' }}</td>
                <td>₱{{ number_format((float) $record->parking_fee, 2) }}</td>
                <td>{{ $record->status }}</td>
            </tr>
        @empty
            <tr><td colspan="9">No parking records found for this date range.</td></tr>
        @endforelse
        </tbody>
    </table>

    <div class="no-print">{{ $records->links() }}</div>
</div>
</body>
</html>
