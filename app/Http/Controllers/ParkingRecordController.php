<?php

namespace App\Http\Controllers;

use App\Contracts\ParkingFeeStrategy;
use App\Models\ParkingRecord;
use App\Models\ParkingSlot;
use App\Models\Vehicle;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ParkingRecordController extends Controller
{
    public function __construct(
        private readonly ParkingFeeStrategy $feeCalculator
    ) {}

    public function index()
    {
        $parkingRecords = ParkingRecord::with([
            'vehicle',
            'parkingSlot',
        ])->latest()->get();

        return view('parking.index', compact('parkingRecords'));
    }

    public function create()
    {
        $parkingSlots = ParkingSlot::where('status', 'Available')
            ->orderBy('slot_number')
            ->get();

        return view('parking.create', compact('parkingSlots'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plate_number' => 'required|string|max:20|unique:vehicles,plate_number',
            'owner_name' => 'required|string|max:100',
            'vehicle_type' => 'required|in:Motorcycle,Car,Van',
            'parking_slot_id' => 'required|exists:parking_slots,id',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $slot = ParkingSlot::whereKey($validated['parking_slot_id'])
                ->lockForUpdate()
                ->firstOrFail();

            if ($slot->status !== 'Available') {
                throw ValidationException::withMessages([
                    'parking_slot_id' => 'This parking slot is no longer available.',
                ]);
            }

            $vehicle = Vehicle::create([
                'plate_number' => $validated['plate_number'],
                'owner_name' => $validated['owner_name'],
                'vehicle_type' => $validated['vehicle_type'],
            ]);

            ParkingRecord::create([
                'vehicle_id' => $vehicle->id,
                'parking_slot_id' => $slot->id,
                'entry_time' => now(),
                'parking_fee' => 0,
                'status' => 'Parked',
            ]);

            $slot->update([
                'status' => 'Occupied',
            ]);

            AuditLogger::log(
                $request,
                'CHECK_IN',
                'Vehicle checked in: ' . $vehicle->plate_number
                    . ' at slot ' . $slot->slot_number
            );
        });

        return redirect()
            ->route('parking.index')
            ->with('success', 'Vehicle successfully checked in.');
    }

    public function checkout(
        Request $request,
        ParkingRecord $parkingRecord
    ) {
        $fee = 0;

        DB::transaction(function () use ($parkingRecord, &$fee, $request) {
            $record = ParkingRecord::whereKey($parkingRecord->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($record->status !== 'Parked') {
                throw ValidationException::withMessages([
                    'checkout' => 'This vehicle has already checked out.',
                ]);
            }

            $exitTime = now();

            $minutes = (int) $record->entry_time->diffInMinutes($exitTime);

            $fee = $this->feeCalculator->calculate($minutes);

            $record->update([
                'exit_time' => $exitTime,
                'parking_fee' => $fee,
                'status' => 'Completed',
            ]);

            $slot = ParkingSlot::whereKey($record->parking_slot_id)
                ->lockForUpdate()
                ->firstOrFail();

            $slot->update([
                'status' => 'Available',
            ]);

            AuditLogger::log(
                $request,
                'CHECK_OUT',
                'Vehicle checked out: '
                    . $record->vehicle->plate_number
                    . ' from slot ' . $slot->slot_number
                    . '; fee: ' . number_format($fee, 2)
            );
        });

        return redirect()
            ->route('parking.index')
            ->with(
                'success',
                'Vehicle checked out successfully. Parking fee: ₱'
                    . number_format($fee, 2)
            );
    }

    public function show(ParkingRecord $parkingRecord)
    {
        $parkingRecord->load(['vehicle', 'parkingSlot']);

        return view('parking.show', compact('parkingRecord'));
    }
}
