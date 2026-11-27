<?php

namespace App\Contracts;

interface ParkingFeeStrategy
{
    public function calculate(int $minutes): int;
}
