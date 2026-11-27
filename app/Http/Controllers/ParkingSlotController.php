<?php

namespace App\Http\Controllers;

use App\Models\ParkingSlot;

class ParkingSlotController extends Controller
{
    public function index()
    {
        $parkingSlots = ParkingSlot::orderBy('slot_number')->get();

        return view('parking-slots.index', compact('parkingSlots'));
    }
}
