<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Parking Record Details</title>
</head>
<body>
    <h1>Parking Record Details</h1>

    <p><strong>Record ID:</strong> {{ $parkingRecord->id }}</p>
    <p><strong>Plate Number:</strong> {{ $parkingRecord->vehicle?->plate_number }}</p>
    <p><strong>Owner:</strong> {{ $parkingRecord->vehicle?->owner_name }}</p>
    <p><strong>Vehicle Type:</strong> {{ $parkingRecord->vehicle?->vehicle_type }}</p>
    <p><strong>Parking Slot:</strong> {{ $parkingRecord->parkingSlot?->slot_number }}</p>
    <p><strong>Entry Time:</strong> {{ $parkingRecord->entry_time?->format('M d, Y h:i A') }}</p>
    <p><strong>Exit Time:</strong> {{ $parkingRecord->exit_time?->format('M d, Y h:i A') ?? 'Still parked' }}</p>
    <p><strong>Parking Fee:</strong> ₱{{ number_format((float) $parkingRecord->parking_fee, 2) }}</p>
    <p><strong>Status:</strong> {{ $parkingRecord->status }}</p>

    <p><a href="{{ route('parking.index') }}">Back to Parking Records</a></p>
</body>
</html>
