<?php

namespace App\Providers;

use App\Contracts\ParkingFeeStrategy;
use App\Services\ParkingFeeCalculator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            ParkingFeeStrategy::class,
            ParkingFeeCalculator::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
