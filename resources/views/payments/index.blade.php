
@extends('layouts.parking')

@section('title', 'Payment Management | Parking Management System')

@push('styles')
<style>
    .payment-stats {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .payment-amount {
        color: #13864e;
        font-weight: bold;
    }

    .payment-method {
        display: inline-block;
        background: #eeeaff;
        color: #6259ca;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: bold;
    }

    .payment-table {
        min-width: 850px;
    }

    @media (max-width: 420px) {
        .payment-stats {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')

<!-- PAGE HEADING -->
<div class="welcome">
    <div>
        <h2>Payment Management</h2>
        <p>View and manage completed parking payments.</p>
    </div>
</div>

<!-- SUMMARY CARDS -->
<div class="stats payment-stats">

    <div class="stat-card blue">
        <div class="stat-title">Total Payment Records</div>

        <div class="stat-number">
            {{ $payments->total() }}
        </div>

        <div class="stat-description">
            All recorded payments
        </div>

        <div class="stat-decoration">₱</div>
    </div>

    <div class="stat-card green">
        <div class="stat-title">Total Amount on This Page</div>

        <div class="stat-number">
            ₱{{ number_format((float) $payments->sum('amount'), 2) }}
        </div>

        <div class="stat-description">
            Total amount from displayed records
        </div>

        <div class="stat-decoration">✓</div>
    </div>

</div>

<!-- PAYMENT RECORDS -->
<div class="records-panel">

    <div class="records-header">
        <div>
            <h3>Payment Records</h3>
            <p class="panel-subtitle" style="margin: 6px 0 0;">
                List of completed parking payments.
            </p>
        </div>

        <a href="{{ route('payments.create') }}" class="primary-btn">
            + Record Payment
        </a>
    </div>

    <!-- SEARCH FORM -->
    <form method="GET"
          action="{{ route('payments.index') }}"
          class="table-search">

        <input
            type="text"
            name="search"
            placeholder="Search plate number or owner..."
            value="{{ $search }}"
        >

        <button type="submit" class="primary-btn">
            Search
        </button>

        <a href="{{ route('payments.index') }}"
           class="secondary-btn">
            Reset
        </a>

    </form>

    <!-- PAYMENT TABLE -->
    <div class="table-wrapper">

        <table class="payment-table">

            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Plate Number</th>
                    <th>Owner</th>
                    <th>Parking Slot</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Paid At</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($payments as $payment)

                    <tr>
                        <td>
                            #{{ $payment->id }}
                        </td>

                        <td>
                            {{ $payment->parkingRecord->vehicle->plate_number }}
                        </td>

                        <td>
                            {{ $payment->parkingRecord->vehicle->owner_name }}
                        </td>

                        <td>
                            {{ $payment->parkingRecord->parkingSlot->slot_number }}
                        </td>

                        <td class="payment-amount">
                            ₱{{ number_format((float) $payment->amount, 2) }}
                        </td>

                        <td>
                            <span class="payment-method">
                                {{ $payment->payment_method }}
                            </span>
                        </td>

                        <td>
                            {{ $payment->paid_at->format('M d, Y h:i A') }}
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="7" class="empty">
                            No payments recorded yet.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <!-- PAGINATION -->
    <div class="pagination-wrapper">
        {{ $payments->links() }}
    </div>

</div>

@endsection
