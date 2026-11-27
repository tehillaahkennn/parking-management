<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Staff Management</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background: #f5f7fb;
            color: #222;
        }

        h1 {
            margin-bottom: 10px;
        }

        a {
            color: #175cd3;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .message {
            padding: 10px;
            margin: 15px 0;
            border-radius: 5px;
        }

        .success {
            color: #166534;
            background: #dcfce7;
        }

        .error {
            color: #991b1b;
            background: #fee2e2;
        }

        .table-container {
            width: 100%;
            overflow-x: auto;
            background: white;
            border-radius: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 750px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #e8eef8;
        }

        .status-active {
            color: #166534;
            font-weight: bold;
        }

        .status-inactive {
            color: #991b1b;
            font-weight: bold;
        }

        button {
            padding: 7px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 2px;
        }

        .toggle-button {
            background: #dbeafe;
            color: #1e40af;
        }

        .delete-button {
            background: #fee2e2;
            color: #991b1b;
        }

        .actions {
            white-space: nowrap;
        }

        @media (max-width: 600px) {
            body {
                margin: 12px;
            }
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                background: white;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <h1>Staff Account Management</h1>

    <p class="no-print">
        <a href="{{ route('parking.index') }}">Parking Dashboard</a> |
        <a href="{{ route('staff.create') }}">Create Staff Account</a>
    </p>

    @if (session('success'))
        <div class="message success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="message error">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="message error">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th class="no-print">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($staff as $member)
                    <tr>
                        <td>{{ $member->id }}</td>
                        <td>{{ $member->name }}</td>
                        <td>{{ $member->email }}</td>
                        <td>{{ $member->role }}</td>

                        <td>
                            @if ($member->is_active)
                                <span class="status-active">Active</span>
                            @else
                                <span class="status-inactive">Inactive</span>
                            @endif
                        </td>

                        <td class="actions no-print">
                            <a href="{{ route('staff.edit', $member) }}">
                                Edit
                            </a>

                            <form
                                action="{{ route('staff.toggle-status', $member) }}"
                                method="POST"
                                style="display: inline;"
                                onsubmit="return confirm('Change this staff account status?');"
                            >
                                @csrf
                                @method('PATCH')

                                <button type="submit" class="toggle-button">
                                    {{ $member->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>

                            <form
                                action="{{ route('staff.destroy', $member) }}"
                                method="POST"
                                style="display: inline;"
                                onsubmit="return confirm('Delete this staff account?');"
                            >
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="delete-button">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No staff accounts found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="no-print">
        {{ $staff->links() }}
    </div>
</body>
</html>
