
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

        /* MAIN LAYOUT */
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

        /* MAIN CONTENT AREA */
        .main {
            width: calc(100% - 240px);
            margin-left: 240px;
            min-width: 0;
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

        .logout-btn:hover {
            background: #fbd5d5;
        }

        /* PAGE CONTENT */
        .content {
            padding: 25px 22px;
            min-height: calc(100vh - 75px);
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

        .alert ul {
            margin: 8px 0 0 20px;
        }

        .alert p {
            margin-top: 5px;
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

        /* CONTENT PANELS */
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

        /* TABLE CONTAINER */
        .records-panel {
            background: white;
            border-radius: 7px;
            box-shadow: 0 3px 12px rgba(40, 50, 90, .05);
            overflow: hidden;
            margin-bottom: 25px;
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

        .table-wrapper {
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

        .empty {
            text-align: center;
            padding: 35px;
            color: #8190a8;
        }

        /* BUTTONS */
        .primary-btn {
            display: inline-block;
            background: #0866e9;
            color: white;
            padding: 11px 16px;
            border: none;
            border-radius: 5px;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
            text-align: center;
        }

        .primary-btn:hover {
            background: #0756c4;
            color: white;
        }

        .secondary-btn {
            display: inline-block;
            background: #64748b;
            color: white;
            padding: 11px 16px;
            border: none;
            border-radius: 5px;
            font-size: 12px;
            cursor: pointer;
            text-align: center;
        }

        .secondary-btn:hover {
            background: #475569;
        }

        .danger-btn {
            display: inline-block;
            background: #ed4165;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            font-size: 11px;
            cursor: pointer;
        }

        .danger-btn:hover {
            background: #d92d51;
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

        /* STATUS BADGES */
        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
        }

        .badge-parked,
        .badge-available,
        .badge-success {
            background: #d9f7e7;
            color: #13864e;
        }

        .badge-completed,
        .badge-neutral {
            background: #edf0f5;
            color: #63718a;
        }

        .badge-occupied,
        .badge-danger {
            background: #ffe1e1;
            color: #c62828;
        }

        .badge-warning {
            background: #fff2d6;
            color: #a66b00;
        }

        /* FORMS */
        .form-panel {
            background: white;
            border-radius: 7px;
            padding: 25px;
            max-width: 850px;
            box-shadow: 0 3px 12px rgba(40, 50, 90, .05);
            margin-bottom: 25px;
        }

        .form-panel h3 {
            font-size: 16px;
            margin-bottom: 8px;
        }

        .form-subtitle {
            font-size: 12px;
            color: #8190a8;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: bold;
            color: #52617c;
            margin-bottom: 9px;
        }

        .form-control,
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #dfe3ec;
            border-radius: 5px;
            background: white;
            color: #52617c;
            font-size: 13px;
            outline: none;
        }

        .form-control:focus,
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #0866d9;
        }

        .form-group input[readonly] {
            background: #f8f9fc;
        }

        .form-help {
            color: #8190a8;
            font-size: 11px;
            margin-top: 7px;
        }

        .field-error {
            color: #c62828;
            font-size: 12px;
            margin-top: 6px;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 25px;
        }

        /* SEARCH */
        .table-search {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            padding: 18px 20px;
        }

        .table-search input {
            width: 280px;
            max-width: 100%;
            padding: 11px 14px;
            border: 1px solid #dfe3ec;
            border-radius: 5px;
            outline: none;
            font-size: 12px;
        }

        .table-search input:focus {
            border-color: #0866d9;
        }

        /* PAGINATION */
        .pagination-wrapper {
            padding: 20px;
            font-size: 12px;
        }

        .pagination-wrapper nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .pagination-wrapper a,
        .pagination-wrapper span {
            padding: 7px 11px;
        }

        /* FOOTER */
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
            }

            .profile {
                gap: 7px;
            }

            .profile-avatar {
                width: 30px;
                height: 30px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

<div class="layout">

    <!-- SIDEBAR -->
    <aside class="sidebar">

        <div class="logo">
            <span>🅿 PARKING SYSTEM</span>
        </div>

        <nav class="menu">

            <div class="menu-title">Main</div>

            <a href="{{ route('parking.index') }}"
               class="{{ request()->routeIs('parking.index') ? 'active' : '' }}">
                <span class="menu-icon">▦</span>
                Dashboard
            </a>

            <div class="menu-title">Parking Management</div>

            <a href="{{ route('parking.create') }}"
               class="{{ request()->routeIs('parking.create', 'parking.store') ? 'active' : '' }}">
                <span class="menu-icon">🚘</span>
                Register Vehicle
            </a>

            <a href="{{ route('parking-slots.index') }}"
               class="{{ request()->routeIs('parking-slots.*') ? 'active' : '' }}">
                <span class="menu-icon">▤</span>
                Parking Slots
            </a>

            <a href="{{ route('vehicles.index') }}"
               class="{{ request()->routeIs('vehicles.*') ? 'active' : '' }}">
                <span class="menu-icon">🚗</span>
                Vehicles
            </a>

            <a href="{{ route('payments.index') }}"
               class="{{ request()->routeIs('payments.*') ? 'active' : '' }}">
                <span class="menu-icon">₱</span>
                Payments
            </a>

            @if (auth()->check() && auth()->user()->role === 'Admin')

                <div class="menu-title">Administration</div>

                <a href="{{ route('staff.index') }}"
                   class="{{ request()->routeIs('staff.*') ? 'active' : '' }}">
                    <span class="menu-icon">♙</span>
                    Staff Management
                </a>

                <a href="{{ route('reports.index') }}"
                   class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <span class="menu-icon">▥</span>
                    Reports
                </a>

                <a href="{{ route('audit-logs.index') }}"
                   class="{{ request()->routeIs('audit-logs.*') ? 'active' : '' }}">
                    <span class="menu-icon">▣</span>
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
                    {{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 1)) : 'U' }}
                </div>

                <div>
                    <div class="profile-name">
                        {{ auth()->user()->name ?? 'User' }}
                    </div>

                    <div class="profile-role">
                        {{ auth()->user()->role ?? '' }}
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


        <!-- PAGE CONTENT -->
        <section class="content">

            <!-- SUCCESS MESSAGE -->
            @if (session('success'))
                <div class="alert success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- ERROR MESSAGES -->
            @if ($errors->any())
                <div class="alert error">
                    <strong>Please check the following errors:</strong>

                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- CHILD PAGE CONTENT -->
            @yield('content')

            <div class="footer">
                Parking Management System © {{ now()->year }}
            </div>

        </section>

    </main>

</div>

@stack('scripts')

</body>
</html>
