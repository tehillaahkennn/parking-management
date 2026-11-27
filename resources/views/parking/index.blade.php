
@extends('layouts.parking')

@section('title', 'Dashboard | Parking Management System')

@section('content')

@php
    $totalRecords = $parkingRecords->count();

    $parkedCount = $parkingRecords->where('status', 'Parked')->count();

    $completedCount = $parkingRecords->filter(
        fn ($record) => $record->status !== 'Parked'
    )->count();

    $totalFees = $parkingRecords->sum('parking_fee');

    $vehicleTypes = $parkingRecords->groupBy(
        fn ($record) => $record->vehicle?->vehicle_type ?? 'Unknown'
    );

    $maxVehicleCount = max(
        1,
        $vehicleTypes->map->count()->max() ?? 1
    );
@endphp

<!-- DASHBOARD WELCOME -->
<div class="welcome">
    <div>
        <h2>Hi, welcome back!</h2>
        <p>Parking Management System Dashboard.</p>
    </div>

    <div class="date-label">
        {{ now()->format('F d, Y') }}
    </div>
</div>


<!-- SUMMARY CARDS -->
<div class="stats">

    <div class="stat-card blue">
        <div class="stat-title">Total Parking Records</div>
        <div class="stat-number">{{ $totalRecords }}</div>
        <div class="stat-description">Records currently displayed</div>
        <div class="stat-decoration">▦</div>
    </div>

    <div class="stat-card pink">
        <div class="stat-title">Currently Parked</div>
        <div class="stat-number">{{ $parkedCount }}</div>
        <div class="stat-description">Vehicles still inside</div>
        <div class="stat-decoration">🚘</div>
    </div>

    <div class="stat-card green">
        <div class="stat-title">Completed Records</div>
        <div class="stat-number">{{ $completedCount }}</div>
        <div class="stat-description">Vehicles checked out</div>
        <div class="stat-decoration">✓</div>
    </div>

    <div class="stat-card orange">
        <div class="stat-title">Total Parking Fees</div>
        <div class="stat-number">
            ₱{{ number_format((float) $totalFees, 2) }}
        </div>
        <div class="stat-description">Fees in displayed records</div>
        <div class="stat-decoration">₱</div>
    </div>

</div>


<!-- DASHBOARD PANELS -->
<div class="panels">

    <!-- PARKING ACTIVITY -->
    <div class="panel">

        <h3>Parking Activity</h3>

        <p class="panel-subtitle">
            Overview of vehicle parking records.
        </p>

        <div class="status-summary">

            <div>
                <div class="status-number">{{ $totalRecords }}</div>
                <div class="status-label">
                    <span class="status-dot blue-dot"></span>
                    Total Records
                </div>
            </div>

            <div>
                <div class="status-number">{{ $parkedCount }}</div>
                <div class="status-label">
                    <span class="status-dot pink-dot"></span>
                    Parked
                </div>
            </div>

            <div>
                <div class="status-number">{{ $completedCount }}</div>
                <div class="status-label">
                    <span class="status-dot green-dot"></span>
                    Completed
                </div>
            </div>

        </div>


        <!-- CURRENTLY PARKED -->
        <div class="activity-row">

            <div class="activity-heading">
                <span>Currently Parked</span>
                <strong>{{ $parkedCount }}</strong>
            </div>

            <div class="progress">
                <div class="progress-bar"
                     style="width: {{ $totalRecords ? ($parkedCount / $totalRecords) * 100 : 0 }}%; background:#ed4165;">
                </div>
            </div>

        </div>


        <!-- COMPLETED -->
        <div class="activity-row">

            <div class="activity-heading">
                <span>Completed</span>
                <strong>{{ $completedCount }}</strong>
            </div>

            <div class="progress">
                <div class="progress-bar"
                     style="width: {{ $totalRecords ? ($completedCount / $totalRecords) * 100 : 0 }}%; background:#0ba77c;">
                </div>
            </div>

        </div>


        <!-- TOTAL RECORDS -->
        <div class="activity-row">

            <div class="activity-heading">
                <span>Total Records</span>
                <strong>{{ $totalRecords }}</strong>
            </div>

            <div class="progress">
                <div class="progress-bar"
                     style="width:100%; background:#0866e9;">
                </div>
            </div>

        </div>

    </div>


    <!-- VEHICLES BY TYPE -->
    <div class="panel">

        <h3>Vehicles by Type</h3>

        <p class="panel-subtitle">
            Vehicle types in the displayed parking records.
        </p>

        @forelse ($vehicleTypes as $type => $records)

            <div class="parking-info">
                <span>{{ $type }}</span>
                <strong>{{ $records->count() }}</strong>
            </div>

            <div class="progress">
                <div class="progress-bar"
                     style="width: {{ ($records->count() / $maxVehicleCount) * 100 }}%; background:#0866e9;">
                </div>
            </div>

        @empty

            <p class="empty">
                No vehicle information available.
            </p>

        @endforelse

    </div>

</div>


<!-- PARKING RECORDS TABLE -->
<div class="records-panel">

    <div class="records-header">

        <h3>Parking Records</h3>

        <div style="display:flex; gap:10px; flex-wrap:wrap;">

            <a href="{{ route('parking-slots.index') }}"
               class="primary-btn"
               style="background:#64748b;">
                View Parking Slots
            </a>

            <a href="{{ route('parking.create') }}"
               class="primary-btn">
                + Register Vehicle
            </a>

        </div>

    </div>


    <div class="table-wrapper">

        <table>

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

                        <td>
                            <strong>
                                {{ $record->vehicle?->plate_number }}
                            </strong>
                        </td>

                        <td>
                            {{ $record->vehicle?->owner_name }}
                        </td>

                        <td>
                            {{ $record->vehicle?->vehicle_type }}
                        </td>

                        <td>
                            {{ $record->parkingSlot?->slot_number }}
                        </td>

                        <td>
                            {{ $record->entry_time?->format('M d, Y h:i A') }}
                        </td>

                        <td>
                            {{ $record->exit_time?->format('M d, Y h:i A') ?? 'Still parked' }}
                        </td>

                        <td>
                            ₱{{ number_format((float) $record->parking_fee, 2) }}
                        </td>

                        <td>

                            @if ($record->status === 'Parked')

                                <span class="badge badge-parked">
                                    Parked
                                </span>

                            @else

                                <span class="badge badge-completed">
                                    {{ $record->status }}
                                </span>

                            @endif

                        </td>

                        <td>

                            @if ($record->status === 'Parked')

                                <form
                                    action="{{ route('parking.checkout', $record->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to check out this vehicle?');"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <button type="submit" class="checkout-btn">
                                        Check Out
                                    </button>

                                </form>

                            @else

                                <span style="color:#8190a8;">
                                    Completed
                                </span>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="10" class="empty">
                            No parking records found.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
