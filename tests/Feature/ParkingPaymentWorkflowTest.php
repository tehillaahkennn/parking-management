<?php

namespace Tests\Feature;

use App\Models\ParkingRecord;
use App\Models\ParkingSlot;
use App\Models\Payment;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;

class ParkingPaymentWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        /*
         * Build the workflow tables needed by these SQLite tests.
         */
        Schema::dropIfExists('payments');
        Schema::dropIfExists('parking_records');
        Schema::dropIfExists('vehicles');

        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('plate_number')->unique();
            $table->string('owner_name');
            $table->string('vehicle_type');
            $table->timestamps();
        });

        Schema::create('parking_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles');
            $table->foreignId('parking_slot_id')
                ->constrained('parking_slots');
            $table->dateTime('entry_time');
            $table->dateTime('exit_time')->nullable();
            $table->decimal('parking_fee', 10, 2)->default(0);
            $table->string('status')->default('Parked');
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parking_record_id')
                ->unique()
                ->constrained('parking_records');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method', 30);
            $table->dateTime('paid_at');
            $table->timestamps();
        });
    }

    public function test_payment_is_rejected_before_checkout(): void
    {
        $user = User::factory()->create([
            'role' => 'Admin',
        ]);

        $slot = ParkingSlot::create([
            'slot_number' => 'TEST-01',
            'slot_type' => 'Car',
            'status' => 'Occupied',
        ]);

        $vehicle = Vehicle::create([
            'plate_number' => 'TEST123',
            'owner_name' => 'Test Owner',
            'vehicle_type' => 'Car',
        ]);

        $record = ParkingRecord::create([
            'vehicle_id' => $vehicle->id,
            'parking_slot_id' => $slot->id,
            'entry_time' => now(),
            'parking_fee' => 0,
            'status' => 'Parked',
        ]);

        $response = $this->actingAs($user)->post('/payments', [
            'parking_record_id' => $record->id,
            'payment_method' => 'Cash',
        ]);

        $response->assertSessionHasErrors('parking_record_id');

        $this->assertDatabaseMissing('payments', [
            'parking_record_id' => $record->id,
        ]);
    }

    public function test_completed_parking_record_can_be_paid(): void
    {
        $user = User::factory()->create([
            'role' => 'Admin',
        ]);

        $slot = ParkingSlot::create([
            'slot_number' => 'TEST-02',
            'slot_type' => 'Car',
            'status' => 'Available',
        ]);

        $vehicle = Vehicle::create([
            'plate_number' => 'TEST456',
            'owner_name' => 'Test Owner',
            'vehicle_type' => 'Car',
        ]);

        $record = ParkingRecord::create([
            'vehicle_id' => $vehicle->id,
            'parking_slot_id' => $slot->id,
            'entry_time' => now()->subHour(),
            'exit_time' => now(),
            'parking_fee' => 20,
            'status' => 'Completed',
        ]);

        $response = $this->actingAs($user)->post('/payments', [
            'parking_record_id' => $record->id,
            'payment_method' => 'Cash',
        ]);

        $response->assertRedirect(route('payments.index'));

        $this->assertDatabaseHas('payments', [
            'parking_record_id' => $record->id,
            'payment_method' => 'Cash',
            'amount' => 20,
        ]);
    }

    public function test_duplicate_payment_is_rejected(): void
    {
        $user = User::factory()->create([
            'role' => 'Admin',
        ]);

        $slot = ParkingSlot::create([
            'slot_number' => 'TEST-03',
            'slot_type' => 'Car',
            'status' => 'Available',
        ]);

        $vehicle = Vehicle::create([
            'plate_number' => 'TEST789',
            'owner_name' => 'Test Owner',
            'vehicle_type' => 'Car',
        ]);

        $record = ParkingRecord::create([
            'vehicle_id' => $vehicle->id,
            'parking_slot_id' => $slot->id,
            'entry_time' => now()->subHour(),
            'exit_time' => now(),
            'parking_fee' => 20,
            'status' => 'Completed',
        ]);

        Payment::create([
            'parking_record_id' => $record->id,
            'amount' => 20,
            'payment_method' => 'Cash',
            'paid_at' => now(),
        ]);

        $response = $this->actingAs($user)->post('/payments', [
            'parking_record_id' => $record->id,
            'payment_method' => 'GCash',
        ]);

        $response->assertSessionHasErrors('parking_record_id');

        $this->assertDatabaseCount('payments', 1);
    }

    public function test_vehicle_can_check_in_to_an_available_slot(): void
    {
        $user = User::factory()->create([
            'role' => 'Admin',
        ]);

        $slot = ParkingSlot::create([
            'slot_number' => 'CHECKIN-01',
            'slot_type' => 'Car',
            'status' => 'Available',
        ]);

        $response = $this->actingAs($user)->post('/parking', [
            'plate_number' => 'CHK1001',
            'owner_name' => 'Check-in Test',
            'vehicle_type' => 'Car',
            'parking_slot_id' => $slot->id,
        ]);

        $response->assertRedirect(route('parking.index'));

        $this->assertDatabaseHas('vehicles', [
            'plate_number' => 'CHK1001',
            'owner_name' => 'Check-in Test',
        ]);

        $this->assertDatabaseHas('parking_records', [
            'parking_slot_id' => $slot->id,
            'status' => 'Parked',
        ]);

        $this->assertDatabaseHas('parking_slots', [
            'id' => $slot->id,
            'status' => 'Occupied',
        ]);
    }

    public function test_vehicle_cannot_check_in_to_an_occupied_slot(): void
    {
        $user = User::factory()->create([
            'role' => 'Admin',
        ]);

        $slot = ParkingSlot::create([
            'slot_number' => 'CHECKIN-02',
            'slot_type' => 'Car',
            'status' => 'Occupied',
        ]);

        $response = $this->actingAs($user)->post('/parking', [
            'plate_number' => 'CHK1002',
            'owner_name' => 'Occupied Slot Test',
            'vehicle_type' => 'Car',
            'parking_slot_id' => $slot->id,
        ]);

        $response->assertSessionHasErrors('parking_slot_id');

        $this->assertDatabaseMissing('vehicles', [
            'plate_number' => 'CHK1002',
        ]);

        $this->assertDatabaseHas('parking_slots', [
            'id' => $slot->id,
            'status' => 'Occupied',
        ]);
    }

    public function test_vehicle_cannot_check_in_to_mismatched_slot_type(): void
    {
        $user = User::factory()->create([
            'role' => 'Admin',
        ]);

        $slot = ParkingSlot::create([
            'slot_number' => 'TYPECHECK-01',
            'slot_type' => 'Motorcycle',
            'status' => 'Available',
        ]);

        $response = $this->actingAs($user)->post('/parking', [
            'plate_number' => 'TYPE1001',
            'owner_name' => 'Type Mismatch Test',
            'vehicle_type' => 'Car',
            'parking_slot_id' => $slot->id,
        ]);

        $response->assertSessionHasErrors('parking_slot_id');

        $this->assertDatabaseMissing('vehicles', [
            'plate_number' => 'TYPE1001',
        ]);

        $this->assertDatabaseMissing('parking_records', [
            'parking_slot_id' => $slot->id,
        ]);

        $this->assertDatabaseHas('parking_slots', [
            'id' => $slot->id,
            'status' => 'Available',
        ]);
    }

    public function test_vehicle_can_check_in_when_slot_type_matches(): void
    {
        $user = User::factory()->create([
            'role' => 'Admin',
        ]);

        $slot = ParkingSlot::create([
            'slot_number' => 'TYPECHECK-02',
            'slot_type' => 'Motorcycle',
            'status' => 'Available',
        ]);

        $response = $this->actingAs($user)->post('/parking', [
            'plate_number' => 'TYPE1002',
            'owner_name' => 'Matching Type Test',
            'vehicle_type' => 'Motorcycle',
            'parking_slot_id' => $slot->id,
        ]);

        $response->assertRedirect(route('parking.index'));

        $this->assertDatabaseHas('vehicles', [
            'plate_number' => 'TYPE1002',
            'vehicle_type' => 'Motorcycle',
        ]);

        $this->assertDatabaseHas('parking_records', [
            'parking_slot_id' => $slot->id,
            'status' => 'Parked',
        ]);

        $this->assertDatabaseHas('parking_slots', [
            'id' => $slot->id,
            'status' => 'Occupied',
        ]);
    }

    public function test_checkout_completes_parking_and_releases_slot(): void
    {
        $user = User::factory()->create([
            'role' => 'Admin',
        ]);

        $slot = ParkingSlot::create([
            'slot_number' => 'CHECKOUT-01',
            'slot_type' => 'Car',
            'status' => 'Occupied',
        ]);

        $vehicle = Vehicle::create([
            'plate_number' => 'OUT1001',
            'owner_name' => 'Checkout Test',
            'vehicle_type' => 'Car',
        ]);

        $record = ParkingRecord::create([
            'vehicle_id' => $vehicle->id,
            'parking_slot_id' => $slot->id,
            'entry_time' => now()->subMinutes(61),
            'parking_fee' => 0,
            'status' => 'Parked',
        ]);

        $response = $this->actingAs($user)
            ->patch("/parking/{$record->id}/checkout");

        $response->assertRedirect(route('parking.index'));

        $this->assertDatabaseHas('parking_records', [
            'id' => $record->id,
            'status' => 'Completed',
            'parking_fee' => 40,
        ]);

        $this->assertDatabaseHas('parking_slots', [
            'id' => $slot->id,
            'status' => 'Available',
        ]);

        $this->assertNotNull(
            $record->fresh()->exit_time,
            'Checkout should record the exit time.'
        );
    }

    public function test_completed_parking_record_cannot_be_checked_out_twice(): void
    {
        $user = User::factory()->create([
            'role' => 'Admin',
        ]);

        $slot = ParkingSlot::create([
            'slot_number' => 'CHECKOUT-02',
            'slot_type' => 'Car',
            'status' => 'Available',
        ]);

        $vehicle = Vehicle::create([
            'plate_number' => 'OUT1002',
            'owner_name' => 'Double Checkout Test',
            'vehicle_type' => 'Car',
        ]);

        $record = ParkingRecord::create([
            'vehicle_id' => $vehicle->id,
            'parking_slot_id' => $slot->id,
            'entry_time' => now()->subHour(),
            'exit_time' => now(),
            'parking_fee' => 20,
            'status' => 'Completed',
        ]);

        $response = $this->actingAs($user)
            ->patch("/parking/{$record->id}/checkout");

        $response->assertSessionHasErrors();

        $this->assertDatabaseHas('parking_records', [
            'id' => $record->id,
            'status' => 'Completed',
            'parking_fee' => 20,
        ]);

        $this->assertDatabaseHas('parking_slots', [
            'id' => $slot->id,
            'status' => 'Available',
        ]);
    }
}
