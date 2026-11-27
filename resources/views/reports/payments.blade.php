
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Revenue Report</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; padding: 25px; }
        .container { max-width: 1100px; margin: auto; background: white; padding: 25px; border-radius: 10px; }
        input, button { padding: 9px; margin: 5px 5px 5px 0; }
        button { cursor: pointer; }
        .summary { background: #eff6ff; padding: 18px; border-radius: 8px; margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #1f2937; color: white; }
        .print-button { background: #2563eb; color: white; border: 0; padding: 10px 15px; border-radius: 5px; }
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
    <div class="no-print">
        <a href="{{ route('reports.index') }}">Reports</a> |
        <a href="{{ route('payments.index') }}">Payments</a> |
        <a href="{{ route('parking.index') }}">Parking</a>
    </div>

    <h1>Payment Revenue Report</h1>
    <p>Filter payments by the date they were recorded as paid.</p>

    <form method="GET" action="{{ route('reports.payments') }}" class="no-print">
        <label>From:
            <input type="date" name="date_from" value="{{ $validated['date_from'] ?? '' }}">
        </label>
        <label>To:
            <input type="date" name="date_to" value="{{ $validated['date_to'] ?? '' }}">
        </label>
        <button type="submit">Filter</button>
        <a href="{{ route('reports.payments') }}">Reset</a>
        <button type="button" class="print-button" onclick="window.print()">Print Report</button>
    </form>

    @if ($errors->any())
        <p style="color: #b91c1c;">{{ $errors->first() }}</p>
    @endif

    <div class="summary">
        <h2>Total Revenue: ₱{{ number_format((float) $totalRevenue, 2) }}</h2>
        <p>Payments in this report: {{ $payments->total() }}</p>
    </div>

    <table>
        <thead>
        <tr>
            <th>Payment ID</th>
            <th>Plate Number</th>
            <th>Owner</th>
            <th>Parking Slot</th>
            <th>Amount</th>
            <th>Method</th>
            <th>Paid At</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($payments as $payment)
            <tr>
                <td>{{ $payment->id }}</td>
                <td>{{ $payment->parkingRecord->vehicle->plate_number }}</td>
                <td>{{ $payment->parkingRecord->vehicle->owner_name }}</td>
                <td>{{ $payment->parkingRecord->parkingSlot->slot_number }}</td>
                <td>₱{{ number_format((float) $payment->amount, 2) }}</td>
                <td>{{ $payment->payment_method }}</td>
                <td>{{ $payment->paid_at->format('M d, Y h:i A') }}</td>
            </tr>
        @empty
            <tr><td colspan="7">No payments found for this date range.</td></tr>
        @endforelse
        </tbody>
    </table>

    <div class="no-print">{{ $payments->links() }}</div>
</div>
</body>
</html>
