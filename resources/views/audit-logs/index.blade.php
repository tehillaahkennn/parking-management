
@extends('layouts.parking')

@section('title', 'Audit Trail | Parking Management System')

@section('content')

<div class="audit-page">

    <div class="page-heading">
        <div>
            <h2>Audit Trail</h2>
            <p>Monitor and review activities performed within the parking management system.</p>
        </div>

        <button type="button" onclick="window.print()" class="primary-btn no-print">
            🖨 Print Audit Logs
        </button>
    </div>

    <div class="audit-summary">
        <div class="summary-card">
            <div class="summary-icon">📋</div>
            <div>
                <p>Total Activity Records</p>
                <h2>{{ $auditLogs->total() }}</h2>
            </div>
        </div>
    </div>

    <div class="records-panel">

        <div class="records-header">
            <div>
                <h3>System Activity History</h3>
                <p>View and search recorded system activities.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert-success no-print">
                {{ session('success') }}
            </div>
        @endif

        <form method="GET"
              action="{{ route('audit-logs.index') }}"
              class="search-form no-print">

            <div class="search-input">
                <label for="search">Search Activity</label>

                <input
                    type="text"
                    id="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search action, user, description, IP..."
                >
            </div>

            <button type="submit" class="primary-btn">
                Search
            </button>

            <a href="{{ route('audit-logs.index') }}" class="secondary-btn">
                Reset
            </a>

        </form>

        <div class="audit-info">
            <span>
                Showing {{ $auditLogs->firstItem() ?? 0 }}
                to {{ $auditLogs->lastItem() ?? 0 }}
                of {{ $auditLogs->total() }} records
            </span>
        </div>

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
                    @forelse($auditLogs as $log)
                        <tr>
                            <td>#{{ $log->id }}</td>

                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">
                                        {{ strtoupper(substr($log->user?->name ?? 'S', 0, 1)) }}
                                    </div>

                                    <span>
                                        {{ $log->user?->name ?? 'System / Unknown' }}
                                    </span>
                                </div>
                            </td>

                            <td>
                                @php
                                    $actionClass = match(strtoupper($log->action)) {
                                        'CREATE' => 'action-create',
                                        'UPDATE' => 'action-update',
                                        'DELETE' => 'action-delete',
                                        'LOGIN' => 'action-login',
                                        'LOGOUT' => 'action-logout',
                                        default => 'action-default',
                                    };
                                @endphp

                                <span class="action-badge {{ $actionClass }}">
                                    {{ $log->action }}
                                </span>
                            </td>

                            <td class="description-cell">
                                {{ $log->description }}
                            </td>

                            <td>
                                {{ $log->ip_address ?? 'N/A' }}
                            </td>

                            <td class="date-cell">
                                {{ $log->created_at?->format('M d, Y') ?? 'N/A' }}
                                <small>
                                    {{ $log->created_at?->format('h:i A') ?? '' }}
                                </small>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty">
                                    <div style="font-size:35px;">📋</div>
                                    <h3>No Audit Logs Found</h3>
                                    <p>No recorded activities match your search.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrapper no-print">
            {{ $auditLogs->links() }}
        </div>

    </div>

</div>

@endsection

