<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(PreventRequestForgery::class);
    }

    public function test_admin_can_view_create_user_page(): void
    {
        $adminRole = Role::create(['name' => 'Admin']);
        $admin = User::create([
            'role_id' => $adminRole->id,
            'full_name' => 'Admin User',
            'username' => 'admin_test',
            'password' => 'secret123',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.users.create'));

        $response->assertStatus(200);
        $response->assertSee('Tambah User Baru');
    }

    public function test_admin_can_create_a_new_user(): void
    {
        $adminRole = Role::create(['name' => 'Admin']);
        $kasirRole = Role::create(['name' => 'Kasir']);

        $admin = User::create([
            'role_id' => $adminRole->id,
            'full_name' => 'Admin User',
            'username' => 'admin_test',
            'password' => 'secret123',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'full_name' => 'Budi Santoso',
            'username' => 'budikeren',
            'role_id' => $kasirRole->id,
            'student_number' => '12345',
            'class_group' => 'XII RPL 2',
            'password' => 'secretpassword',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.users'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'username' => 'budikeren',
            'full_name' => 'Budi Santoso',
            'student_number' => '12345',
            'class_group' => 'XII RPL 2',
            'role_id' => $kasirRole->id,
            'is_active' => 1,
        ]);

        $createdUser = User::where('username', 'budikeren')->first();
        $this->assertNotNull($createdUser);
        $this->assertTrue(Hash::check('secretpassword', $createdUser->password));
    }

    public function test_create_user_validation_fails_with_invalid_data(): void
    {
        $adminRole = Role::create(['name' => 'Admin']);
        $admin = User::create([
            'role_id' => $adminRole->id,
            'full_name' => 'Admin User',
            'username' => 'admin_test',
            'password' => 'secret123',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'full_name' => '',
            'username' => '',
            'role_id' => 99999,
            'password' => '123',
            'is_active' => 'invalid',
        ]);

        $response->assertSessionHasErrors(['full_name', 'username', 'role_id', 'password', 'is_active']);
    }

    public function test_cannot_create_user_with_duplicate_username(): void
    {
        $adminRole = Role::create(['name' => 'Admin']);
        $admin = User::create([
            'role_id' => $adminRole->id,
            'full_name' => 'Admin User',
            'username' => 'admin_test',
            'password' => 'secret123',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'full_name' => 'Duplicate Admin',
            'username' => 'admin_test',
            'role_id' => $adminRole->id,
            'password' => 'password123',
            'is_active' => '1',
        ]);

        $response->assertSessionHasErrors(['username']);
    }

    public function test_non_admin_cannot_create_user(): void
    {
        $kasirRole = Role::create(['name' => 'Kasir']);
        $kasir = User::create([
            'role_id' => $kasirRole->id,
            'full_name' => 'Kasir User',
            'username' => 'kasir_test',
            'password' => 'secret123',
            'is_active' => true,
        ]);

        $response = $this->actingAs($kasir)->post(route('admin.users.store'), [
            'full_name' => 'Someone',
            'username' => 'someone',
            'role_id' => $kasirRole->id,
            'password' => 'password123',
            'is_active' => '1',
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_cannot_delete_self(): void
    {
        $adminRole = Role::create(['name' => 'Admin']);
        $admin = User::create([
            'role_id' => $adminRole->id,
            'full_name' => 'Admin User',
            'username' => 'admin_test',
            'password' => 'secret123',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $admin->id));

        $response->assertRedirect(route('admin.users'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_admin_cannot_delete_only_remaining_admin(): void
    {
        $adminRole = Role::create(['name' => 'Admin']);
        $admin1 = User::create([
            'role_id' => $adminRole->id,
            'full_name' => 'Admin One',
            'username' => 'admin_one',
            'password' => 'secret123',
            'is_active' => true,
        ]);
        $admin2 = User::create([
            'role_id' => $adminRole->id,
            'full_name' => 'Admin Two',
            'username' => 'admin_two',
            'password' => 'secret123',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin1)->delete(route('admin.users.destroy', $admin2->id));
        $response->assertRedirect(route('admin.users'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('users', ['id' => $admin2->id]);
    }

    public function test_admin_guru_can_delete_setup_admin_and_setup_cannot_login(): void
    {
        $adminRole = Role::create(['name' => 'Admin']);

        $setupAdmin = User::create([
            'role_id' => $adminRole->id,
            'full_name' => 'Setup Installer',
            'username' => 'setup',
            'password' => 'setup123',
            'is_active' => true,
        ]);

        $guruAdmin = User::create([
            'role_id' => $adminRole->id,
            'full_name' => 'Guru Pembimbing',
            'username' => 'guru',
            'password' => 'guru123',
            'is_active' => true,
        ]);

        $response = $this->actingAs($guruAdmin)->delete(route('admin.users.destroy', $setupAdmin->id));

        $response->assertRedirect(route('admin.users'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('users', ['username' => 'setup']);

        $this->post('/logout');
        $loginResponse = $this->post(route('login'), [
            'username' => 'setup',
            'password' => 'setup123',
        ]);

        $loginResponse->assertSessionHasErrors(['username']);
        $this->assertGuest();
    }

    public function test_admin_can_view_edit_user_page(): void
    {
        $adminRole = Role::create(['name' => 'Admin']);
        $admin = User::create([
            'role_id' => $adminRole->id,
            'full_name' => 'Admin User',
            'username' => 'admin_test',
            'password' => 'secret123',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.users.edit', $admin->id));

        $response->assertStatus(200);
        $response->assertSee('Edit Data User');
        $response->assertSee('Admin User');
    }

    public function test_admin_can_update_user_without_changing_password(): void
    {
        $adminRole = Role::create(['name' => 'Admin']);
        $admin = User::create([
            'role_id' => $adminRole->id,
            'full_name' => 'Admin Lama',
            'username' => 'admin_lama',
            'password' => 'passwordlama',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.users.update', $admin->id), [
            'full_name' => 'Admin Baru',
            'username' => 'admin_lama', // same username
            'role_id' => $adminRole->id,
            'password' => '', // blank, do not change
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.users'));
        $response->assertSessionHas('success');

        $admin->refresh();
        $this->assertEquals('Admin Baru', $admin->full_name);
        $this->assertTrue(Hash::check('passwordlama', $admin->password));
    }

    public function test_admin_can_update_user_and_change_password(): void
    {
        $adminRole = Role::create(['name' => 'Admin']);
        $admin = User::create([
            'role_id' => $adminRole->id,
            'full_name' => 'Admin User',
            'username' => 'admin_user',
            'password' => 'passwordlama',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.users.update', $admin->id), [
            'full_name' => 'Admin User',
            'username' => 'admin_user',
            'role_id' => $adminRole->id,
            'password' => 'passwordbaru123',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.users'));
        $admin->refresh();
        $this->assertTrue(Hash::check('passwordbaru123', $admin->password));
    }

    public function test_only_remaining_admin_cannot_be_demoted(): void
    {
        $adminRole = Role::create(['name' => 'Admin']);
        $kasirRole = Role::create(['name' => 'Kasir']);

        $onlyAdmin = User::create([
            'role_id' => $adminRole->id,
            'full_name' => 'Only Admin',
            'username' => 'only_admin',
            'password' => 'secret123',
            'is_active' => true,
        ]);

        $response = $this->actingAs($onlyAdmin)->put(route('admin.users.update', $onlyAdmin->id), [
            'full_name' => 'Only Admin',
            'username' => 'only_admin',
            'role_id' => $kasirRole->id, // attempt to demote to kasir
            'is_active' => '1',
        ]);

        $response->assertSessionHasErrors(['role_id']);
    }

    public function test_admin_can_update_user_via_edit_url(): void
    {
        $adminRole = Role::create(['name' => 'Admin']);
        $admin = User::create([
            'role_id' => $adminRole->id,
            'full_name' => 'Admin Test',
            'username' => 'admintest',
            'password' => 'secret123',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->put('/admin/users/'.$admin->id.'/edit', [
            'full_name' => 'Admin Test Updated',
            'username' => 'admintest',
            'role_id' => $adminRole->id,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.users'));
        $admin->refresh();
        $this->assertEquals('Admin Test Updated', $admin->full_name);
    }
}
