<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\ParkingRecord;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $payments = Payment::with([
            'parkingRecord.vehicle',
            'parkingRecord.parkingSlot',
        ])
            ->when($search, function ($query, $search) {
                $query->whereHas('parkingRecord.vehicle', function ($q) use ($search) {
                    $q->where('plate_number', 'like', "%{$search}%")
                        ->orWhere('owner_name', 'like', "%{$search}%");
                });
            })
            ->latest('paid_at')
            ->paginate(10)
            ->withQueryString();

        return view('payments.index', compact('payments', 'search'));
    }

    public function create()
    {
        $parkingRecords = ParkingRecord::with([
            'vehicle',
            'parkingSlot',
            'payment',
        ])
            ->where('status', 'Completed')
            ->whereDoesntHave('payment')
            ->latest()
            ->get();

        return view('payments.create', compact('parkingRecords'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'parking_record_id' => 'required|integer|exists:parking_records,id',
            'payment_method' => 'required|in:Cash,GCash,Card',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $parkingRecord = ParkingRecord::whereKey(
                $validated['parking_record_id']
            )
                ->lockForUpdate()
                ->firstOrFail();

            if ($parkingRecord->status !== 'Completed') {
                throw ValidationException::withMessages([
                    'parking_record_id' =>
                        'Only completed parking records can be paid.',
                ]);
            }

            if ($parkingRecord->payment()->exists()) {
                throw ValidationException::withMessages([
                    'parking_record_id' =>
                        'This parking record has already been paid.',
                ]);
            }

            $payment = Payment::create([
                'parking_record_id' => $parkingRecord->id,
                'amount' => $parkingRecord->parking_fee,
                'payment_method' => $validated['payment_method'],
                'paid_at' => now(),
            ]);

            $parkingRecord->loadMissing(['vehicle', 'parkingSlot']);

            AuditLogger::log(
                $request,
                'PAYMENT',
                'Payment recorded for vehicle '
                    . $parkingRecord->vehicle->plate_number
                    . '; amount: '
                    . number_format((float) $payment->amount, 2)
                    . '; method: '
                    . $payment->payment_method
            );
        });

        return redirect()
            ->route('payments.index')
            ->with('success', 'Payment recorded successfully.');
    }
}