@push('styles')
<style>
    .audit-page {
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
        color: #263449;
        font-size: 24px;
    }

    .page-heading p {
        margin: 0;
        color: #8993a4;
        font-size: 14px;
    }

    .audit-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 18px;
        margin-bottom: 25px;
    }

    .summary-card {
        display: flex;
        align-items: center;
        gap: 17px;
        padding: 22px;
        background: #fff;
        border: 1px solid #edf0f5;
        border-radius: 12px;
        box-shadow: 0 3px 15px rgba(0,0,0,.04);
    }

    .summary-icon {
        width: 52px;
        height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #e8f1ff;
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
        padding: 22px;
        background: #fff;
        border: 1px solid #edf0f5;
        border-radius: 12px;
        box-shadow: 0 3px 15px rgba(0,0,0,.04);
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

    .search-form {
        display: flex;
        align-items: flex-end;
        gap: 10px;
        flex-wrap: wrap;
        padding: 16px;
        margin-bottom: 18px;
        background: #f8f9fc;
        border-radius: 9px;
    }

    .search-input {
        flex: 1;
        min-width: 220px;
    }

    .search-input label {
        display: block;
        margin-bottom: 7px;
        color: #536176;
        font-size: 12px;
        font-weight: 600;
    }

    .search-input input {
        width: 100%;
        box-sizing: border-box;
        padding: 11px 13px;
        border: 1px solid #dfe4ec;
        border-radius: 7px;
        outline: none;
        font-size: 13px;
    }

    .search-input input:focus {
        border-color: #0866d9;
        box-shadow: 0 0 0 3px rgba(8,102,217,.09);
    }

    .search-form button,
    .search-form a {
        cursor: pointer;
        font-family: inherit;
        text-decoration: none;
    }

    .audit-info {
        margin-bottom: 15px;
        color: #8993a4;
        font-size: 12px;
    }

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .table-wrapper table {
        width: 100%;
        min-width: 950px;
        border-collapse: collapse;
    }

    .table-wrapper th {
        padding: 14px;
        background: #f5f7fb;
        color: #657187;
        text-align: left;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .4px;
        white-space: nowrap;
    }

    .table-wrapper td {
        padding: 14px;
        border-bottom: 1px solid #edf0f5;
        color: #536176;
        font-size: 12px;
        vertical-align: middle;
    }

    .table-wrapper tbody tr:hover {
        background: #f9fbff;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 9px;
        white-space: nowrap;
        color: #263449;
        font-weight: 600;
    }

    .user-avatar {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #e8f1ff;
        color: #0866d9;
        font-weight: bold;
    }

    .action-badge {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .action-create {
        background: #e8f8ef;
        color: #16834a;
    }

    .action-update {
        background: #e8f1ff;
        color: #0866d9;
    }

    .action-delete {
        background: #fff0f0;
        color: #dc3545;
    }

    .action-login {
        background: #f0eaff;
        color: #7040bd;
    }

    .action-logout {
        background: #fff4df;
        color: #b7791f;
    }

    .action-default {
        background: #f0f1f4;
        color: #596579;
    }

    .description-cell {
        min-width: 220px;
        max-width: 350px;
        overflow-wrap: anywhere;
        line-height: 1.6;
    }

    .date-cell {
        white-space: nowrap;
    }

    .date-cell small {
        display: block;
        margin-top: 4px;
        color: #8993a4;
    }

    .empty {
        padding: 45px 15px;
        text-align: center;
        color: #8993a4;
    }

    .empty h3 {
        margin: 12px 0 5px;
        color: #263449;
    }

    .empty p {
        margin: 0;
        font-size: 13px;
    }

    .alert-success {
        padding: 13px 16px;
        margin-bottom: 18px;
        border-radius: 7px;
        background: #e8f8ef;
        color: #16834a;
        font-size: 13px;
    }

    @media (max-width: 600px) {
        .page-heading {
            align-items: flex-start;
            flex-direction: column;
        }

        .records-panel {
            padding: 15px;
        }

        .search-form {
            align-items: stretch;
            flex-direction: column;
        }
    }

    @media print {
        @page {
            size: landscape;
            margin: 12mm;
        }

        body {
            background: #fff !important;
            color: #222 !important;
        }

        .sidebar,
        .topbar,
        .footer,
        .no-print,
        .page-heading button {
            display: none !important;
        }

        .main {
            margin: 0 !important;
            width: 100% !important;
        }

        .content {
            padding: 0 !important;
        }

        .records-panel,
        .summary-card {
            box-shadow: none !important;
            border: none !important;
        }

        .table-wrapper {
            overflow: visible !important;
        }

        .table-wrapper table {
            min-width: 0 !important;
            font-size: 9px;
        }

        .table-wrapper th,
        .table-wrapper td {
            padding: 7px;
            overflow-wrap: anywhere;
        }

        tr {
            page-break-inside: avoid;
        }
    }
    /* Smaller pagination arrows */
    .pagination-wrapper svg {
    width: 16px !important;
    height: 16px !important;
}
</style>
@endpush
