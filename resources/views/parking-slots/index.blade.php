<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Parking Slots - Parking Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background: #f8fafc;
            color: #1e293b;
        }
        h1, h2 { color: #1e3a5f; }
        nav { margin-bottom: 24px; }
        nav a { margin-right: 14px; }
        a { color: #2563eb; }
        button {
            padding: 8px 14px;
            cursor: pointer;
        }
        .info {
            margin: 16px 0;
            padding: 14px;
            background: #e2e8f0;
            border-radius: 6px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 12px;
            text-align: left;
        }
        th { background: #e2e8f0; }
        .available { color: #15803d; font-weight: bold; }
        .occupied { color: #b91c1c; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Parking Management System</h1>
    <h2>Parking Slots</h2>

    @auth
        <div class="info">
            Logged in as:
            <strong>{{ auth()->user()->name }}</strong>
            ({{ auth()->user()->role }})

            <form action="{{ route('logout') }}" method="POST"
                  style="display:inline; margin-left:15px;">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>
    @endauth

    <nav>
        <a href="{{ route('parking.index') }}">Parking Records</a>
        <a href="{{ route('parking.create') }}">Register Vehicle</a>
        <a href="{{ route('parking-slots.index') }}">Refresh Slots</a>
    </nav>

    <p>View the current availability and type of each parking slot.</p>

    <table>
        <thead>
            <tr>
                <th>Slot ID</th>
                <th>Slot Number</th>
                <th>Slot Type</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($parkingSlots as $slot)
                <tr>
                    <td>{{ $slot->id }}</td>
                    <td>{{ $slot->slot_number }}</td>
                    <td>{{ $slot->slot_type }}</td>
                    <td class="{{ $slot->status === 'Available' ? 'available' : 'occupied' }}">
                        {{ $slot->status }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No parking slots found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p>Total parking slots: {{ $parkingSlots->count() }}</p>
</body>
</html>
