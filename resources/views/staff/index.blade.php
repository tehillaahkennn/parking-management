
@extends('layouts.parking')

@section('title', 'Staff Management | Parking Management System')

@section('content')

<div class="staff-page">

    <div class="page-heading">
        <div>
            <h2>Staff Management</h2>
            <p>Manage your parking staff accounts and access.</p>
        </div>

        <a href="{{ route('staff.create') }}" class="primary-btn">
            + Add New Staff
        </a>
    </div>

    <div class="staff-summary">
        <div class="summary-card">
            <div class="summary-icon">👥</div>
            <div>
                <p>Total Parking Staff</p>
                <h2>{{ $staff->total() }}</h2>
            </div>
        </div>
    </div>

    <div class="records-panel">

        <div class="records-header">
            <div>
                <h3>Staff Accounts</h3>
                <p>List of registered parking staff members</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert-error">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert-error">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Staff Name</th>
                        <th>Email Address</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($staff as $member)
                        <tr>
                            <td>#{{ $member->id }}</td>

                            <td>
                                <div class="staff-name">
                                    <div class="staff-avatar">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </div>
                                    <strong>{{ $member->name }}</strong>
                                </div>
                            </td>

                            <td>{{ $member->email }}</td>

                            <td>
                                <span class="staff-role">
                                    {{ $member->role }}
                                </span>
                            </td>

                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('staff.edit', $member) }}"
                                       class="edit-btn">
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('staff.destroy', $member) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this staff account?');"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="delete-btn">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty">
                                    <div style="font-size:35px;">👥</div>
                                    <h3>No Staff Accounts Found</h3>
                                    <p>Start by creating your first parking staff account.</p>

                                    <a href="{{ route('staff.create') }}" class="primary-btn">
                                        Add Staff Account
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrapper">
            {{ $staff->links() }}
        </div>

    </div>

</div>

@endsection

@push('styles')
<style>
    .staff-page {
        width: 100%;
    }

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

    .staff-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 18px;
        margin-bottom: 25px;
    }

    .summary-card {
        display: flex;
        align-items: center;
        gap: 17px;
        background: #fff;
        padding: 22px;
        border-radius: 12px;
        box-shadow: 0 3px 15px rgba(0,0,0,.04);
        border: 1px solid #edf0f5;
    }

    .summary-icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        background: #e8f1ff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 25px;
    }

    .summary-card p {
        margin: 0 0 5px;
        color: #8993a4;
        font-size: 13px;
    }

    .summary-card h2 {
        margin: 0;
        color: #263449;
        font-size: 25px;
    }

    .records-panel {
        background: #fff;
        border-radius: 12px;
        padding: 22px;
        box-shadow: 0 3px 15px rgba(0,0,0,.04);
        border: 1px solid #edf0f5;
    }

    .records-header {
        margin-bottom: 20px;
    }

    .records-header h3 {
        margin: 0 0 5px;
        color: #263449;
        font-size: 18px;
    }

    .records-header p {
        margin: 0;
        color: #8993a4;
        font-size: 13px;
    }

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .table-wrapper table {
        width: 100%;
        min-width: 650px;
        border-collapse: collapse;
    }

    .table-wrapper th {
        background: #f5f7fb;
        color: #657187;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .4px;
        padding: 14px;
        text-align: left;
        white-space: nowrap;
    }

    .table-wrapper td {
        padding: 15px 14px;
        border-bottom: 1px solid #edf0f5;
        color: #536176;
        font-size: 13px;
    }

    .table-wrapper tbody tr:hover {
        background: #f9fbff;
    }

    .staff-name {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #263449;
    }

    .staff-avatar {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: #e8f1ff;
        color: #0866d9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    .staff-role {
        display: inline-block;
        padding: 6px 11px;
        border-radius: 20px;
        background: #e8f1ff;
        color: #0866d9;
        font-size: 11px;
        font-weight: 600;
    }

    .action-buttons {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .action-buttons form {
        margin: 0;
    }

    .edit-btn,
    .delete-btn {
        border: none;
        border-radius: 6px;
        padding: 7px 12px;
        font-size: 12px;
        cursor: pointer;
        text-decoration: none;
        font-weight: 600;
    }

    .edit-btn {
        background: #e8f1ff;
        color: #0866d9;
    }

    .edit-btn:hover {
        background: #d5e6ff;
    }

    .delete-btn {
        background: #fff0f0;
        color: #dc3545;
    }

    .delete-btn:hover {
        background: #ffe0e0;
    }

    .empty {
        text-align: center;
        padding: 45px 15px;
        color: #8993a4;
    }

    .empty h3 {
        color: #263449;
        margin: 12px 0 5px;
    }

    .empty p {
        margin-bottom: 20px;
    }

    .alert-success,
    .alert-error {
        padding: 13px 16px;
        border-radius: 7px;
        margin-bottom: 18px;
        font-size: 13px;
    }

    .alert-success {
        background: #e8f8ef;
        color: #16834a;
    }

    .alert-error {
        background: #fff0f0;
        color: #c62828;
    }

    @media (max-width: 600px) {
        .page-heading {
            align-items: flex-start;
            flex-direction: column;
        }

        .records-panel {
            padding: 15px;
        }
    }
</style>
@endpush
