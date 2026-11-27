<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ParkingSlot;

class ParkingSlotSeeder extends Seeder
{
    public function run(): void
    {
        $slots = [];

        for ($i = 1; $i <= 20; $i++) {
            $slots[] = [
                'slot_number' => 'A' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'slot_type' => 'Car',
                'status' => 'Available',
            ];
        }

        foreach ($slots as $slot) {
            ParkingSlot::firstOrCreate(
                ['slot_number' => $slot['slot_number']],
                $slot
            );
        }
    }
}