<?php

namespace Tests\Feature;

use App\Models\ParkingRecord;
use App\Models\ParkingSlot;
use App\Models\Payment;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportManagementTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create([
            'role' => 'Admin',
            'is_active' => true,
        ]);
    }

    private function parkingStaff(): User
    {
        return User::factory()->create([
            'role' => 'Parking Staff',
            'is_active' => true,
        ]);
    }

    private function createParkingRecord(
        string $plate,
        string $entryTime,
        string $status = 'Completed',
        float $fee = 100
    ): ParkingRecord {
        $vehicle = Vehicle::create([
            'plate_number' => $plate,
            'owner_name' => 'Test Owner',
            'vehicle_type' => 'Car',
        ]);

        $slot = ParkingSlot::create([
            'slot_number' => 'SLOT-' . $plate,
            'slot_type' => 'Car',
            'status' => 'Available',
        ]);

        return ParkingRecord::create([
            'vehicle_id' => $vehicle->id,
            'parking_slot_id' => $slot->id,
            'entry_time' => $entryTime,
            'exit_time' => $status === 'Completed'
                ? $entryTime
                : null,
            'parking_fee' => $fee,
            'status' => $status,
        ]);
    }

    public function test_admin_can_access_reports(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->get('/reports')->assertOk();
        $this->actingAs($admin)->get('/reports/parking')->assertOk();
        $this->actingAs($admin)->get('/reports/payments')->assertOk();
    }

    public function test_parking_staff_cannot_access_reports(): void
    {
        $staff = $this->parkingStaff();

        $this->actingAs($staff)->get('/reports')->assertForbidden();
        $this->actingAs($staff)->get('/reports/parking')->assertForbidden();
        $this->actingAs($staff)->get('/reports/payments')->assertForbidden();
    }

    public function test_parking_report_filters_by_entry_date(): void
    {
        $admin = $this->admin();

        $this->createParkingRecord(
            'REPORT-001',
            '2026-11-10 09:00:00'
        );

        $this->createParkingRecord(
            'REPORT-002',
            '2026-11-20 10:00:00'
        );

        $response = $this->actingAs($admin)->get(
            '/reports/parking?date_from=2026-11-15&date_to=2026-11-25'
        );

        $response->assertOk();
        $response->assertSee('REPORT-002');
        $response->assertDontSee('REPORT-001');
    }

    public function test_payment_report_filters_by_paid_date_and_calculates_revenue(): void
    {
        $admin = $this->admin();

        $record1 = $this->createParkingRecord(
            'PAYMENT-001',
            '2026-11-10 09:00:00',
            'Completed',
            100
        );

        $record2 = $this->createParkingRecord(
            'PAYMENT-002',
            '2026-11-20 10:00:00',
            'Completed',
            250
        );

        Payment::create([
            'parking_record_id' => $record1->id,
            'amount' => 100,
            'payment_method' => 'Cash',
            'paid_at' => '2026-11-10 10:00:00',
        ]);

        Payment::create([
            'parking_record_id' => $record2->id,
            'amount' => 250,
            'payment_method' => 'GCash',
            'paid_at' => '2026-11-20 11:00:00',
        ]);

        $response = $this->actingAs($admin)->get(
            '/reports/payments?date_from=2026-11-15&date_to=2026-11-25'
        );

        $response->assertOk();
        $response->assertSee('PAYMENT-002');
        $response->assertDontSee('PAYMENT-001');
        $response->assertSee('250.00');
    }

    public function test_report_rejects_end_date_before_start_date(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->get(
            '/reports/parking?date_from=2026-11-25&date_to=2026-11-15'
        );

        $response->assertSessionHasErrors('date_to');
    }
}
