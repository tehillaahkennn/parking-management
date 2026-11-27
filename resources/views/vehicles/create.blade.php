
@extends('layouts.parking')

@section('title', 'Add Vehicle | Parking Management System')

@push('styles')
<style>
    .vehicle-create-panel {
        max-width: 850px;
    }

    .vehicle-create-form .form-group {
        margin-bottom: 22px;
    }

    .vehicle-create-form label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 9px;
        color: #344563;
    }

    .vehicle-create-form input,
    .vehicle-create-form select {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #dfe3eb;
        border-radius: 6px;
        font-size: 13px;
        outline: none;
        background: white;
        color: #344563;
    }

    .vehicle-create-form input:focus,
    .vehicle-create-form select:focus {
        border-color: #0866d9;
        box-shadow: 0 0 0 2px rgba(8, 102, 217, .08);
    }

    .vehicle-form-hint {
        color: #8792a8;
        font-size: 12px;
        margin-top: 7px;
    }

    .vehicle-error-box {
        background: #fff0f0;
        color: #a52834;
        padding: 14px 18px;
        border-radius: 6px;
        margin-bottom: 22px;
        font-size: 13px;
    }

    .vehicle-error-box ul {
        margin: 8px 0 0;
        padding-left: 20px;
    }

    .vehicle-create-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        padding-top: 10px;
    }

    @media (max-width: 600px) {
        .vehicle-create-panel .form-body {
            padding: 18px;
        }
    }
</style>
@endpush

@section('content')

<div class="welcome">
    <div>
        <h2>Add Vehicle</h2>
        <p>Add a new vehicle to the Parking Management System.</p>
    </div>

    <a href="{{ route('vehicles.index') }}" class="secondary-btn">
        ← Back to Vehicles
    </a>
</div>

<div class="records-panel vehicle-create-panel">

    <div class="records-header">
        <div>
            <h3>Vehicle Information</h3>
            <p class="panel-subtitle" style="margin:6px 0 0;">
                Enter the details of the vehicle you want to register.
            </p>
        </div>
    </div>

    <div class="form-body vehicle-create-form">

        @if ($errors->any())
            <div class="vehicle-error-box">
                <strong>Please correct the following errors:</strong>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('vehicles.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="plate_number">Plate Number</label>

                <input
                    type="text"
                    id="plate_number"
                    name="plate_number"
                    value="{{ old('plate_number') }}"
                    maxlength="20"
                    placeholder="Enter vehicle plate number"
                    required
                >

                <div class="vehicle-form-hint">
                    Enter the vehicle's registered plate number.
                </div>

                @error('plate_number')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="owner_name">Owner Name</label>

                <input
                    type="text"
                    id="owner_name"
                    name="owner_name"
                    value="{{ old('owner_name') }}"
                    maxlength="100"
                    placeholder="Enter vehicle owner's name"
                    required
                >

                @error('owner_name')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="vehicle_type">Vehicle Type</label>

                <select id="vehicle_type" name="vehicle_type" required>
                    <option value="">-- Select Vehicle Type --</option>

                    <option value="Motorcycle"
                        {{ old('vehicle_type') === 'Motorcycle' ? 'selected' : '' }}>
                        Motorcycle
                    </option>

                    <option value="Car"
                        {{ old('vehicle_type') === 'Car' ? 'selected' : '' }}>
                        Car
                    </option>

                    <option value="Van"
                        {{ old('vehicle_type') === 'Van' ? 'selected' : '' }}>
                        Van
                    </option>
                </select>

                @error('vehicle_type')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-actions vehicle-create-actions">
                <button type="submit" class="primary-btn">
                    Save Vehicle
                </button>

                <a href="{{ route('vehicles.index') }}" class="secondary-btn">
                    Cancel
                </a>
            </div>

        </form>

    </div>
</div>

@endsection
