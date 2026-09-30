<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('make:admin {--name= : Nama lengkap admin} {--username= : Username admin} {--password= : Password admin}')]
#[Description('Membuat akun Admin pertama atau baru beserta inisialisasi Role')]
class MakeAdmin extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->components->info('Inisialisasi akun Administrator...');

        // Pastikan role Admin & Kasir sudah tersedia di database
        $adminRole = Role::firstOrCreate(
            ['name' => 'Admin'],
            ['description' => 'Administrator / Guru']
        );

        Role::firstOrCreate(
            ['name' => 'Kasir'],
            ['description' => 'Siswa / Kasir BNI']
        );

        $name = $this->option('name');
        if (! $name) {
            $name = $this->ask('Nama Lengkap Admin', 'Administrator');
        }

        $username = $this->option('username');
        while (empty($username) || User::where('username', $username)->exists()) {
            if ($username && User::where('username', $username)->exists()) {
                $this->error("Username '{$username}' sudah terdaftar. Silakan gunakan username lain.");
            }
            $username = $this->ask('Username Admin');
        }

        $password = $this->option('password');
        while (empty($password) || strlen($password) < 6) {
            if ($password && strlen($password) < 6) {
                $this->error('Password minimal harus 6 karakter.');
            }
            $password = $this->secret('Password Admin (min 6 karakter)');
        }

        $user = User::create([
            'role_id' => $adminRole->id,
            'full_name' => $name,
            'username' => $username,
            'password' => $password,
            'is_active' => true,
        ]);

        $this->components->info("Akun Admin '{$user->username}' ({$user->full_name}) berhasil dibuat!");
        $this->line('Silakan login di halaman web menggunakan username & password tersebut.');

        return self::SUCCESS;
    }
}
