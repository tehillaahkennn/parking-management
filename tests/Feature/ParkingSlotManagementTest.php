<?php

namespace Tests\Feature;

use App\Models\ParkingSlot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParkingSlotManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_parking_slots(): void
    {
        $admin = User::factory()->create([
            'role' => 'Admin',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('parking-slots.index'));

        $response->assertOk();
    }

    public function test_parking_staff_cannot_create_parking_slots(): void
    {
        $staff = User::factory()->create([
            'role' => 'Parking Staff',
        ]);

        $response = $this->actingAs($staff)
            ->get(route('parking-slots.create'));

        $response->assertForbidden();
    }

    public function test_admin_can_create_parking_slot(): void
    {
        $admin = User::factory()->create([
            'role' => 'Admin',
        ]);

        $response = $this->actingAs($admin)->post(
            route('parking-slots.store'),
            [
                'slot_number' => 'TEST-A01',
                'slot_type' => 'Car',
            ]
        );

        $response->assertRedirect(route('parking-slots.index'));

        $this->assertDatabaseHas('parking_slots', [
            'slot_number' => 'TEST-A01',
            'slot_type' => 'Car',
            'status' => 'Available',
        ]);
    }

    public function test_duplicate_slot_number_is_rejected(): void
    {
        $admin = User::factory()->create([
            'role' => 'Admin',
        ]);

        ParkingSlot::create([
            'slot_number' => 'TEST-A02',
            'slot_type' => 'Car',
            'status' => 'Available',
        ]);

        $response = $this->actingAs($admin)->post(
            route('parking-slots.store'),
            [
                'slot_number' => 'TEST-A02',
                'slot_type' => 'Motorcycle',
            ]
        );

        $response->assertSessionHasErrors('slot_number');

        $this->assertDatabaseCount('parking_slots', 1);
    }

    public function test_admin_can_update_available_slot(): void
    {
        $admin = User::factory()->create([
            'role' => 'Admin',
        ]);

        $slot = ParkingSlot::create([
            'slot_number' => 'TEST-A03',
            'slot_type' => 'Car',
            'status' => 'Available',
        ]);

        $response = $this->actingAs($admin)->put(
            route('parking-slots.update', $slot),
            [
                'slot_number' => 'TEST-A03',
                'slot_type' => 'Van',
            ]
        );

        $response->assertRedirect(route('parking-slots.index'));

        $this->assertDatabaseHas('parking_slots', [
            'id' => $slot->id,
            'slot_type' => 'Van',
            'status' => 'Available',
        ]);
    }

    public function test_admin_cannot_delete_slot_with_parking_history(): void
    {
        $admin = User::factory()->create([
            'role' => 'Admin',
        ]);

        $slot = ParkingSlot::create([
            'slot_number' => 'TEST-A04',
            'slot_type' => 'Car',
            'status' => 'Available',
        ]);

        // A slot with history must remain in the database.
        // Create the related records using the project's existing test schema.
        $vehicle = \App\Models\Vehicle::create([
            'plate_number' => 'TESTHISTORY01',
            'owner_name' => 'Test Owner',
            'vehicle_type' => 'Car',
        ]);

        \App\Models\ParkingRecord::create([
            'vehicle_id' => $vehicle->id,
            'parking_slot_id' => $slot->id,
            'entry_time' => now()->subHour(),
            'exit_time' => now(),
            'parking_fee' => 20,
            'status' => 'Completed',
        ]);

        $response = $this->actingAs($admin)->delete(
            route('parking-slots.destroy', $slot)
        );

        $response->assertRedirect(route('parking-slots.index'));

        $this->assertDatabaseHas('parking_slots', [
            'id' => $slot->id,
        ]);
    }
}
