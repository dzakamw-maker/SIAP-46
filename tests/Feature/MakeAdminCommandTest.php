<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MakeAdminCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_make_admin_creates_roles_and_admin_user_via_options(): void
    {
        $this->artisan('make:admin', [
            '--name' => 'Super Administrator',
            '--username' => 'superadmin',
            '--password' => 'supersecret123',
        ])
            ->assertSuccessful();

        $this->assertDatabaseHas('roles', ['name' => 'Admin']);
        $this->assertDatabaseHas('roles', ['name' => 'Kasir']);

        $this->assertDatabaseHas('users', [
            'username' => 'superadmin',
            'full_name' => 'Super Administrator',
            'is_active' => 1,
        ]);

        $admin = User::where('username', 'superadmin')->first();
        $this->assertNotNull($admin);
        $this->assertEquals('Admin', $admin->role->name);
        $this->assertTrue(Hash::check('supersecret123', $admin->password));
    }

    public function test_make_admin_prompts_interactively_when_options_are_missing(): void
    {
        $this->artisan('make:admin')
            ->expectsQuestion('Nama Lengkap Admin', 'Budi Direktur')
            ->expectsQuestion('Username Admin', 'budi_admin')
            ->expectsQuestion('Password Admin (min 6 karakter)', 'password123')
            ->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'username' => 'budi_admin',
            'full_name' => 'Budi Direktur',
        ]);
    }

    public function test_make_admin_repairs_or_reuses_existing_roles(): void
    {
        $existingAdminRole = Role::create([
            'name' => 'Admin',
            'description' => 'Existing description',
        ]);

        $this->artisan('make:admin', [
            '--name' => 'Admin Kedua',
            '--username' => 'admin2',
            '--password' => 'secret123',
        ])
            ->assertSuccessful();

        $this->assertEquals(1, Role::where('name', 'Admin')->count());
        $this->assertDatabaseHas('roles', ['name' => 'Kasir']);

        $user = User::where('username', 'admin2')->first();
        $this->assertEquals($existingAdminRole->id, $user->role_id);
    }
}
