<?php

namespace App\Services;

use App\Services\Fees\AbstractParkingFeeStrategy;

class ParkingFeeCalculator extends AbstractParkingFeeStrategy
{
    public function __construct()
    {
        parent::__construct(20);
    }

    public function calculate(int $minutes): int
    {
        return $this->calculateHourlyFee($minutes);
    }
}
