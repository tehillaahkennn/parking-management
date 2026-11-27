
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Payment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 25px;
        }
        .container {
            max-width: 750px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
        }
        h1 { color: #1f2937; }
        label {
            display: block;
            margin-top: 18px;
            margin-bottom: 7px;
            font-weight: bold;
        }
        select, button {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        button {
            margin-top: 22px;
            background: #2563eb;
            color: white;
            border: none;
            cursor: pointer;
        }
        .error {
            color: #b91c1c;
            margin-top: 5px;
        }
        .alert {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 5px;
        }
        a { color: #2563eb; }
    </style>
</head>
<body>
<div class="container">
    <a href="{{ route('payments.index') }}">← Back to Payments</a>

    <h1>Record Payment</h1>
    <p>Select a completed parking session and its payment method.</p>

    @if ($errors->any())
        <div class="alert">
            Please correct the following:
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($parkingRecords->isEmpty())
        <p>There are no unpaid completed parking records available.</p>
    @else
        <form method="POST" action="{{ route('payments.store') }}">
            @csrf

            <label for="parking_record_id">Completed Parking Record</label>
            <select id="parking_record_id" name="parking_record_id" required>
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
                <div class="error">{{ $message }}</div>
            @enderror

            <label for="amount_display">Payment Amount</label>
            <input
                id="amount_display"
                type="text"
                value="Select a parking record"
                readonly
                style="width: 100%; padding: 12px; box-sizing: border-box;"
            >
            <p>The amount is taken from the parking fee and cannot be edited here.</p>

            <label for="payment_method">Payment Method</label>
            <select id="payment_method" name="payment_method" required>
                <option value="">-- Select payment method --</option>
                <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                <option value="GCash" {{ old('payment_method') == 'GCash' ? 'selected' : '' }}>GCash</option>
                <option value="Card" {{ old('payment_method') == 'Card' ? 'selected' : '' }}>Card</option>
            </select>

            @error('payment_method')
                <div class="error">{{ $message }}</div>
            @enderror

            <button type="submit">Confirm Payment</button>
        </form>
    @endif
</div>

<script>
    const recordSelect = document.getElementById('parking_record_id');
    const amountDisplay = document.getElementById('amount_display');

    function updateAmount() {
        const option = recordSelect.options[recordSelect.selectedIndex];
        const fee = option ? option.dataset.fee : '';

        amountDisplay.value = fee !== ''
            ? '₱' + Number(fee).toFixed(2)
            : 'Select a parking record';
    }

    recordSelect?.addEventListener('change', updateAmount);
    updateAmount();
</script>
</body>
</html>
