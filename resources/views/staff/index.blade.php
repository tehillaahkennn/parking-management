
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Staff Management</title>
</head>
<body>
    <h1>Staff Account Management</h1>

    <p>
        <a href="{{ route('parking.index') }}">Parking Dashboard</a> |
        <a href="{{ route('staff.create') }}">Create Staff Account</a>
    </p>

    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    @if (session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    @if ($errors->any())
        <div style="color: red;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
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
                        <a href="{{ route('staff.edit', $member) }}">Edit</a>

                        <form
                            action="{{ route('staff.destroy', $member) }}"
                            method="POST"
                            style="display: inline;"
                            onsubmit="return confirm('Delete this staff account?');"
                        >
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No staff accounts found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div>
        {{ $staff->links() }}
    </div>
</body>
</html>
