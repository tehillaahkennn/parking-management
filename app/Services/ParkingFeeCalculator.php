<?php

namespace App\Services;

class ParkingFeeCalculator
{
    public function calculate(int $minutes): int
    {
        $hours = max(1, (int) ceil(max(0, $minutes) / 60));

        return $hours * 20;
    }
}
