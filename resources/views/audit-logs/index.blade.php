<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Trail</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 24px;
            color: #222;
        }

        nav {
            margin-bottom: 24px;
        }

        nav a {
            margin-right: 12px;
        }

        .search-form {
            margin: 20px 0;
        }

        input, button {
            padding: 8px;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            overflow-wrap: anywhere;
        }

        th {
            background: #f2f2f2;
        }

        .pagination {
            margin-top: 20px;
        }

        @media print {
            nav, .search-form, .pagination, .no-print {
                display: none;
            }

            body {
                margin: 0;
                font-size: 11px;
            }

            table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <nav>
        <a href="{{ route('reports.index') }}">Reports</a>
        <a href="{{ route('parking.index') }}">Parking</a>
        <a href="{{ route('payments.index') }}">Payments</a>
        <a href="{{ route('staff.index') }}">Staff</a>
    </nav>

    <h1>Audit Trail</h1>
    <p>View system activities recorded by the application.</p>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <form method="GET" action="{{ route('audit-logs.index') }}"
          class="search-form">
        <label for="search">Search logs:</label>
        <input
            type="text"
            id="search"
            name="search"
            value="{{ request('search') }}"
            placeholder="Action, user, description, IP..."
        >
        <button type="submit">Search</button>
        <a href="{{ route('audit-logs.index') }}">Reset</a>
        <button type="button" onclick="window.print()">Print</button>
    </form>

    <p>
        <strong>Records shown:</strong>
        {{ $auditLogs->total() }}
    </p>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>IP Address</th>
                    <th>Date and Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($auditLogs as $log)
                    <tr>
                        <td>{{ $log->id }}</td>
                        <td>
                            {{ $log->user?->name ?? 'System / Unknown' }}
                        </td>
                        <td>{{ $log->action }}</td>
                        <td>{{ $log->description }}</td>
                        <td>{{ $log->ip_address ?? 'N/A' }}</td>
                        <td>{{ $log->created_at?->format('M d, Y h:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No audit logs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $auditLogs->links() }}
    </div>
</body>
</html>