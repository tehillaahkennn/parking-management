<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ParkingSlotController;
use App\Http\Controllers\ParkingRecordController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AuditLogController;

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.submit')
    ->middleware('throttle:5,1');

Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    // Dashboard redirect
    Route::get('/', function () {
        return redirect()->route('parking.index');
    });

    // Admin and Parking Staff
    Route::middleware('role:Admin,Parking Staff')->group(function () {

        // Parking records
        Route::get('/parking', [ParkingRecordController::class, 'index'])
            ->name('parking.index');

        Route::get('/parking/create', [ParkingRecordController::class, 'create'])
            ->name('parking.create');

        Route::post('/parking', [ParkingRecordController::class, 'store'])
            ->name('parking.store');

        Route::patch(
            '/parking/{parkingRecord}/checkout',
            [ParkingRecordController::class, 'checkout']
        )->name('parking.checkout');

        Route::get(
            '/parking/{parkingRecord}',
            [ParkingRecordController::class, 'show']
        )->name('parking.show');

        // Parking slots
        Route::get('/parking-slots', [ParkingSlotController::class, 'index'])
            ->name('parking-slots.index');

        // Vehicle management
        Route::get('/vehicles', [VehicleController::class, 'index'])
            ->name('vehicles.index');

        Route::get('/vehicles/create', [VehicleController::class, 'create'])
            ->name('vehicles.create');

        Route::post('/vehicles', [VehicleController::class, 'store'])
            ->name('vehicles.store');

        Route::get(
            '/vehicles/{vehicle}/edit',
            [VehicleController::class, 'edit']
        )->name('vehicles.edit');

        Route::put(
            '/vehicles/{vehicle}',
            [VehicleController::class, 'update']
        )->name('vehicles.update');

        Route::delete(
            '/vehicles/{vehicle}',
            [VehicleController::class, 'destroy']
        )->name('vehicles.destroy');

        // Payments
        Route::get('/payments', [PaymentController::class, 'index'])
            ->name('payments.index');

        Route::get('/payments/create', [PaymentController::class, 'create'])
            ->name('payments.create');

        Route::post('/payments', [PaymentController::class, 'store'])
            ->name('payments.store');
    });

    // Admin only
    Route::middleware('role:Admin')->group(function () {

        // Staff account management
        Route::resource('staff', StaffController::class)
            ->except(['show']);

        // Activate or deactivate a staff account
        Route::patch(
            '/staff/{staff}/toggle-status',
            [StaffController::class, 'toggleStatus']
        )->name('staff.toggle-status');

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])
            ->name('reports.index');

        Route::get('/reports/parking', [ReportController::class, 'parking'])
            ->name('reports.parking');

        Route::get('/reports/payments', [ReportController::class, 'payments'])
            ->name('reports.payments');

        // Audit logs
        Route::get('/audit-logs', [AuditLogController::class, 'index'])
            ->name('audit-logs.index');
    });
});
