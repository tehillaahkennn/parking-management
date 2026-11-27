
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 25px;
        }
        .container {
            max-width: 1100px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
        }
        h1 { color: #1f2937; }
        .button {
            display: inline-block;
            background: #2563eb;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .success {
            background: #dcfce7;
            color: #166534;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        input {
            padding: 10px;
            width: 260px;
            max-width: 90%;
            margin-bottom: 15px;
        }
        button {
            padding: 10px 15px;
            cursor: pointer;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 11px;
            text-align: left;
        }
        th { background: #1f2937; color: white; }
        .empty { text-align: center; padding: 20px; }
        .nav { margin-bottom: 20px; }
        .nav a { margin-right: 15px; }
    </style>
</head>
<body>
<div class="container">
    <div class="nav">
        <a href="{{ route('parking.index') }}">Parking</a>
        <a href="{{ route('vehicles.index') }}">Vehicles</a>
        <a href="{{ route('parking-slots.index') }}">Parking Slots</a>
    </div>

    <h1>Payment Management</h1>
    <p>View and manage completed parking payments.</p>

    @if (session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <a class="button" href="{{ route('payments.create') }}">
        + Record Payment
    </a>

    <form method="GET" action="{{ route('payments.index') }}">
        <input
            type="text"
            name="search"
            placeholder="Search plate number or owner"
            value="{{ $search }}"
        >
        <button type="submit">Search</button>
        <a href="{{ route('payments.index') }}">Reset</a>
    </form>

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
            <tr>
                <td colspan="7" class="empty">
                    No payments recorded yet.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $payments->links() }}
    </div>
</div>
</body>
</html>
