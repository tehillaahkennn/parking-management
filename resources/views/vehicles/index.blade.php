
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vehicle Management</title>
</head>
<body>
    <h1>Vehicle Management</h1>

    <p>
        <a href="{{ route('parking.index') }}">Parking Records</a> |
        <a href="{{ route('parking-slots.index') }}">Parking Slots</a> |
        <a href="{{ route('vehicles.create') }}">Register Vehicle</a>
    </p>

    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    @if (session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    <form action="{{ route('vehicles.index') }}" method="GET">
        <input
            type="text"
            name="search"
            placeholder="Search plate number or owner"
            value="{{ $search }}"
        >
        <button type="submit">Search</button>
        <a href="{{ route('vehicles.index') }}">Clear</a>
    </form>

    <br>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Plate Number</th>
                <th>Owner Name</th>
                <th>Vehicle Type</th>
                <th>Parking Records</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($vehicles as $vehicle)
                <tr>
                    <td>{{ $vehicle->id }}</td>
                    <td>{{ $vehicle->plate_number }}</td>
                    <td>{{ $vehicle->owner_name }}</td>
                    <td>{{ $vehicle->vehicle_type }}</td>
                    <td>{{ $vehicle->parking_records_count }}</td>
                    <td>
                        <a href="{{ route('vehicles.edit', $vehicle) }}">Edit</a>

                        @if ($vehicle->parking_records_count === 0)
                            <form
                                action="{{ route('vehicles.destroy', $vehicle) }}"
                                method="POST"
                                style="display: inline;"
                                onsubmit="return confirm('Are you sure you want to delete this vehicle?');"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                        @else
                            <span>Cannot delete (has history)</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No vehicles found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <br>

    {{ $vehicles->links() }}
</body>
</html>
