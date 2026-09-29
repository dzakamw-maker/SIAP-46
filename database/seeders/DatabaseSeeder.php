<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminRole = \App\Models\Role::create([
            'name' => 'Admin',
            'description' => 'Administrator / Guru'
        ]);

        $kasirRole = \App\Models\Role::create([
            'name' => 'Kasir',
            'description' => 'Siswa / Kasir BNI'
        ]);

        \App\Models\User::create([
            'role_id' => $adminRole->id,
            'full_name' => 'Guru Pembimbing',
            'username' => 'admin',
            'password' => 'password',
            'is_active' => true,
        ]);

        \App\Models\User::create([
            'role_id' => $kasirRole->id,
            'full_name' => 'Agus Setiawan',
            'student_number' => '123456789',
            'class_group' => 'XI RPL 1',
            'username' => 'kasir',
            'password' => 'password',
            'is_active' => true,
        ]);
    }
}
