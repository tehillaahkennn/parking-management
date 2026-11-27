<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Parking Management System</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            font-family: Arial, sans-serif;
            background: #f1f5f9;
        }

        .register-container {
            width: 100%;
            max-width: 400px;
            padding: 35px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .logo {
            text-align: center;
            font-size: 42px;
            margin-bottom: 10px;
        }

        h1 {
            text-align: center;
            font-size: 24px;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .subtitle {
            text-align: center;
            color: #64748b;
            margin-bottom: 28px;
            font-size: 14px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
            color: #334155;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 18px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 15px;
        }

        input:focus {
            outline: 2px solid #93c5fd;
            border-color: #2563eb;
        }

        button {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 6px;
            background: #1d4ed8;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #1e40af;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            margin-bottom: 18px;
            border-radius: 6px;
            font-size: 14px;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            padding: 12px;
            margin-bottom: 18px;
            border-radius: 6px;
            font-size: 14px;
        }

        .auth-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #64748b;
        }

        .auth-link a {
            color: #1d4ed8;
            text-decoration: none;
            font-weight: bold;
        }

        .auth-link a:hover {
            text-decoration: underline;
        }

        .footer {
            text-align: center;
            color: #94a3b8;
            font-size: 12px;
            margin-top: 25px;
        }
    </style>
</head>
<body>

    <div class="register-container">
        <div class="logo">🅿️</div>

        <h1>Create an Account</h1>
        <p class="subtitle">Register to access the parking dashboard</p>

        @if (session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register.submit') }}">
            @csrf

            <label for="name">Full Name</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                placeholder="Enter your full name"
                autocomplete="name"
                required
                autofocus
            >

            <label for="email">Email Address</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="Enter your email"
                autocomplete="email"
                required
            >

            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Create a password"
                autocomplete="new-password"
                required
            >

            <label for="password_confirmation">Confirm Password</label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                placeholder="Confirm your password"
                autocomplete="new-password"
                required
            >

            <button type="submit">Register</button>
        </form>

        <div class="auth-link">
            Already have an account? <a href="{{ route('login') }}">Sign in here</a>
        </div>

        <div class="footer">
            Parking Management System &copy; {{ date('Y') }}
        </div>
    </div>

</body>
</html>