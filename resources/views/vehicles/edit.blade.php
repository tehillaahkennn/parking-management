
@extends('layouts.parking')

@section('title', 'Edit Vehicle | Parking Management System')

@push('styles')
<style>
    .vehicle-edit-panel {
        max-width: 850px;
    }

    .vehicle-edit-form .form-group {
        margin-bottom: 22px;
    }

    .vehicle-edit-form label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 9px;
        color: #344563;
    }

    .vehicle-edit-form input,
    .vehicle-edit-form select {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #dfe3eb;
        border-radius: 6px;
        font-size: 13px;
        outline: none;
        background: white;
        color: #344563;
    }

    .vehicle-edit-form input:focus,
    .vehicle-edit-form select:focus {
        border-color: #0866d9;
        box-shadow: 0 0 0 2px rgba(8, 102, 217, .08);
    }

    .vehicle-edit-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        padding-top: 10px;
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

    @media (max-width: 600px) {
        .vehicle-edit-panel .form-body {
            padding: 18px;
        }
    }
</style>
@endpush

@section('content')

<div class="welcome">
    <div>
        <h2>Edit Vehicle</h2>
        <p>Update the registered vehicle's information.</p>
    </div>

    <a href="{{ route('vehicles.index') }}" class="secondary-btn">
        ← Back to Vehicles
    </a>
</div>

<div class="records-panel vehicle-edit-panel">

    <div class="records-header">
        <div>
            <h3>Vehicle Information</h3>
            <p class="panel-subtitle" style="margin:6px 0 0;">
                Make changes to the selected vehicle.
            </p>
        </div>
    </div>

    <div class="form-body vehicle-edit-form">

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

        <form action="{{ route('vehicles.update', $vehicle) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="plate_number">Plate Number</label>

                <input
                    type="text"
                    id="plate_number"
                    name="plate_number"
                    value="{{ old('plate_number', $vehicle->plate_number) }}"
                    maxlength="20"
                    required
                >

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
                    value="{{ old('owner_name', $vehicle->owner_name) }}"
                    maxlength="100"
                    required
                >

                @error('owner_name')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="vehicle_type">Vehicle Type</label>

                <select id="vehicle_type" name="vehicle_type" required>
                    <option value="Motorcycle"
                        {{ old('vehicle_type', $vehicle->vehicle_type) === 'Motorcycle' ? 'selected' : '' }}>
                        Motorcycle
                    </option>

                    <option value="Car"
                        {{ old('vehicle_type', $vehicle->vehicle_type) === 'Car' ? 'selected' : '' }}>
                        Car
                    </option>

                    <option value="Van"
                        {{ old('vehicle_type', $vehicle->vehicle_type) === 'Van' ? 'selected' : '' }}>
                        Van
                    </option>
                </select>

                @error('vehicle_type')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-actions vehicle-edit-actions">
                <button type="submit" class="primary-btn">
                    Update Vehicle
                </button>

                <a href="{{ route('vehicles.index') }}" class="secondary-btn">
                    Cancel
                </a>
            </div>

        </form>

    </div>
</div>

@endsection
