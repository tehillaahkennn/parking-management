<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_parking_staff_cannot_access_staff_management(): void
    {
        $user = User::factory()->create([
            'role' => 'Parking Staff',
        ]);

        $response = $this->actingAs($user)->get('/staff');

        $response->assertForbidden();
    }

    public function test_admin_can_access_staff_management(): void
    {
        $user = User::factory()->create([
            'role' => 'Admin',
        ]);

        $response = $this->actingAs($user)->get('/staff');

        $response->assertOk();
    }

    public function test_parking_staff_cannot_access_audit_trail(): void
    {
        $user = User::factory()->create([
            'role' => 'Parking Staff',
        ]);

        $response = $this->actingAs($user)->get('/audit-logs');

        $response->assertForbidden();
    }
}