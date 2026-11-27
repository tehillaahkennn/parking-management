
@extends('layouts.parking')

@section('title', 'Register Vehicle | Parking Management System')

@section('content')

<!-- PAGE HEADING -->
<div class="welcome">
    <div>
        <h2>Register Vehicle</h2>
        <p>Enter vehicle information and assign an available parking slot.</p>
    </div>

    <a href="{{ route('parking.index') }}" class="secondary-btn">
        ← Back to Parking Records
    </a>
</div>

<!-- REGISTRATION FORM -->
<div class="form-panel">

    <h3>Vehicle Information</h3>

    <p class="form-subtitle">
        Please complete all required vehicle details below.
    </p>

    <form action="{{ route('parking.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="plate_number">
                Plate Number <span style="color:#c62828;">*</span>
            </label>

            <input
                type="text"
                id="plate_number"
                name="plate_number"
                value="{{ old('plate_number') }}"
                placeholder="Enter vehicle plate number"
                required
            >
        </div>

        <div class="form-group">
            <label for="owner_name">
                Owner Name <span style="color:#c62828;">*</span>
            </label>

            <input
                type="text"
                id="owner_name"
                name="owner_name"
                value="{{ old('owner_name') }}"
                placeholder="Enter vehicle owner's name"
                required
            >
        </div>

        <div class="form-group">
            <label for="vehicle_type">
                Vehicle Type <span style="color:#c62828;">*</span>
            </label>

            <select id="vehicle_type" name="vehicle_type" required>
                <option value="">-- Select Vehicle Type --</option>

                <option value="Car"
                    {{ old('vehicle_type') == 'Car' ? 'selected' : '' }}>
                    Car
                </option>

                <option value="Motorcycle"
                    {{ old('vehicle_type') == 'Motorcycle' ? 'selected' : '' }}>
                    Motorcycle
                </option>

                <option value="Van"
                    {{ old('vehicle_type') == 'Van' ? 'selected' : '' }}>
                    Van
                </option>
            </select>
        </div>

        <div class="form-group">
            <label for="parking_slot_id">
                Parking Slot <span style="color:#c62828;">*</span>
            </label>

            <select id="parking_slot_id" name="parking_slot_id" required>
                <option value="">-- Select Available Slot --</option>

                @foreach ($parkingSlots as $slot)
                    <option
                        value="{{ $slot->id }}"
                        {{ old('parking_slot_id') == $slot->id ? 'selected' : '' }}
                    >
                        {{ $slot->slot_number }} - {{ $slot->slot_type }}
                    </option>
                @endforeach
            </select>

            @if ($parkingSlots->isEmpty())
                <p class="field-error">
                    No available parking slots found.
                </p>
            @else
                <p class="form-help">
                    Only available parking slots are displayed.
                </p>
            @endif
        </div>

        <!-- FORM BUTTONS -->
        <div class="form-actions">

            <a href="{{ route('parking.index') }}" class="secondary-btn">
                Cancel
            </a>

            <button
                type="submit"
                class="primary-btn"
                @disabled($parkingSlots->isEmpty())
            >
                Register Vehicle
            </button>

        </div>

    </form>

</div>

@endsection
