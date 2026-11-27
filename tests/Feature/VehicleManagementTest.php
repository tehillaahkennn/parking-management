<?php

namespace Tests\Feature;

use App\Models\ParkingRecord;
use App\Models\ParkingSlot;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleManagementTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        return User::factory()->create([
            'role' => 'Admin',
            'is_active' => true,
        ]);
    }

    private function createVehicle(array $attributes = []): Vehicle
    {
        return Vehicle::create(array_merge([
            'plate_number' => 'TEST-VEHICLE-01',
            'owner_name' => 'Test Owner',
            'vehicle_type' => 'Car',
        ], $attributes));
    }

    private function createParkingHistory(Vehicle $vehicle): ParkingRecord
    {
        $slot = ParkingSlot::create([
            'slot_number' => 'TEST-HISTORY-01',
            'slot_type' => 'Car',
            'status' => 'Available',
        ]);

        return ParkingRecord::create([
            'vehicle_id' => $vehicle->id,
            'parking_slot_id' => $slot->id,
            'entry_time' => now()->subHour(),
            'exit_time' => now(),
            'parking_fee' => 20,
            'status' => 'Completed',
        ]);
    }

    public function test_admin_can_register_vehicle(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post(
            route('vehicles.store'),
            [
                'plate_number' => 'TEST-CREATE-01',
                'owner_name' => 'Juan Dela Cruz',
                'vehicle_type' => 'Car',
            ]
        );

        $response->assertRedirect(route('vehicles.index'));

        $this->assertDatabaseHas('vehicles', [
            'plate_number' => 'TEST-CREATE-01',
            'owner_name' => 'Juan Dela Cruz',
            'vehicle_type' => 'Car',
        ]);
    }

    public function test_duplicate_plate_number_is_rejected(): void
    {
        $admin = $this->createAdmin();

        $this->createVehicle([
            'plate_number' => 'TEST-DUPLICATE-01',
        ]);

        $response = $this->actingAs($admin)->post(
            route('vehicles.store'),
            [
                'plate_number' => 'TEST-DUPLICATE-01',
                'owner_name' => 'Another Owner',
                'vehicle_type' => 'Car',
            ]
        );

        $response->assertSessionHasErrors('plate_number');

        $this->assertDatabaseCount('vehicles', 1);
    }

    public function test_admin_can_update_vehicle_without_parking_history(): void
    {
        $admin = $this->createAdmin();

        $vehicle = $this->createVehicle();

        $response = $this->actingAs($admin)->put(
            route('vehicles.update', $vehicle),
            [
                'plate_number' => 'TEST-UPDATED-01',
                'owner_name' => 'Updated Owner',
                'vehicle_type' => 'Van',
            ]
        );

        $response->assertRedirect(route('vehicles.index'));

        $this->assertDatabaseHas('vehicles', [
            'id' => $vehicle->id,
            'plate_number' => 'TEST-UPDATED-01',
            'owner_name' => 'Updated Owner',
            'vehicle_type' => 'Van',
        ]);
    }

    public function test_vehicle_type_cannot_change_when_parking_history_exists(): void
    {
        $admin = $this->createAdmin();

        $vehicle = $this->createVehicle();

        $this->createParkingHistory($vehicle);

        $response = $this->actingAs($admin)->put(
            route('vehicles.update', $vehicle),
            [
                'plate_number' => $vehicle->plate_number,
                'owner_name' => 'Updated Owner',
                'vehicle_type' => 'Van',
            ]
        );

        $response->assertSessionHasErrors('vehicle_type');

        $this->assertDatabaseHas('vehicles', [
            'id' => $vehicle->id,
            'vehicle_type' => 'Car',
        ]);
    }

    public function test_vehicle_with_parking_history_cannot_be_deleted(): void
    {
        $admin = $this->createAdmin();

        $vehicle = $this->createVehicle();

        $this->createParkingHistory($vehicle);

        $response = $this->actingAs($admin)->delete(
            route('vehicles.destroy', $vehicle)
        );

        $response->assertRedirect(route('vehicles.index'));

        $this->assertDatabaseHas('vehicles', [
            'id' => $vehicle->id,
        ]);
    }

    public function test_vehicle_without_parking_history_can_be_deleted(): void
    {
        $admin = $this->createAdmin();

        $vehicle = $this->createVehicle();

        $response = $this->actingAs($admin)->delete(
            route('vehicles.destroy', $vehicle)
        );

        $response->assertRedirect(route('vehicles.index'));

        $this->assertDatabaseMissing('vehicles', [
            'id' => $vehicle->id,
        ]);
    }
}
