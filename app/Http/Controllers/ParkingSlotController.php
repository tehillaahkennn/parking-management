<?php

namespace App\Http\Controllers;

use App\Models\ParkingSlot;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ParkingSlotController extends Controller
{
    public function index()
    {
        $parkingSlots = ParkingSlot::orderBy('slot_number')->get();

        return view('parking-slots.index', compact('parkingSlots'));
    }

    public function create()
    {
        return view('parking-slots.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'slot_number' => [
                'required',
                'string',
                'max:20',
                'unique:parking_slots,slot_number',
            ],
            'slot_type' => [
                'required',
                Rule::in(['Motorcycle', 'Car', 'Van']),
            ],
        ]);

        $parkingSlot = ParkingSlot::create([
            'slot_number' => $validated['slot_number'],
            'slot_type' => $validated['slot_type'],
            'status' => 'Available',
        ]);

        AuditLogger::log(
            $request,
            'CREATE',
            "Created parking slot {$parkingSlot->slot_number} ({$parkingSlot->slot_type})."
        );

        return redirect()
            ->route('parking-slots.index')
            ->with('success', 'Parking slot created successfully.');
    }

    public function edit(ParkingSlot $parkingSlot)
    {
        if ($parkingSlot->status === 'Occupied') {
            return redirect()
                ->route('parking-slots.index')
                ->withErrors([
                    'slot' => 'Occupied slots cannot be edited.',
                ]);
        }

        return view('parking-slots.edit', compact('parkingSlot'));
    }

    public function update(Request $request, ParkingSlot $parkingSlot)
    {
        if ($parkingSlot->status === 'Occupied') {
            return redirect()
                ->route('parking-slots.index')
                ->withErrors([
                    'slot' => 'Occupied slots cannot be updated.',
                ]);
        }

        $validated = $request->validate([
            'slot_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('parking_slots', 'slot_number')
                    ->ignore($parkingSlot->id),
            ],
            'slot_type' => [
                'required',
                Rule::in(['Motorcycle', 'Car', 'Van']),
            ],
        ]);

        if (
            $parkingSlot->parkingRecords()->exists() &&
            $validated['slot_type'] !== $parkingSlot->slot_type
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'slot_type' => 'The type of a slot with parking history cannot be changed.',
                ]);
        }

        $oldSlotNumber = $parkingSlot->slot_number;

        $parkingSlot->update([
            'slot_number' => $validated['slot_number'],
            'slot_type' => $validated['slot_type'],
        ]);

        AuditLogger::log(
            $request,
            'UPDATE',
            "Updated parking slot {$oldSlotNumber} to {$parkingSlot->slot_number} ({$parkingSlot->slot_type})."
        );

        return redirect()
            ->route('parking-slots.index')
            ->with('success', 'Parking slot updated successfully.');
    }

    public function destroy(Request $request, ParkingSlot $parkingSlot)
    {
        if ($parkingSlot->status === 'Occupied') {
            return redirect()
                ->route('parking-slots.index')
                ->withErrors([
                    'slot' => 'Occupied slots cannot be deleted.',
                ]);
        }

        if ($parkingSlot->parkingRecords()->exists()) {
            return redirect()
                ->route('parking-slots.index')
                ->withErrors([
                    'slot' => 'This slot has parking history and cannot be deleted.',
                ]);
        }

        $slotNumber = $parkingSlot->slot_number;

        $parkingSlot->delete();

        AuditLogger::log(
            $request,
            'DELETE',
            "Deleted parking slot {$slotNumber}."
        );

        return redirect()
            ->route('parking-slots.index')
            ->with('success', 'Parking slot deleted successfully.');
    }
}
