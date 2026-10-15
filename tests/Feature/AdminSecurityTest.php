<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AdminSecurityTest extends TestCase
{
    use RefreshDatabase; // Resets the database after testing

    public function test_guest_user_is_redirected_to_login()
    {
        // Attempt to visit admin dashboard without logging in
        $response = $this->get('/admin');

        // Should be redirected to the login page
        $response->assertRedirect('/login');
    }

    public function test_customer_gets_forbidden_error()
    {
        // Create a fake customer
        $customer = User::factory()->create(['role' => 'customer']);

        // Act as customer and try to access admin
        $response = $this->actingAs($customer)->get('/admin');

        // Assert they get a 403 Forbidden error
        $response->assertStatus(403);
    }

    public function test_admin_can_access_dashboard()
    {
        // Create a fake admin
        $admin = User::factory()->create(['role' => 'admin']);

        // Act as admin and try to access dashboard
        $response = $this->actingAs($admin)->get('/admin');

        // Assert success (200 OK)
        $response->assertStatus(200);
        $response->assertSee('Dashboard Summary');
    }
}