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

    public function test_inactive_staff_cannot_access_parking_pages(): void
    {
        $user = User::factory()->create([
            'role' => 'Parking Staff',
            'is_active' => false,
        ]);

        $response = $this->actingAs($user)->get('/parking');

        $response->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_admin_can_access_staff_management_with_inactive_staff(): void
    {
        User::factory()->create([
            'name' => 'Inactive Test Staff',
            'email' => 'inactive.staff@example.com',
            'role' => 'Parking Staff',
            'is_active' => false,
        ]);

        $admin = User::factory()->create([
            'role' => 'Admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get('/staff');

        $response->assertOk();
        $response->assertSee('Inactive Test Staff');
        $response->assertSee('Activate');
    }

    public function test_inactive_staff_cannot_log_in_with_correct_password(): void
    {
        $user = User::factory()->create([
            'email' => 'inactive.login@example.com',
            'password' => 'CorrectPassword123!',
            'role' => 'Parking Staff',
            'is_active' => false,
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'CorrectPassword123!',
        ]);

        $response->assertSessionHasErrors('email');

        $this->assertGuest();
    }
}
