
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Staff Account</title>
</head>
<body>
    <h1>Edit Parking Staff Account</h1>

    <p><a href="{{ route('staff.index') }}">Back to Staff List</a></p>

    <p>Leave the password fields empty if you do not want to change the password.</p>

    @if ($errors->any())
        <div style="color: red;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('staff.update', $staff) }}" method="POST">
        @csrf
        @method('PUT')

        <p>
            <label for="name">Full Name</label><br>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name', $staff->name) }}"
                required
            >
        </p>

        <p>
            <label for="email">Email Address</label><br>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email', $staff->email) }}"
                required
            >
        </p>

        <p>
            <label for="password">New Password (optional)</label><br>
            <input
                type="password"
                id="password"
                name="password"
                minlength="8"
                autocomplete="new-password"
            >
        </p>

        <p>
            <label for="password_confirmation">Confirm New Password</label><br>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                minlength="8"
                autocomplete="new-password"
            >
        </p>

        <button type="submit">Save Changes</button>
    </form>
</body>
</html>
