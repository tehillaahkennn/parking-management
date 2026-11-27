<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class AuditTrailTest extends TestCase
{
    use RefreshDatabase;

    public function test_audit_logger_records_action_user_and_ip_address(): void
    {
        $user = User::factory()->create([
            'role' => 'Admin',
            'is_active' => true,
        ]);

        $request = Request::create(
            '/audit-test',
            'GET',
            [],
            [],
            [],
            ['REMOTE_ADDR' => '192.168.1.10']
        );

        $request->setUserResolver(fn () => $user);

        AuditLogger::log(
            $request,
            'TEST_ACTION',
            'Testing audit trail recording.'
        );

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'action' => 'TEST_ACTION',
            'description' => 'Testing audit trail recording.',
            'ip_address' => '192.168.1.10',
        ]);
    }

    public function test_admin_can_access_audit_logs(): void
    {
        $admin = User::factory()->create([
            'role' => 'Admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)
            ->get(route('audit-logs.index'));

        $response->assertOk();
    }

    public function test_parking_staff_cannot_access_audit_logs(): void
    {
        $staff = User::factory()->create([
            'role' => 'Parking Staff',
            'is_active' => true,
        ]);

        $response = $this->actingAs($staff)
            ->get(route('audit-logs.index'));

        $response->assertForbidden();
    }
}
