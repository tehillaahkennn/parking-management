
@extends('layouts.parking')

@section('title', 'Record Payment | Parking Management System')

@push('styles')
<style>
    .payment-form-panel {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, .04);
        max-width: 800px;
        overflow: hidden;
    }

    .payment-form-header {
        padding: 22px 25px;
        border-bottom: 1px solid #edf0f5;
    }

    .payment-form-header h3 {
        margin: 0 0 6px;
        font-size: 17px;
        color: #172b4d;
    }

    .payment-form-header p {
        margin: 0;
        color: #8792a8;
        font-size: 13px;
    }

    .payment-form-body {
        padding: 25px;
    }

    .payment-form-group {
        margin-bottom: 21px;
    }

    .payment-form-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 9px;
        color: #344563;
    }

    .payment-form-group select,
    .payment-form-group input {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #dfe3eb;
        border-radius: 6px;
        background: #fff;
        font-size: 13px;
        outline: none;
        color: #344563;
    }

    .payment-form-group select:focus,
    .payment-form-group input:focus {
        border-color: #0866d9;
        box-shadow: 0 0 0 2px rgba(8, 102, 217, .08);
    }

    .payment-form-group input[readonly] {
        background: #f4f8ff;
        color: #13864e;
        font-weight: 700;
    }

    .payment-form-help {
        font-size: 12px;
        color: #8792a8;
        margin-top: 7px;
    }

    .payment-form-error {
        color: #dc3545;
        font-size: 12px;
        margin-top: 7px;
    }

    .payment-error-alert {
        background: #fff0f0;
        color: #a52834;
        padding: 13px 16px;
        border-radius: 6px;
        margin-bottom: 20px;
        font-size: 13px;
    }

    .payment-form-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 26px;
    }

    .payment-form-actions button {
        font-family: inherit;
    }

    .no-unpaid-records {
        background: #f4f6fa;
        color: #52617c;
        padding: 20px;
        border-radius: 7px;
        font-size: 13px;
    }

    .no-unpaid-records p {
        margin: 7px 0 0;
    }

    @media (max-width: 600px) {
        .payment-form-body {
            padding: 18px;
        }

        .payment-form-header {
            padding: 18px;
        }
    }
</style>
@endpush

@section('content')

<div class="welcome">
    <div>
        <h2>Record Payment</h2>
        <p>Record payment for a completed parking session.</p>
    </div>

    <a href="{{ route('payments.index') }}" class="secondary-btn">
        ← Back to Payments
    </a>
</div>

<div class="payment-form-panel">

    <div class="payment-form-header">
        <h3>Payment Information</h3>
        <p>Select a completed parking record and payment method.</p>
    </div>

    <div class="payment-form-body">

        @if ($errors->any())
            <div class="payment-error-alert">
                <strong>Please correct the following:</strong>

                <ul style="margin:8px 0 0; padding-left:20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($parkingRecords->isEmpty())

            <div class="no-unpaid-records">
                <strong>No unpaid parking records available.</strong>
                <p>
                    There are currently no completed parking sessions waiting for payment.
                </p>
            </div>

            <div class="payment-form-actions">
                <a href="{{ route('payments.index') }}" class="secondary-btn">
                    ← Back to Payments
                </a>
            </div>

        @else

            <form method="POST" action="{{ route('payments.store') }}">
                @csrf

                <div class="payment-form-group">
                    <label for="parking_record_id">
                        Completed Parking Record
                    </label>

                    <select id="parking_record_id"
                            name="parking_record_id"
                            required>

                        <option value="">-- Select parking record --</option>

                        @foreach ($parkingRecords as $record)
                            <option
                                value="{{ $record->id }}"
                                data-fee="{{ $record->parking_fee }}"
                                {{ old('parking_record_id') == $record->id ? 'selected' : '' }}
                            >
                                #{{ $record->id }}
                                — {{ $record->vehicle->plate_number }}
                                — {{ $record->vehicle->owner_name }}
                                — Slot {{ $record->parkingSlot->slot_number }}
                                — ₱{{ number_format((float) $record->parking_fee, 2) }}
                            </option>
                        @endforeach

                    </select>

                    @error('parking_record_id')
                        <div class="payment-form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="payment-form-group">
                    <label for="amount_display">Payment Amount</label>

                    <input
                        id="amount_display"
                        type="text"
                        value="Select a parking record"
                        readonly
                    >

                    <div class="payment-form-help">
                        The amount is automatically taken from the parking fee and cannot be edited here.
                    </div>
                </div>

                <div class="payment-form-group">
                    <label for="payment_method">Payment Method</label>

                    <select id="payment_method"
                            name="payment_method"
                            required>

                        <option value="">-- Select payment method --</option>

                        <option value="Cash"
                            {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>
                            Cash
                        </option>

                        <option value="GCash"
                            {{ old('payment_method') == 'GCash' ? 'selected' : '' }}>
                            GCash
                        </option>

                        <option value="Card"
                            {{ old('payment_method') == 'Card' ? 'selected' : '' }}>
                            Card
                        </option>

                    </select>

                    @error('payment_method')
                        <div class="payment-form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="payment-form-actions">
                    <button type="submit" class="primary-btn">
                        Confirm Payment
                    </button>

                    <a href="{{ route('payments.index') }}" class="secondary-btn">
                        Cancel
                    </a>
                </div>

            </form>

        @endif

    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const recordSelect = document.getElementById('parking_record_id');
        const amountDisplay = document.getElementById('amount_display');

        function updateAmount() {
            if (!recordSelect || !amountDisplay) {
                return;
            }

            const option = recordSelect.options[recordSelect.selectedIndex];
            const fee = option ? option.dataset.fee : '';

            amountDisplay.value = fee !== undefined && fee !== ''
                ? '₱' + Number(fee).toFixed(2)
                : 'Select a parking record';
        }

        if (recordSelect) {
            recordSelect.addEventListener('change', updateAmount);
            updateAmount();
        }
    });
</script>
@endpush
