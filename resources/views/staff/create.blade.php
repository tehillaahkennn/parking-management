
@extends('layouts.parking')

@section('title', 'Create Staff Account | Parking Management System')

@section('content')

<div class="page-heading">
    <div>
        <h2>Create Staff Account</h2>
        <p>Register a new parking staff member in the system.</p>
    </div>

    <a href="{{ route('staff.index') }}" class="secondary-btn">
        ← Back to Staff List
    </a>
</div>

<div class="form-panel">

    <div class="form-header">
        <div class="form-avatar">👤</div>
        <div>
            <h3>New Parking Staff</h3>
            <p>Enter the staff member's information and login credentials.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="form-errors">
            <strong>Please correct the following errors:</strong>

            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('staff.store') }}" method="POST">
        @csrf

        <div class="form-body">

            <div class="form-section-title">
                <h4>Personal Information</h4>
                <p>Provide the basic information of the new staff member.</p>
            </div>

            <div class="form-group">
                <label for="name">Full Name <span>*</span></label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Enter full name"
                    required
                >
            </div>

            <div class="form-group">
                <label for="email">Email Address <span>*</span></label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Enter email address"
                    required
                >
            </div>

            <div class="form-section-title password-section">
                <h4>Login Credentials</h4>
                <p>Create a password that the staff member will use to access the system.</p>
            </div>

            <div class="form-group">
                <label for="password">Password <span>*</span></label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    minlength="8"
                    autocomplete="new-password"
                    placeholder="Enter password"
                    required
                >

                <small>Password must contain at least 8 characters.</small>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password <span>*</span></label>

                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    minlength="8"
                    autocomplete="new-password"
                    placeholder="Re-enter password"
                    required
                >
            </div>

            <div class="staff-notice">
                <strong>Staff Role</strong>
                <p>This account will automatically be assigned the Parking Staff role.</p>
            </div>

        </div>

        <div class="form-actions">
            <a href="{{ route('staff.index') }}" class="secondary-btn">
                Cancel
            </a>

            <button type="submit" class="primary-btn">
                Create Staff Account
            </button>
        </div>

    </form>
</div>

@endsection

@push('styles')
<style>
    .page-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
    }

    .page-heading h2 {
        margin: 0 0 6px;
        font-size: 24px;
        color: #263449;
    }

    .page-heading p {
        margin: 0;
        color: #8993a4;
        font-size: 14px;
    }

    .form-panel {
        background: #fff;
        border-radius: 12px;
        padding: 25px;
        border: 1px solid #edf0f5;
        box-shadow: 0 3px 15px rgba(0,0,0,.04);
        max-width: 850px;
    }

    .form-header {
        display: flex;
        align-items: center;
        gap: 15px;
        padding-bottom: 22px;
        margin-bottom: 22px;
        border-bottom: 1px solid #edf0f5;
    }

    .form-avatar {
        width: 55px;
        height: 55px;
        border-radius: 12px;
        background: #e8f1ff;
        color: #0866d9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 25px;
    }

    .form-header h3 {
        margin: 0 0 5px;
        color: #263449;
        font-size: 18px;
    }

    .form-header p {
        margin: 0;
        color: #8993a4;
        font-size: 13px;
    }

    .form-section-title {
        margin-bottom: 18px;
    }

    .form-section-title h4 {
        margin: 0 0 5px;
        color: #263449;
        font-size: 16px;
    }

    .form-section-title p {
        margin: 0;
        color: #8993a4;
        font-size: 13px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #536176;
        font-size: 13px;
        font-weight: 600;
    }

    .form-group label span {
        color: #dc3545;
    }

    .form-group input {
        width: 100%;
        box-sizing: border-box;
        padding: 12px 14px;
        border: 1px solid #dfe4ec;
        border-radius: 7px;
        font-size: 13px;
        outline: none;
        color: #263449;
        background: #fff;
    }

    .form-group input:focus {
        border-color: #0866d9;
        box-shadow: 0 0 0 3px rgba(8,102,217,.09);
    }

    .form-group small {
        display: block;
        margin-top: 6px;
        color: #8993a4;
        font-size: 11px;
    }

    .password-section {
        border-top: 1px solid #edf0f5;
        padding-top: 22px;
        margin-top: 8px;
    }

    .staff-notice {
        padding: 15px;
        background: #e8f1ff;
        border-radius: 8px;
        border-left: 4px solid #0866d9;
        margin-top: 10px;
    }

    .staff-notice strong {
        color: #0866d9;
        font-size: 13px;
    }

    .staff-notice p {
        margin: 5px 0 0;
        color: #536176;
        font-size: 12px;
    }

    .form-errors {
        padding: 14px 16px;
        background: #fff0f0;
        color: #c62828;
        border-radius: 7px;
        margin-bottom: 20px;
        font-size: 13px;
    }

    .form-errors p {
        margin: 6px 0 0;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        border-top: 1px solid #edf0f5;
        padding-top: 20px;
        margin-top: 25px;
    }

    .form-actions button {
        cursor: pointer;
        font-family: inherit;
    }

    @media (max-width: 600px) {
        .page-heading {
            align-items: flex-start;
            flex-direction: column;
        }

        .form-panel {
            padding: 17px;
        }
    }
</style>
@endpush
