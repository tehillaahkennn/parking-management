<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Parking Management System</title>
</head>

<body>
    <h1>Parking Management System</h1>

    @auth
        <div style="margin-bottom: 20px;">
            <p>
                Logged in as:
                <strong>{{ auth()->user()->name }}</strong>
                ({{ auth()->user()->role }})
            </p>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>
    @endauth


    <p>
        <a href="{{ route('parking.create') }}">Register Vehicle</a> |
        <a href="{{ route('parking-slots.index') }}">View Parking Slots</a>
    </p>

    <h2>Parking Records</h2>

    @if (session('success'))
        <p style="color: green;">
            {{ session('success') }}
        </p>
    @endif

    @if ($errors->any())
        <div style="color: red;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <table border="1" cellpadding="8" cellspacing="0">
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
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($parkingRecords as $record)
                <tr>
                    <td>{{ $record->id }}</td>
                    <td>{{ $record->vehicle?->plate_number }}</td>
                    <td>{{ $record->vehicle?->owner_name }}</td>
                    <td>{{ $record->vehicle?->vehicle_type }}</td>
                    <td>{{ $record->parkingSlot?->slot_number }}</td>

                    <td>
                        {{ $record->entry_time?->format('M d, Y h:i A') }}
                    </td>

                    <td>
                        {{ $record->exit_time?->format('M d, Y h:i A') ?? 'Still parked' }}
                    </td>

                    <td>
                        ₱{{ number_format((float) $record->parking_fee, 2) }}
                    </td>

                    <td>{{ $record->status }}</td>

                    <td>
                        @if ($record->status === 'Parked')
                            <form
                                action="{{ route('parking.checkout', $record->id) }}"
                                method="POST"
                                onsubmit="return confirm('Are you sure you want to check out this vehicle?');"
                            >
                                @csrf
                                @method('PATCH')

                                <button type="submit">Check Out</button>
                            </form>
                        @else
                            Completed
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10">No parking records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
