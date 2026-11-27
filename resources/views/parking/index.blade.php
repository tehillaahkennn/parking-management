
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Parking Management System')</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            background: #edf0f8;
            color: #172b4d;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            width: 240px;
            background: #fff;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            overflow-y: auto;
            border-right: 1px solid #e5e7eb;
            z-index: 10;
        }

        .logo {
            height: 75px;
            display: flex;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
            color: #0866d9;
            font-size: 19px;
            font-weight: bold;
        }

        .menu-title {
            padding: 27px 25px 12px;
            font-size: 11px;
            font-weight: bold;
            color: #263858;
            text-transform: uppercase;
        }

        .menu a {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 13px 24px;
            font-size: 14px;
            color: #526789;
            border-left: 3px solid transparent;
            transition: .2s;
        }

        .menu a:hover {
            color: #0866d9;
            background: #f4f7ff;
        }

        .menu a.active {
            color: #0866d9;
            background: #f2f6ff;
            border-left-color: #0866d9;
        }

        .menu-icon {
            width: 20px;
            text-align: center;
            font-size: 17px;
        }

        /* MAIN */
        .main {
            width: calc(100% - 240px);
            margin-left: 240px;
        }

        /* TOPBAR */
        .topbar {
            height: 75px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 25px;
            border-bottom: 1px solid #e5e7eb;
        }

        .search {
            width: 350px;
            max-width: 45%;
            padding: 13px 20px;
            border: none;
            outline: none;
            border-radius: 30px;
            background: #edf0f8;
            font-size: 13px;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 12px;
            text-align: right;
        }

        .profile-avatar {
            width: 38px;
            height: 38px;
            background: #dbeafe;
            color: #0866d9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .profile-name {
            font-size: 13px;
            font-weight: bold;
        }

        .profile-role {
            font-size: 11px;
            color: #8190a8;
            margin-top: 4px;
        }

        .logout-btn {
            border: none;
            background: #fce8e8;
            color: #dc3545;
            padding: 9px 13px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
        }

        /* CONTENT */
        .content {
            padding: 25px 22px;
        }

        .welcome {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        .welcome h2 {
            font-size: 22px;
            margin-bottom: 5px;
        }

        .welcome p {
            color: #8290aa;
            font-size: 13px;
        }

        .date-label {
            color: #8290aa;
            font-size: 12px;
        }

        /* ALERTS */
        .alert {
            padding: 13px 16px;
            border-radius: 6px;
            margin-bottom: 18px;
            font-size: 13px;
        }

        .success {
            background: #d9f7e7;
            color: #13864e;
        }

        .error {
            background: #ffe1e1;
            color: #c62828;
        }

        /* SUMMARY CARDS */
        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            margin-bottom: 25px;
        }

        .stat-card {
            color: white;
            padding: 20px 17px;
            border-radius: 6px;
            min-height: 135px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(40, 50, 90, .10);
        }

        .stat-card.blue {
            background: linear-gradient(120deg, #0866e9, #72a9f5);
        }

        .stat-card.pink {
            background: linear-gradient(120deg, #ed4165, #fa8197);
        }

        .stat-card.green {
            background: linear-gradient(120deg, #0ba77c, #37cba0);
        }

        .stat-card.orange {
            background: linear-gradient(120deg, #f36d32, #f7a65e);
        }

        .stat-title {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .stat-number {
            font-size: 25px;
            font-weight: bold;
            margin: 17px 0 7px;
        }

        .stat-description {
            font-size: 12px;
            opacity: .9;
        }

        .stat-decoration {
            position: absolute;
            right: 12px;
            bottom: 8px;
            font-size: 27px;
            opacity: .25;
        }

        /* DASHBOARD PANELS */
        .panels {
            display: grid;
            grid-template-columns: 1.35fr 1fr;
            gap: 25px;
            margin-bottom: 25px;
        }

        .panel {
            background: #fff;
            border-radius: 7px;
            padding: 22px;
            box-shadow: 0 3px 12px rgba(40, 50, 90, .05);
            min-width: 0;
        }

        .panel h3 {
            font-size: 15px;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .panel-subtitle {
            font-size: 12px;
            color: #8190a8;
            margin-bottom: 25px;
        }

        .status-summary {
            display: flex;
            gap: 35px;
            flex-wrap: wrap;
            margin-bottom: 25px;
        }

        .status-number {
            font-size: 23px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .status-label {
            font-size: 12px;
            color: #71809a;
        }

        .status-dot {
            display: inline-block;
            width: 9px;
            height: 9px;
            border-radius: 50%;
            margin-right: 5px;
        }

        .blue-dot { background: #0866e9; }
        .pink-dot { background: #ed4165; }
        .green-dot { background: #0ba77c; }

        .activity-row {
            margin: 18px 0;
        }

        .activity-heading {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            margin-bottom: 8px;
        }

        .progress {
            height: 9px;
            background: #edf0f8;
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            border-radius: 10px;
        }

        .parking-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #edf0f5;
            font-size: 13px;
        }

        .parking-info:last-child {
            border-bottom: none;
        }

        .parking-info strong {
            font-size: 15px;
        }

        /* TABLE */
        .records-panel {
            background: white;
            border-radius: 7px;
            box-shadow: 0 3px 12px rgba(40, 50, 90, .05);
            overflow: hidden;
        }

        .records-header {
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid #edf0f5;
        }

        .records-header h3 {
            font-size: 16px;
        }

        .primary-btn {
            display: inline-block;
            background: #0866e9;
            color: white;
            padding: 11px 16px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: bold;
        }

        .primary-btn:hover {
            background: #0756c4;
        }

        .table-wrapper {
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
            white-space: nowrap;
            font-size: 12px;
        }

        thead {
            background: #f8f9fc;
        }

        th {
            padding: 15px 13px;
            text-align: left;
            color: #52617c;
            font-size: 11px;
            text-transform: uppercase;
        }

        td {
            padding: 14px 13px;
            border-top: 1px solid #edf0f5;
            color: #52617c;
        }

        tbody tr:hover {
            background: #f9fbff;
        }

        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
        }

        .badge-parked {
            background: #d9f7e7;
            color: #13864e;
        }

        .badge-completed {
            background: #edf0f5;
            color: #63718a;
        }

        .checkout-btn {
            background: #ed4165;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 8px 10px;
            cursor: pointer;
            font-size: 11px;
        }

        .checkout-btn:hover {
            background: #d92d51;
        }

        .empty {
            text-align: center;
            padding: 35px;
            color: #8190a8;
        }

        .footer {
            padding: 20px 0;
            text-align: center;
            color: #8190a8;
            font-size: 11px;
        }

        /* RESPONSIVE */
        @media (max-width: 1100px) {
            .stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .panels {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }

            .main {
                width: 100%;
                margin-left: 0;
            }

            .topbar {
                padding: 0 15px;
            }

            .search {
                width: 180px;
            }

            .content {
                padding: 18px 12px;
            }

            .welcome {
                align-items: flex-start;
                flex-direction: column;
            }

            .stats {
                gap: 12px;
            }

            .stat-card {
                padding: 15px;
            }

            .stat-number {
                font-size: 21px;
            }

            .records-header {
                align-items: flex-start;
                flex-direction: column;
            }
        }

        @media (max-width: 420px) {
            .stats {
                grid-template-columns: 1fr;
            }

            .profile-name {
                font-size: 11px;
            }

            .logout-btn {
                padding: 8px;
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
    $maxVehicleCount = max(1, $vehicleTypes->map->count()->max() ?? 1);
@endphp

<nav>
    <a href="{{ route('parking.create') }}">Register Vehicle</a>
    <a href="{{ route('parking-slots.index') }}">View Parking Slots</a>
</nav>

<div class="layout">

    <!-- SIDEBAR -->
    <aside class="sidebar">

        <div class="logo">
            <span>PARKING SYSTEM</span>
        </div>

        <nav class="menu">

            <div class="menu-title">Main</div>

            <a href="{{ route('parking.index') }}" class="active">
                <span class="menu-icon">&#9632;</span>
                Dashboard
            </a>

            <div class="menu-title">Parking Management</div>

            <a href="{{ route('parking.create') }}">
                <span class="menu-icon">&#128664;</span>
                Register Vehicle
            </a>

            <a href="{{ route('parking-slots.index') }}">
                <span class="menu-icon">&#9633;</span>
                Parking Slots
            </a>

            <a href="{{ route('vehicles.index') }}">
                <span class="menu-icon">&#128663;</span>
                Vehicles
            </a>

            <a href="{{ route('payments.index') }}">
                <span class="menu-icon">&#8369;</span>
                Payments
            </a>

            @if (auth()->user()->role === 'Admin')

                <div class="menu-title">Administration</div>

                <a href="{{ route('staff.index') }}">
                    <span class="menu-icon">&#9824;</span>
                    Staff Management
                </a>

                <a href="{{ route('reports.index') }}">
                    <span class="menu-icon">&#9632;</span>
                    Reports
                </a>

                <a href="{{ route('audit-logs.index') }}">
                    <span class="menu-icon">&#9633;</span>
                    Audit Logs
                </a>

            @endif

        </nav>
    </aside>


    <!-- MAIN CONTENT -->
    <main class="main">

        <!-- TOPBAR -->
        <header class="topbar">

            <input
                type="search"
                class="search"
                placeholder="Search for anything..."
                aria-label="Search"
            >

            <div class="profile">

                <div class="profile-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

                <div>
                    <div class="profile-name">
                        {{ auth()->user()->name }}
                    </div>

                    <div class="profile-role">
                        {{ auth()->user()->role }}
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="logout-btn" type="submit">
                        Logout
                    </button>
                </form>

            </div>
        </header>


        <!-- DASHBOARD -->
        <section class="content">

            <div class="welcome">
                <div>
                    <h2>Hi, welcome back!</h2>
                    <p>Parking Management System Dashboard.</p>
                </div>

                <div class="date-label">
                    {{ now()->format('F d, Y') }}
                </div>
            </div>


            <!-- ALERTS -->
            @if (session('success'))
                <div class="alert success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert error">
                    <strong>Please check the following errors:</strong>

                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif


            <!-- SUMMARY CARDS -->
            <div class="stats">

                <div class="stat-card blue">
                    <div class="stat-title">Total Parking Records</div>
                    <div class="stat-number">{{ $totalRecords }}</div>
                    <div class="stat-description">Records currently displayed</div>
                    <div class="stat-decoration">&#9632;</div>
                </div>

                <div class="stat-card pink">
                    <div class="stat-title">Currently Parked</div>
                    <div class="stat-number">{{ $parkedCount }}</div>
                    <div class="stat-description">Vehicles still inside</div>
                    <div class="stat-decoration">&#128664;</div>
                </div>

                <div class="stat-card green">
                    <div class="stat-title">Completed Records</div>
                    <div class="stat-number">{{ $completedCount }}</div>
                    <div class="stat-description">Vehicles checked out</div>
                    <div class="stat-decoration">&#10003;</div>
                </div>

                <div class="stat-card orange">
                    <div class="stat-title">Total Parking Fees</div>
                    <div class="stat-number">
                        &#8369;{{ number_format((float) $totalFees, 2) }}
                    </div>
                    <div class="stat-description">Fees in displayed records</div>
                    <div class="stat-decoration">&#8369;</div>
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


                <!-- VEHICLE TYPES -->
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

                        <p class="empty">No vehicle information available.</p>

                    @endforelse

                </div>

            </div>


            <!-- PARKING RECORDS -->
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

            <div class="footer">
                Parking Management System &copy; {{ now()->year }}
            </div>

        </section>

    </main>

</div>

</body>
</html>

