<?php

namespace Tests\Unit;

use App\Contracts\ParkingFeeStrategy;
use App\Services\Fees\DiscountedParkingFeeCalculator;
use App\Services\ParkingFeeCalculator;
use PHPUnit\Framework\TestCase;

class ParkingFeeStrategyTest extends TestCase
{
    public function test_different_fee_strategies_share_the_same_interface(): void
    {
        $strategies = [
            new ParkingFeeCalculator(),
            new DiscountedParkingFeeCalculator(),
        ];

        $expectedFees = [40, 30];

        foreach ($strategies as $index => $strategy) {
            $this->assertInstanceOf(
                ParkingFeeStrategy::class,
                $strategy
            );

            $this->assertSame(
                $expectedFees[$index],
                $strategy->calculate(61)
            );
        }
    }
}