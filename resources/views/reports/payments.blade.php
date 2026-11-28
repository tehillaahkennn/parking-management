
@extends('layouts.parking')

@section('title', 'Payment Report | Parking Management System')

@section('content')

<style>
    .report-heading {
        margin-bottom: 22px;
    }

    .report-heading h2 {
        margin: 0 0 7px;
        color: #172b4d;
        font-size: 24px;
    }

    .report-heading p {
        color: #7b8495;
        margin: 0;
        font-size: 14px;
    }

    .summary-card,
    .filter-panel,
    .records-panel {
        background: #fff;
        border-radius: 12px;
        padding: 22px;
        margin-bottom: 22px;
        box-shadow: 0 3px 15px rgba(0,0,0,.04);
        border: 1px solid #edf0f5;
    }

    .summary-card {
        max-width: 350px;
        border-left: 4px solid #0866d9;
    }

    .summary-label {
        color: #7b8495;
        font-size: 13px;
        margin-bottom: 10px;
    }

    .summary-amount {
        color: #172b4d;
        font-size: 28px;
        font-weight: 700;
    }

    .filter-panel h3,
    .records-header h3 {
        margin: 0 0 18px;
        color: #172b4d;
        font-size: 17px;
    }

    .filter-form {
        display: flex;
        align-items: end;
        flex-wrap: wrap;
        gap: 15px;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 7px;
        min-width: 190px;
    }

    .filter-group label {
        font-size: 13px;
        font-weight: 600;
        color: #526078;
    }

    .filter-group input {
        padding: 10px 12px;
        border: 1px solid #dce2ec;
        border-radius: 7px;
        font-size: 13px;
        outline: none;
    }

    .filter-group input:focus {
        border-color: #0866d9;
    }

    .filter-btn,
    .reset-btn,
    .back-btn {
        display: inline-block;
        padding: 11px 17px;
        border-radius: 7px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        border: none;
    }

    .filter-btn {
        background: #0866d9;
        color: white;
    }

    .filter-btn:hover {
        background: #0754b3;
        color: white;
    }

    .reset-btn {
        background: #edf0f5;
        color: #526078;
    }

    .back-btn {
        background: #e9efff;
        color: #0866d9;
    }

    .records-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 18px;
    }

    .records-header h3 {
        margin: 0;
    }

    .record-count {
        font-size: 13px;
        color: #7b8495;
    }

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .report-table {
        width: 100%;
        min-width: 950px;
        border-collapse: collapse;
    }

    .report-table th {
        background: #f5f7fb;
        color: #526078;
        padding: 13px 12px;
        text-align: left;
        font-size: 12px;
        white-space: nowrap;
    }

    .report-table td {
        padding: 14px 12px;
        border-bottom: 1px solid #edf0f5;
        color: #526078;
        font-size: 13px;
        white-space: nowrap;
    }

    .report-table tbody tr:hover {
        background: #f9fbff;
    }

    .plate-number {
        font-weight: 700;
        color: #172b4d;
    }

    .payment-method {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        background: #e9efff;
        color: #0866d9;
        font-size: 11px;
        font-weight: 600;
    }

    .amount {
        color: #16834a;
        font-weight: 700;
    }

    .empty-state {
        text-align: center;
        padding: 40px 15px;
        color: #8993a5;
        font-size: 14px;
    }

    .pagination-wrapper {
        margin-top: 20px;
    }

    .validation-error {
        color: #dc3545;
        font-size: 12px;
        margin-top: 6px;
    }

    @media(max-width: 600px) {
        .filter-group {
            width: 100%;
        }

        .filter-form > a,
        .filter-form > button {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="report-heading">
    <h2>Payment Report</h2>
    <p>Monitor payment transactions and revenue collected from parking activities.</p>
</div>

<div class="summary-card">
    <div class="summary-label">Total Revenue</div>
    <div class="summary-amount">
        ₱{{ number_format((float) $totalRevenue, 2) }}
    </div>
</div>

<div class="filter-panel">

    <h3>Filter Payment Transactions</h3>

    <form action="{{ route('reports.payments') }}" method="GET" class="filter-form">

        <div class="filter-group">
            <label for="date_from">Date From</label>
            <input
                type="date"
                name="date_from"
                id="date_from"
                value="{{ $validated['date_from'] ?? '' }}"
            >

            @error('date_from')
                <span class="validation-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="filter-group">
            <label for="date_to">Date To</label>
            <input
                type="date"
                name="date_to"
                id="date_to"
                value="{{ $validated['date_to'] ?? '' }}"
            >

            @error('date_to')
                <span class="validation-error">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="filter-btn">
            Apply Filter
        </button>

        <a href="{{ route('reports.payments') }}" class="reset-btn">
            Reset
        </a>

        <a href="{{ route('reports.index') }}" class="back-btn">
            Back to Reports
        </a>

    </form>

</div>

<div class="records-panel">

    <div class="records-header">
        <h3>Payment Transactions</h3>

        <span class="record-count">
            Total Transactions: {{ $payments->total() }}
        </span>
    </div>

    <div class="table-wrapper">

        <table class="report-table">

            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Plate Number</th>
                    <th>Vehicle Owner</th>
                    <th>Parking Slot</th>
                    <th>Payment Method</th>
                    <th>Payment Date</th>
                    <th>Amount Paid</th>
                </tr>
            </thead>

            <tbody>

                @forelse($payments as $payment)

                    <tr>
                        <td>
                            #{{ $payment->id }}
                        </td>

                        <td class="plate-number">
                            {{ $payment->parkingRecord?->vehicle?->plate_number ?? 'N/A' }}
                        </td>

                        <td>
                            {{ $payment->parkingRecord?->vehicle?->owner_name ?? 'N/A' }}
                        </td>

                        <td>
                            {{ $payment->parkingRecord?->parkingSlot?->slot_number ?? 'N/A' }}
                        </td>

                        <td>
                            <span class="payment-method">
                                {{ $payment->payment_method }}
                            </span>
                        </td>

                        <td>
                            {{ $payment->paid_at?->format('M d, Y h:i A') ?? 'N/A' }}
                        </td>

                        <td class="amount">
                            ₱{{ number_format((float) $payment->amount, 2) }}
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="7" class="empty-state">
                            No payment transactions found.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="pagination-wrapper">
        {{ $payments->links() }}
    </div>

</div>

@endsection
