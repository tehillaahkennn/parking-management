<?php

namespace App\Services\Fees;

class DiscountedParkingFeeCalculator extends AbstractParkingFeeStrategy
{
    public function __construct(int $hourlyRate = 15)
    {
        parent::__construct($hourlyRate);
    }

    public function calculate(int $minutes): int
    {
        return $this->calculateHourlyFee($minutes);
    }
}
