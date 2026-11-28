
@extends('layouts.parking')

@section('title', 'Add Parking Slot | Parking Management System')

@section('content')

<div class="welcome">
    <div>
        <h2>Add Parking Slot</h2>
        <p>Register a new parking space.</p>
    </div>

    <a href="{{ route('parking-slots.index') }}" class="secondary-btn">
        Back to Parking Slots
    </a>
</div>

<div class="records-panel" style="max-width: 650px;">
    <div class="records-header">
        <h3>Parking Slot Information</h3>
    </div>

    <form action="{{ route('parking-slots.store') }}" method="POST"
          style="padding: 24px;">
        @csrf

        <div class="form-group">
            <label for="slot_number">Slot Number</label>
            <input
                type="text"
                id="slot_number"
                name="slot_number"
                value="{{ old('slot_number') }}"
                placeholder="Example: A01"
                required
            >

            @error('slot_number')
                <small style="color: red;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group" style="margin-top: 18px;">
            <label for="slot_type">Vehicle Type</label>

            <select id="slot_type" name="slot_type" required>
                <option value="">Select Vehicle Type</option>
                <option value="Motorcycle">Motorcycle</option>
                <option value="Car">Car</option>
                <option value="Van">Van</option>
            </select>

            @error('slot_type')
                <small style="color: red;">{{ $message }}</small>
            @enderror
        </div>

        <div style="display: flex; gap: 10px; margin-top: 24px;">
            <button type="submit" class="primary-btn">
                Save Parking Slot
            </button>

            <a href="{{ route('parking-slots.index') }}" class="secondary-btn">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection
