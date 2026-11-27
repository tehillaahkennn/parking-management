<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_guest_is_redirected_to_login_from_homepage(): void
    {
        $this->get('/')
            ->assertRedirect('/login');
    }

    public function test_guest_cannot_access_parking_page(): void
    {
        $this->get('/parking')
            ->assertRedirect('/login');
    }

    public function test_guest_cannot_access_audit_trail(): void
    {
        $this->get('/audit-logs')
            ->assertRedirect('/login');
    }
}
