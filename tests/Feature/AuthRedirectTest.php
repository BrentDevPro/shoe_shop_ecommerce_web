<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_registration_redirects_to_storefront(): void
    {
        $response = $this->post('/register', [
            'name' => 'New Customer',
            'email' => 'customer@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('store.index'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'customer@example.com',
            'role' => 'customer',
        ]);
    }

    public function test_admin_registration_redirects_to_admin_dashboard(): void
    {
        $response = $this->post(route('admin.register.submit'), [
            'name' => 'New Admin',
            'email' => 'admin@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);
    }

    public function test_customer_login_redirects_to_storefront_even_with_home_as_intended_url(): void
    {
        $customer = User::factory()->create([
            'email' => 'customer@example.com',
            'password' => bcrypt('password123'),
            'role' => 'customer',
        ]);

        $response = $this->withSession(['url.intended' => route('home')])
            ->post('/login', [
                'email' => $customer->email,
                'password' => 'password123',
            ]);

        $response->assertRedirect(route('store.index'));
        $this->assertAuthenticatedAs($customer);
    }

    public function test_admin_login_redirects_to_admin_dashboard_even_with_home_as_intended_url(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        $response = $this->withSession(['url.intended' => route('home')])
            ->post('/login', [
                'email' => $admin->email,
                'password' => 'password123',
            ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }
}