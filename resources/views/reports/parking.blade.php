
@extends('layouts.parking')

@section('title', 'Parking Report | Parking Management System')

@section('content')

<style>
    .report-heading {
        margin-bottom: 22px;
    }

    .report-heading h2 {
        margin: 0 0 7px;
        color: #172b4d;
        font-size: 24px;
    }

    .report-heading p {
        color: #7b8495;
        margin: 0;
        font-size: 14px;
    }

    .filter-panel,
    .records-panel {
        background: #fff;
        border-radius: 12px;
        padding: 22px;
        margin-bottom: 22px;
        box-shadow: 0 3px 15px rgba(0,0,0,.04);
        border: 1px solid #edf0f5;
    }

    .filter-panel h3,
    .records-header h3 {
        margin: 0 0 18px;
        color: #172b4d;
        font-size: 17px;
    }

    .filter-form {
        display: flex;
        align-items: end;
        flex-wrap: wrap;
        gap: 15px;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 7px;
        min-width: 190px;
    }

    .filter-group label {
        font-size: 13px;
        font-weight: 600;
        color: #526078;
    }

    .filter-group input {
        padding: 10px 12px;
        border: 1px solid #dce2ec;
        border-radius: 7px;
        font-size: 13px;
        outline: none;
    }

    .filter-group input:focus {
        border-color: #0866d9;
    }

    .filter-btn,
    .reset-btn,
    .back-btn {
        display: inline-block;
        padding: 11px 17px;
        border-radius: 7px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        border: none;
    }

    .filter-btn {
        background: #0866d9;
        color: white;
    }

    .filter-btn:hover {
        background: #0754b3;
        color: white;
    }

    .reset-btn {
        background: #edf0f5;
        color: #526078;
    }

    .back-btn {
        background: #e9efff;
        color: #0866d9;
    }

    .records-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 18px;
    }

    .records-header h3 {
        margin: 0;
    }

    .record-count {
        font-size: 13px;
        color: #7b8495;
    }

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .report-table {
        width: 100%;
        min-width: 1050px;
        border-collapse: collapse;
    }

    .report-table th {
        background: #f5f7fb;
        color: #526078;
        padding: 13px 12px;
        text-align: left;
        font-size: 12px;
        white-space: nowrap;
    }

    .report-table td {
        padding: 14px 12px;
        border-bottom: 1px solid #edf0f5;
        color: #526078;
        font-size: 13px;
        white-space: nowrap;
    }

    .report-table tbody tr:hover {
        background: #f9fbff;
    }

    .plate-number {
        font-weight: 700;
        color: #172b4d;
    }

    .badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .badge-parked {
        background: #fff3cd;
        color: #856404;
    }

    .badge-completed {
        background: #d1f7df;
        color: #187a3d;
    }

    .empty-state {
        text-align: center;
        padding: 40px 15px;
        color: #8993a5;
        font-size: 14px;
    }

    .pagination-wrapper {
        margin-top: 20px;
    }

    .validation-error {
        color: #dc3545;
        font-size: 12px;
        margin-top: 6px;
    }

    @media(max-width: 600px) {
        .filter-group {
            width: 100%;
        }

        .filter-form > a,
        .filter-form > button {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="report-heading">
    <h2>Parking Report</h2>
    <p>View and monitor all vehicle parking records.</p>
</div>

<div class="filter-panel">

    <h3>Filter Parking Records</h3>

    <form action="{{ route('reports.parking') }}" method="GET" class="filter-form">

        <div class="filter-group">
            <label for="date_from">Date From</label>
            <input
                type="date"
                name="date_from"
                id="date_from"
                value="{{ $validated['date_from'] ?? '' }}"
            >

            @error('date_from')
                <span class="validation-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="filter-group">
            <label for="date_to">Date To</label>
            <input
                type="date"
                name="date_to"
                id="date_to"
                value="{{ $validated['date_to'] ?? '' }}"
            >

            @error('date_to')
                <span class="validation-error">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="filter-btn">
            Apply Filter
        </button>

        <a href="{{ route('reports.parking') }}" class="reset-btn">
            Reset
        </a>

        <a href="{{ route('reports.index') }}" class="back-btn">
            Back to Reports
        </a>

    </form>

</div>

<div class="records-panel">

    <div class="records-header">
        <h3>Parking Records</h3>

        <span class="record-count">
            Total Records: {{ $records->total() }}
        </span>
    </div>

    <div class="table-wrapper">

        <table class="report-table">

            <thead>
                <tr>
                    <th>#</th>
                    <th>Plate Number</th>
                    <th>Vehicle Owner</th>
                    <th>Vehicle Type</th>
                    <th>Parking Slot</th>
                    <th>Entry Time</th>
                    <th>Exit Time</th>
                    <th>Parking Fee</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

                @forelse($records as $record)

                    <tr>
                        <td>{{ $records->firstItem() + $loop->index }}</td>

                        <td class="plate-number">
                            {{ $record->vehicle?->plate_number ?? 'N/A' }}
                        </td>

                        <td>
                            {{ $record->vehicle?->owner_name ?? 'N/A' }}
                        </td>

                        <td>
                            {{ $record->vehicle?->vehicle_type ?? 'N/A' }}
                        </td>

                        <td>
                            {{ $record->parkingSlot?->slot_number ?? 'N/A' }}
                        </td>

                        <td>
                            {{ $record->entry_time?->format('M d, Y h:i A') ?? 'N/A' }}
                        </td>

                        <td>
                            {{ $record->exit_time?->format('M d, Y h:i A') ?? 'Not yet exited' }}
                        </td>

                        <td>
                            ₱{{ number_format((float) $record->parking_fee, 2) }}
                        </td>

                        <td>
                            @if($record->status === 'Parked')
                                <span class="badge badge-parked">
                                    Parked
                                </span>
                            @else
                                <span class="badge badge-completed">
                                    {{ $record->status }}
                                </span>
                            @endif
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="9" class="empty-state">
                            No parking records found.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="pagination-wrapper">
        {{ $records->links() }}
    </div>

</div>

@endsection
