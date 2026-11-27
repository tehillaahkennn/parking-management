
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; padding: 25px; }
        .container { max-width: 900px; margin: auto; background: white; padding: 25px; border-radius: 10px; }
        .cards { display: flex; gap: 20px; flex-wrap: wrap; margin-top: 20px; }
        .card { flex: 1; min-width: 220px; border: 1px solid #ddd; border-radius: 8px; padding: 20px; }
        .button { display: inline-block; background: #2563eb; color: white; padding: 12px 16px; text-decoration: none; border-radius: 5px; margin-top: 10px; }
        a { color: #2563eb; }
    </style>
</head>
<body>
<div class="container">
    <p>
        <a href="{{ route('parking.index') }}">Parking</a> |
        <a href="{{ route('vehicles.index') }}">Vehicles</a> |
        <a href="{{ route('parking-slots.index') }}">Parking Slots</a> |
        <a href="{{ route('payments.index') }}">Payments</a>
    </p>

    <h1>Reports Management</h1>
    <p>Generate parking activity and payment revenue reports.</p>

    <div class="cards">
        <div class="card">
            <h2>Parking Activity</h2>
            <p>Review parking sessions, vehicle details, slot assignments, and session status.</p>
            <a class="button" href="{{ route('reports.parking') }}">View Parking Report</a>
        </div>

        <div class="card">
            <h2>Payment Revenue</h2>
            <p>Review payments received, payment methods, and total revenue for a date range.</p>
            <a class="button" href="{{ route('reports.payments') }}">View Payment Report</a>
        </div>
    </div>
</div>
</body>
</html>
