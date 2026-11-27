<?php

namespace Tests\Unit;

use App\Services\ParkingFeeCalculator;
use PHPUnit\Framework\TestCase;

class ParkingFeeCalculatorTest extends TestCase
{
    public function test_it_calculates_parking_fees_correctly(): void
    {
        $calculator = new ParkingFeeCalculator();

        $this->assertSame(20, $calculator->calculate(0));
        $this->assertSame(20, $calculator->calculate(1));
        $this->assertSame(20, $calculator->calculate(60));
        $this->assertSame(40, $calculator->calculate(61));
        $this->assertSame(40, $calculator->calculate(120));
        $this->assertSame(60, $calculator->calculate(121));
    }
}