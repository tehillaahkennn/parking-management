
@extends('layouts.parking')

@section('title', 'Vehicle Management | Parking Management System')

@push('styles')
<style>
    .vehicle-stats {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .plate-number {
        font-weight: 700;
        color: #0866d9;
    }

    .vehicle-type {
        display: inline-block;
        background: #edf4ff;
        color: #0866d9;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .vehicle-table {
        min-width: 850px;
    }

    .action-group {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .edit-btn {
        display: inline-block;
        background: #e8f1ff;
        color: #2563eb;
        padding: 7px 11px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 12px;
    }

    .edit-btn:hover {
        background: #dbeafe;
    }

    .delete-btn {
        background: #fee2e2;
        color: #dc2626;
        border: none;
        padding: 7px 11px;
        border-radius: 5px;
        font-size: 12px;
        cursor: pointer;
    }

    .delete-btn:hover {
        background: #fecaca;
    }

    .cannot-delete {
        color: #999;
        font-size: 11px;
    }

    @media (max-width: 600px) {
        .vehicle-stats {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')

<div class="welcome">
    <div>
        <h2>Vehicle Management</h2>
        <p>Manage registered vehicles and their information.</p>
    </div>

    <a href="{{ route('vehicles.create') }}" class="primary-btn">
        + Add Vehicle
    </a>
</div>

{{-- VEHICLE SUMMARY --}}
<div class="stats vehicle-stats">

    <div class="stat-card blue">
        <div class="stat-title">Registered Vehicles</div>
        <div class="stat-number">{{ $vehicles->total() }}</div>
        <div class="stat-description">All registered vehicle records</div>
        <div class="stat-decoration">🚘</div>
    </div>

    <div class="stat-card green">
        <div class="stat-title">Vehicles on This Page</div>
        <div class="stat-number">{{ $vehicles->count() }}</div>
        <div class="stat-description">Vehicles currently displayed</div>
        <div class="stat-decoration">✓</div>
    </div>

</div>

{{-- VEHICLE TABLE --}}
<div class="records-panel">

    <div class="records-header">
        <div>
            <h3>Registered Vehicles</h3>
            <p class="panel-subtitle" style="margin:6px 0 0;">
                View, search, edit, and manage vehicle records.
            </p>
        </div>

        <span class="badge badge-neutral">
            {{ $vehicles->total() }} Vehicles
        </span>
    </div>

    {{-- SEARCH --}}
    <form action="{{ route('vehicles.index') }}"
          method="GET"
          class="table-search">

        <input
            type="text"
            name="search"
            placeholder="Search plate number or owner name..."
            value="{{ $search }}"
        >

        <button type="submit" class="primary-btn">
            Search
        </button>

        <a href="{{ route('vehicles.index') }}" class="secondary-btn">
            Clear
        </a>

    </form>

    {{-- TABLE --}}
    <div class="table-wrapper">

        <table class="vehicle-table">

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
                        <td>#{{ $vehicle->id }}</td>

                        <td class="plate-number">
                            {{ $vehicle->plate_number }}
                        </td>

                        <td>
                            {{ $vehicle->owner_name }}
                        </td>

                        <td>
                            <span class="vehicle-type">
                                {{ $vehicle->vehicle_type }}
                            </span>
                        </td>

                        <td>
                            {{ $vehicle->parking_records_count }}
                        </td>

                        <td>
                            <div class="action-group">

                                <a href="{{ route('vehicles.edit', $vehicle) }}"
                                   class="edit-btn">
                                    Edit
                                </a>

                                @if ($vehicle->parking_records_count == 0)

                                    <form
                                        action="{{ route('vehicles.destroy', $vehicle) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this vehicle?');"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="delete-btn">
                                            Delete
                                        </button>
                                    </form>

                                @else

                                    <span class="cannot-delete">
                                        Cannot delete (has history)
                                    </span>

                                @endif

                            </div>
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="empty">
                            No vehicles found.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{-- PAGINATION --}}
    <div class="pagination-wrapper">
        {{ $vehicles->appends(request()->query())->links() }}
    </div>

</div>

@endsection
