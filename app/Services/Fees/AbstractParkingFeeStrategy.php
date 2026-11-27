<?php

namespace App\Services\Fees;

use App\Contracts\ParkingFeeStrategy;

abstract class AbstractParkingFeeStrategy implements ParkingFeeStrategy
{
    public function __construct(
        private readonly int $hourlyRate
    ) {
        if ($this->hourlyRate < 1) {
            throw new \InvalidArgumentException(
                'The hourly rate must be greater than zero.'
            );
        }
    }

    protected function calculateHourlyFee(int $minutes): int
    {
        $hours = max(1, (int) ceil(max(0, $minutes) / 60));

        return $hours * $this->hourlyRate;
    }
}
