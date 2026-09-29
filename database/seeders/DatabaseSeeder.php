<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\TransactionType;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminRole = Role::create([
            'name' => 'Admin',
            'description' => 'Administrator / Guru',
        ]);

        $kasirRole = Role::create([
            'name' => 'Kasir',
            'description' => 'Siswa / Kasir BNI',
        ]);

        User::create([
            'role_id' => $adminRole->id,
            'full_name' => 'Guru Pembimbing',
            'username' => env('ADMIN_USERNAME'),
            'password' => env('ADMIN_PASSWORD'),
            'is_active' => true,
        ]);

        User::create([
            'role_id' => $kasirRole->id,
            'full_name' => 'Agus Setiawan',
            'student_number' => '123456789',
            'class_group' => 'XI RPL 1',
            'username' => env('KASIR_USERNAME'),
            'password' => env('KASIR_PASSWORD'),
            'is_active' => true,
        ]);

        $types = [
            'Setor Tunai BNI', 'Setor Tunai Antar Bank', 'PULSA- INDOSAT', 'PULSA-TSEL',
            'PULSA-XL', 'PULSA- 3', 'PLN- PREPAID', 'PLN', 'BPJS', 'TOPUP- GOPAY',
            'TOPUP- SPAY', 'TOPUP-DANA', 'TELKOM', 'PDAM', 'Tarik Tunai', 'Tagihan Telepon', 'Materai',
        ];

        $typeData = array_map(function ($t) {
            return [
                'code' => Str::slug($t, '_'),
                'name' => $t,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $types);

        TransactionType::insert($typeData);

        $this->call(DummyDataSeeder::class);
    }
}
