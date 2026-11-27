
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register Vehicle</title>
</head>
<body>
    <h1>Register Vehicle</h1>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('vehicles.store') }}" method="POST">
        @csrf

        <p>
            <label for="plate_number">Plate Number:</label><br>
            <input
                type="text"
                id="plate_number"
                name="plate_number"
                value="{{ old('plate_number') }}"
                maxlength="20"
                required
            >
        </p>

        <p>
            <label for="owner_name">Owner Name:</label><br>
            <input
                type="text"
                id="owner_name"
                name="owner_name"
                value="{{ old('owner_name') }}"
                maxlength="100"
                required
            >
        </p>

        <p>
            <label for="vehicle_type">Vehicle Type:</label><br>
            <select id="vehicle_type" name="vehicle_type" required>
                <option value="">-- Select Vehicle Type --</option>
                <option value="Motorcycle" {{ old('vehicle_type') === 'Motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                <option value="Car" {{ old('vehicle_type') === 'Car' ? 'selected' : '' }}>Car</option>
                <option value="Van" {{ old('vehicle_type') === 'Van' ? 'selected' : '' }}>Van</option>
            </select>
        </p>

        <button type="submit">Save Vehicle</button>
    </form>

    <p><a href="{{ route('vehicles.index') }}">Back to Vehicles</a></p>
</body>
</html>
