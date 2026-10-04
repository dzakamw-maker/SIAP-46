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
        $adminRole = Role::firstOrCreate(
            ['name' => 'Admin'],
            ['description' => 'Administrator / Guru']
        );

        $kasirRole = Role::firstOrCreate(
            ['name' => 'Kasir'],
            ['description' => 'Siswa / Kasir BNI']
        );

        User::firstOrCreate(
            ['username' => env('ADMIN_USERNAME', 'admin')],
            [
                'role_id' => $adminRole->id,
                'full_name' => 'Guru Pembimbing',
                'password' => env('ADMIN_PASSWORD', 'password'),
                'is_active' => true,
            ]
        );

        User::firstOrCreate(
            ['username' => env('KASIR_USERNAME', 'kasir')],
            [
                'role_id' => $kasirRole->id,
                'full_name' => 'Agus Setiawan',
                'student_number' => '123456789',
                'class_group' => 'XI RPL 1',
                'password' => env('KASIR_PASSWORD', 'password'),
                'is_active' => true,
            ]
        );

        $types = [
            'Setor Tunai BNI', 'Setor Tunai Antar Bank', 'PULSA- INDOSAT', 'PULSA-TSEL',
            'PULSA-XL', 'PULSA- 3', 'PLN- PREPAID', 'PLN', 'BPJS', 'TOPUP- GOPAY',
            'TOPUP- SPAY', 'TOPUP-DANA', 'TELKOM', 'PDAM', 'Tarik Tunai', 'Tagihan Telepon', 'Materai', 'Bayar SPP',
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

        TransactionType::insertOrIgnore($typeData);

        $this->call(DummyDataSeeder::class);
    }
}
