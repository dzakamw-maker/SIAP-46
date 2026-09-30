<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('SIAP-46');
        $response->assertSee('Daftar Layanan');
        $response->assertSee('Login Petugas');
    }

    public function test_guest_can_view_login_page(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Silakan masuk menggunakan akun Anda');
    }

    public function test_unauthenticated_user_redirected_to_login_when_accessing_admin(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_admin_visiting_home_is_redirected_to_admin_dashboard(): void
    {
        $adminRole = Role::create(['name' => 'Admin']);
        $admin = User::create([
            'role_id' => $adminRole->id,
            'full_name' => 'Admin Test',
            'username' => 'admintest',
            'password' => 'secret123',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get('/');

        $response->assertRedirect('/admin');
    }

    public function test_authenticated_cashier_visiting_home_is_redirected_to_cashier_dashboard(): void
    {
        $kasirRole = Role::create(['name' => 'Kasir']);
        $kasir = User::create([
            'role_id' => $kasirRole->id,
            'full_name' => 'Kasir Test',
            'username' => 'kasirtest',
            'password' => 'secret123',
            'is_active' => true,
        ]);

        $response = $this->actingAs($kasir)->get('/');

        $response->assertRedirect('/kasir');
    }
}
