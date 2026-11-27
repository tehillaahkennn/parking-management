
@extends('layouts.parking')

@section('title', 'Parking Slots | Parking Management System')

@push('styles')
<style>
    .stats {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .slot-number {
        font-weight: bold;
        color: #172b4d;
    }

    .slot-type {
        color: #52617c;
    }

    @media (max-width: 768px) {
        .stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 420px) {
        .stats {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')

@php
    $totalSlots = $parkingSlots->count();

    $availableSlots = $parkingSlots->where('status', 'Available')->count();

    $occupiedSlots = $parkingSlots->where('status', 'Occupied')->count();
@endphp

<!-- PAGE HEADING -->
<div class="welcome">
    <div>
        <h2>Parking Slots</h2>
        <p>Manage and monitor all parking spaces in your parking area.</p>
    </div>

    <div>
        <a href="{{ route('parking-slots.index') }}" class="secondary-btn">
            ↻ Refresh
        </a>
    </div>
</div>

<!-- SUMMARY CARDS -->
<div class="stats">

    <div class="stat-card blue">
        <div class="stat-title">Total Parking Slots</div>
        <div class="stat-number">{{ $totalSlots }}</div>
        <div class="stat-description">All registered parking spaces</div>
        <div class="stat-decoration">▤</div>
    </div>

    <div class="stat-card green">
        <div class="stat-title">Available Slots</div>
        <div class="stat-number">{{ $availableSlots }}</div>
        <div class="stat-description">Slots ready for vehicles</div>
        <div class="stat-decoration">✓</div>
    </div>

    <div class="stat-card pink">
        <div class="stat-title">Occupied Slots</div>
        <div class="stat-number">{{ $occupiedSlots }}</div>
        <div class="stat-description">Currently occupied spaces</div>
        <div class="stat-decoration">🚘</div>
    </div>

</div>

<!-- PARKING SLOTS TABLE -->
<div class="records-panel">

    <div class="records-header">
        <div>
            <h3>Parking Slot List</h3>
            <p class="panel-subtitle" style="margin: 6px 0 0;">
                View the current status of every parking slot.
            </p>
        </div>

        <span class="badge badge-neutral">
            {{ $totalSlots }} Total Slots
        </span>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Slot Number</th>
                    <th>Vehicle Type</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($parkingSlots as $slot)
                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        <td>
                            <span class="slot-number">
                                {{ $slot->slot_number }}
                            </span>
                        </td>

                        <td>
                            <span class="slot-type">
                                {{ $slot->slot_type }}
                            </span>
                        </td>

                        <td>
                            @if ($slot->status === 'Available')
                                <span class="badge badge-available">
                                    Available
                                </span>
                            @else
                                <span class="badge badge-occupied">
                                    Occupied
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="empty">
                            No parking slots found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
