<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Parking Management System</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            color: #222;
        }

        nav {
            margin-bottom: 20px;
        }

        nav a {
            margin-right: 12px;
        }

        .message {
            padding: 10px;
            margin: 15px 0;
            border-radius: 4px;
        }

        .success {
            background: #e6f7e9;
            color: #17652a;
        }

        .error {
            background: #fdeaea;
            color: #a32121;
        }

        .filters {
            margin: 20px 0;
            padding: 15px;
            background: #f4f4f4;
        }

        input, select, button {
            padding: 7px;
            margin: 4px 4px 4px 0;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        th {
            background: #eaeaea;
        }

        .pagination {
            margin-top: 20px;
        }

        .pagination a,
        .pagination span {
            display: inline-block;
            padding: 7px 11px;
            margin: 2px;
            border: 1px solid #ccc;
            text-decoration: none;
        }

        .pagination .current {
            background: #ddd;
            font-weight: bold;
        }

        @media (max-width: 600px) {
            body {
                margin: 12px;
            }
        }
    </style>
</head>

<body>
    <h1>Parking Management System</h1>

    @auth
        <div style="margin-bottom: 20px;">
            <p>
                Logged in as:
                <strong>{{ auth()->user()->name }}</strong>
                ({{ auth()->user()->role }})
            </p>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>
    @endauth

    <nav>
        <a href="{{ route('parking.create') }}">Register Vehicle</a>
        <a href="{{ route('parking-slots.index') }}">View Parking Slots</a>
    </nav>

    <h2>Parking Records</h2>

    @if (session('success'))
        <div class="message success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="message error">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- Search and status filter --}}
    <form
        action="{{ route('parking.index') }}"
        method="GET"
        class="filters"
    >
        <div>
            <label for="search">Search Plate Number or Owner:</label><br>
            <input
                type="text"
                id="search"
                name="search"
                value="{{ request('search') }}"
                maxlength="100"
                placeholder="Enter plate number or owner"
            >
        </div>

        <div>
            <label for="status">Filter by Status:</label><br>
            <select name="status" id="status">
                <option value="">All Statuses</option>
                <option value="Parked"
                    {{ request('status') === 'Parked' ? 'selected' : '' }}>
                    Parked
                </option>
                <option value="Completed"
                    {{ request('status') === 'Completed' ? 'selected' : '' }}>
                    Completed
                </option>
            </select>
        </div>

        <button type="submit">Search / Filter</button>

        <a href="{{ route('parking.index') }}">Clear Filters</a>
    </form>

    <p>
        Showing {{ $parkingRecords->firstItem() ?? 0 }}
        to {{ $parkingRecords->lastItem() ?? 0 }}
        of {{ $parkingRecords->total() }} records.
    </p>

    <div class="table-container">
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
                        <td>{{ $record->vehicle?->plate_number }}</td>
                        <td>{{ $record->vehicle?->owner_name }}</td>
                        <td>{{ $record->vehicle?->vehicle_type }}</td>
                        <td>{{ $record->parkingSlot?->slot_number }}</td>

                        <td>
                            {{ $record->entry_time?->format('M d, Y h:i A') }}
                        </td>

                        <td>
                            {{ $record->exit_time?->format('M d, Y h:i A') ?? 'Still parked' }}
                        </td>

                        <td>
                            ₱{{ number_format((float) $record->parking_fee, 2) }}
                        </td>

                        <td>{{ $record->status }}</td>

                        <td>
                            <a href="{{ route('parking.show', $record->id) }}">
                                Details
                            </a>

                            @if ($record->status === 'Parked')
                                <form
                                    action="{{ route('parking.checkout', $record->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to check out this vehicle?');"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <button type="submit">Check Out</button>
                                </form>
                            @else
                                <p>Completed</p>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10">
                            No parking records found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if ($parkingRecords->hasPages())
        <div class="pagination">
            @if ($parkingRecords->onFirstPage())
                <span>Previous</span>
            @else
                <a href="{{ $parkingRecords->previousPageUrl() }}">
                    Previous
                </a>
            @endif

            @foreach ($parkingRecords->getUrlRange(
                max(1, $parkingRecords->currentPage() - 2),
                min($parkingRecords->lastPage(), $parkingRecords->currentPage() + 2)
            ) as $page => $url)
                @if ($page == $parkingRecords->currentPage())
                    <span class="current">{{ $page }}</span>
                @else
                    <a href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach

            @if ($parkingRecords->hasMorePages())
                <a href="{{ $parkingRecords->nextPageUrl() }}">
                    Next
                </a>
            @else
                <span>Next</span>
            @endif
        </div>
    @endif
</body>
</html>
