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

    <form action="{{ route('parking.store') }}" method="POST">
        @csrf

        <p>
            <label>Plate Number:</label><br>
            <input type="text" name="plate_number"
                   value="{{ old('plate_number') }}" required>
        </p>

        <p>
            <label>Owner Name:</label><br>
            <input type="text" name="owner_name"
                   value="{{ old('owner_name') }}" required>
        </p>

        <p>
            <label>Vehicle Type:</label><br>
            <select name="vehicle_type" required>
                <option value="Car">Car</option>
                <option value="Motorcycle">Motorcycle</option>
                <option value="Van">Van</option>
            </select>
        </p>

        <p>
            <label>Parking Slot:</label><br>
            <select name="parking_slot_id" required>
                <option value="">-- Select Available Slot --</option>
                @foreach ($parkingSlots as $slot)
                    <option value="{{ $slot->id }}"
                        {{ old('parking_slot_id') == $slot->id ? 'selected' : '' }}>
                        {{ $slot->slot_number }} - {{ $slot->slot_type }}
                    </option>
                @endforeach
            </select>
        </p>

        <button type="submit">Register Vehicle</button>
    </form>

    <p><a href="{{ route('parking.index') }}">Back to Parking Records</a></p>
</body>
</html>
