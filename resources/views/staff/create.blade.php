
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Staff Account</title>
</head>
<body>
    <h1>Create Parking Staff Account</h1>

    <p><a href="{{ route('staff.index') }}">Back to Staff List</a></p>

    @if ($errors->any())
        <div style="color: red;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('staff.store') }}" method="POST">
        @csrf

        <p>
            <label for="name">Full Name</label><br>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                required
            >
        </p>

        <p>
            <label for="email">Email Address</label><br>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
            >
        </p>

        <p>
            <label for="password">Password (minimum 8 characters)</label><br>
            <input
                type="password"
                id="password"
                name="password"
                minlength="8"
                required
            >
        </p>

        <p>
            <label for="password_confirmation">Confirm Password</label><br>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                minlength="8"
                required
            >
        </p>

        <button type="submit">Create Staff Account</button>
    </form>
</body>
</html>
