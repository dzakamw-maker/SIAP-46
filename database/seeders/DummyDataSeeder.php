<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\DailyRecap;
use App\Models\Role;
use App\Models\StaffAttendance;
use App\Models\Transaction;
use App\Models\TransactionType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $kasirRole = Role::firstOrCreate(['name' => 'Kasir'], ['description' => 'Siswa / Kasir BNI']);

        // 1. Kasir Siswa Tambahan
        $kasirs = [
            User::firstOrCreate(
                ['username' => 'agus_setiawan'],
                [
                    'role_id' => $kasirRole->id,
                    'full_name' => 'Agus Setiawan',
                    'student_number' => '123456789',
                    'class_group' => 'XI RPL 1',
                    'password' => Hash::make('password'),
                    'is_active' => true,
                ]
            ),
            User::firstOrCreate(
                ['username' => 'siti_rahma'],
                [
                    'role_id' => $kasirRole->id,
                    'full_name' => 'Siti Rahmawati',
                    'student_number' => '123456790',
                    'class_group' => 'XI RPL 1',
                    'password' => Hash::make('password'),
                    'is_active' => true,
                ]
            ),
            User::firstOrCreate(
                ['username' => 'budi_pratama'],
                [
                    'role_id' => $kasirRole->id,
                    'full_name' => 'Budi Pratama',
                    'student_number' => '123456791',
                    'class_group' => 'XI RPL 2',
                    'password' => Hash::make('password'),
                    'is_active' => true,
                ]
            ),
            User::firstOrCreate(
                ['username' => 'dina_ananda'],
                [
                    'role_id' => $kasirRole->id,
                    'full_name' => 'Dina Ananda Putri',
                    'student_number' => '123456792',
                    'class_group' => 'XI RPL 2',
                    'password' => Hash::make('password'),
                    'is_active' => true,
                ]
            ),
        ];

        // 2. Transaksi Types
        $setorBni = TransactionType::firstOrCreate(['code' => 'setor_tunai_bni'], ['name' => 'Setor Tunai BNI', 'is_active' => true]);
        $tarikBni = TransactionType::firstOrCreate(['code' => 'tarik_tunai'], ['name' => 'Tarik Tunai', 'is_active' => true]);
        $setorAntarBank = TransactionType::firstOrCreate(['code' => 'setor_tunai_antar_bank'], ['name' => 'Setor Tunai Antar Bank', 'is_active' => true]);
        $plnPrepaid = TransactionType::firstOrCreate(['code' => 'pln_prepaid'], ['name' => 'PLN- PREPAID', 'is_active' => true]);
        $pulsaTsel = TransactionType::firstOrCreate(['code' => 'pulsa_tsel'], ['name' => 'PULSA-TSEL', 'is_active' => true]);
        $topupDana = TransactionType::firstOrCreate(['code' => 'topup_dana'], ['name' => 'TOPUP-DANA', 'is_active' => true]);
        $topupGopay = TransactionType::firstOrCreate(['code' => 'topup_gopay'], ['name' => 'TOPUP- GOPAY', 'is_active' => true]);
        $bpjs = TransactionType::firstOrCreate(['code' => 'bpjs'], ['name' => 'BPJS', 'is_active' => true]);
        $materai = TransactionType::firstOrCreate(['code' => 'materai'], ['name' => 'Materai', 'is_active' => true]);
        $bayarSpp = TransactionType::firstOrCreate(['code' => 'bayar_spp'], ['name' => 'Bayar SPP', 'is_active' => true]);

        // 3. Nasabah (Customers)
        $customersData = [
            ['type' => $setorBni, 'identifier' => '0234567891', 'name' => 'H. Suherman'],
            ['type' => $setorBni, 'identifier' => '0298765432', 'name' => 'Koperasi Sekolah SMKN 46'],
            ['type' => $tarikBni, 'identifier' => '0211223344', 'name' => 'Rina Astuti'],
            ['type' => $setorAntarBank, 'identifier' => '5432109876', 'name' => 'Bambang Irawan'],
            ['type' => $plnPrepaid, 'identifier' => '14234567890', 'name' => 'Bu Sumiati'],
            ['type' => $pulsaTsel, 'identifier' => '081298765432', 'name' => 'Fajar Nugraha'],
            ['type' => $topupDana, 'identifier' => '085712345678', 'name' => 'Rizky Alamsyah'],
            ['type' => $topupGopay, 'identifier' => '087855443322', 'name' => 'Maya Tri Lestari'],
            ['type' => $bpjs, 'identifier' => '0001234567890', 'name' => 'Supriyadi'],
            ['type' => $materai, 'identifier' => 'CASH-001', 'name' => 'Warga / Pembeli Materai'],
            ['type' => $bayarSpp, 'identifier' => '9884456512511177', 'name' => 'Fajar Pratama (Siswa)'],
        ];

        $customers = [];
        foreach ($customersData as $cd) {
            $customers[] = Customer::firstOrCreate(
                [
                    'transaction_type_id' => $cd['type']->id,
                    'customer_identifier' => $cd['identifier'],
                ],
                [
                    'customer_name' => $cd['name'],
                    'last_edited_by' => $kasirs[0]->id,
                ]
            );
        }

        // 4. Data Dummy Transaksi (Laporan Transaksi)
        $transactionsData = [
            [
                'days_ago' => 0,
                'customer' => $customers[0],
                'type' => $setorBni,
                'amount' => 1500000,
                'admin_fee' => 3000,
                'kasir' => $kasirs[0],
                'notes' => 'Setoran tabungan pribadi',
            ],
            [
                'days_ago' => 0,
                'customer' => $customers[4],
                'type' => $plnPrepaid,
                'amount' => 100000,
                'admin_fee' => 2500,
                'kasir' => $kasirs[0],
                'notes' => 'Token listrik rumah',
            ],
            [
                'days_ago' => 0,
                'customer' => $customers[6],
                'type' => $topupDana,
                'amount' => 50000,
                'admin_fee' => 2000,
                'kasir' => $kasirs[1],
                'notes' => 'Topup saldo Dana',
            ],
            [
                'days_ago' => 1,
                'customer' => $customers[1],
                'type' => $setorBni,
                'amount' => 3200000,
                'admin_fee' => 5000,
                'kasir' => $kasirs[1],
                'notes' => 'Setoran harian kantin/koperasi',
            ],
            [
                'days_ago' => 1,
                'customer' => $customers[2],
                'type' => $tarikBni,
                'amount' => 500000,
                'admin_fee' => 4000,
                'kasir' => $kasirs[2],
                'notes' => 'Penarikan tunai',
            ],
            [
                'days_ago' => 1,
                'customer' => $customers[9],
                'type' => $materai,
                'amount' => 20000,
                'admin_fee' => 0,
                'kasir' => $kasirs[2],
                'notes' => 'Pembelian 2 pcs materai',
            ],
            [
                'days_ago' => 2,
                'customer' => $customers[3],
                'type' => $setorAntarBank,
                'amount' => 750000,
                'admin_fee' => 6500,
                'kasir' => $kasirs[0],
                'notes' => 'Transfer ke Bank Mandiri',
            ],
            [
                'days_ago' => 2,
                'customer' => $customers[5],
                'type' => $pulsaTsel,
                'amount' => 25000,
                'admin_fee' => 2000,
                'kasir' => $kasirs[3],
                'notes' => 'Pulsa reguler Telkomsel',
            ],
            [
                'days_ago' => 3,
                'customer' => $customers[8],
                'type' => $bpjs,
                'amount' => 105000,
                'admin_fee' => 2500,
                'kasir' => $kasirs[2],
                'notes' => 'Iuran BPJS Kesehatan kelas 2',
            ],
            [
                'days_ago' => 3,
                'customer' => $customers[7],
                'type' => $topupGopay,
                'amount' => 200000,
                'admin_fee' => 2000,
                'kasir' => $kasirs[1],
                'notes' => 'Topup GoPay',
            ],
            [
                'days_ago' => 4,
                'customer' => $customers[0],
                'type' => $setorBni,
                'amount' => 2000000,
                'admin_fee' => 3000,
                'kasir' => $kasirs[0],
                'notes' => 'Setoran usaha',
            ],
            [
                'days_ago' => 5,
                'customer' => $customers[2],
                'type' => $tarikBni,
                'amount' => 300000,
                'admin_fee' => 4000,
                'kasir' => $kasirs[3],
                'notes' => 'Tarik tunai keperluan sekolah',
            ],
            [
                'days_ago' => 6,
                'customer' => $customers[4],
                'type' => $plnPrepaid,
                'amount' => 50000,
                'admin_fee' => 2500,
                'kasir' => $kasirs[1],
                'notes' => 'Token PLN 50k',
            ],
            [
                'days_ago' => 7,
                'customer' => $customers[1],
                'type' => $setorBni,
                'amount' => 4500000,
                'admin_fee' => 5000,
                'kasir' => $kasirs[2],
                'notes' => 'Setoran omzet mingguan',
            ],
        ];

        Transaction::query()->delete();

        $runningNumber = 1001;
        $runningBalance = 5000000;
        foreach ($transactionsData as $td) {
            $total = $td['amount'] + $td['admin_fee'];
            $runningBalance += ($td['type']->name === 'Tarik Tunai' ? -$td['amount'] : $td['amount']);

            Transaction::create([
                'transaction_number' => $runningNumber++,
                'transaction_date' => Carbon::now()->subDays($td['days_ago'])->toDateString(),
                'transaction_type_id' => $td['type']->id,
                'customer_id' => $td['customer']->id,
                'amount' => $td['amount'],
                'admin_fee' => $td['admin_fee'],
                'total_payment' => $total,
                'bni_balance_after' => $runningBalance,
                'cashier_id' => $td['kasir']->id,
                'notes' => $td['notes'],
                'created_at' => Carbon::now()->subDays($td['days_ago'])->setHour(rand(8, 15))->setMinute(rand(0, 59)),
                'updated_at' => Carbon::now()->subDays($td['days_ago'])->setHour(rand(8, 15))->setMinute(rand(0, 59)),
            ]);
        }

        // 5. Data Dummy Presensi (Rekap Presensi)
        StaffAttendance::query()->delete();

        $attendanceLogs = [
            [
                'days_ago' => 0,
                'user' => $kasirs[0],
                'student_note' => 'Shift pagi lancar, melayani 3 transaksi. Kas dan saldo BNI balance.',
                'teacher_note' => 'Bagus, pertahankan ketelitian dan kebersihan konter.',
            ],
            [
                'days_ago' => 0,
                'user' => $kasirs[1],
                'student_note' => 'Shift siang ramai, setor tunai lancar tanpa kendala printer.',
                'teacher_note' => null,
            ],
            [
                'days_ago' => 1,
                'user' => $kasirs[1],
                'student_note' => 'Bertugas shift pagi, rekapitulasi setoran koperasi cocok.',
                'teacher_note' => 'Sudah diperiksa, laporan fisik sesuai.',
            ],
            [
                'days_ago' => 1,
                'user' => $kasirs[2],
                'student_note' => 'Shift siang, ada kendala koneksi sebentar pukul 13.15 tapi segera teratasi.',
                'teacher_note' => 'Tanggap mengatasi masalah jaringan. Sangat baik.',
            ],
            [
                'days_ago' => 2,
                'user' => $kasirs[0],
                'student_note' => 'Shift pagi berjalan normal. 4 nasabah dilayani.',
                'teacher_note' => 'Verifikasi selesai, catatan pembukuan rapi.',
            ],
            [
                'days_ago' => 2,
                'user' => $kasirs[3],
                'student_note' => 'Shift siang lancar, stok materai terdata aman.',
                'teacher_note' => 'Kerja bagus.',
            ],
            [
                'days_ago' => 3,
                'user' => $kasirs[2],
                'student_note' => 'Shift pagi, pembayaran BPJS dan Topup e-wallet lancar.',
                'teacher_note' => 'Pelayanan ramah dan cepat, tingkatkan terus.',
            ],
            [
                'days_ago' => 3,
                'user' => $kasirs[1],
                'student_note' => 'Shift siang bertugas sampai pukul 15.00, tutup kas tepat waktu.',
                'teacher_note' => 'Selesai tepat waktu, rekapitulasi klop.',
            ],
            [
                'days_ago' => 4,
                'user' => $kasirs[0],
                'student_note' => 'Shift pagi, nasabah setor tunai nominal besar sudah dihitung ulang.',
                'teacher_note' => 'Ketelitian penghitungan uang tunai sangat baik.',
            ],
            [
                'days_ago' => 5,
                'user' => $kasirs[3],
                'student_note' => 'Shift siang, mencatat transaksi penarikan dana.',
                'teacher_note' => 'Catatan lengkap.',
            ],
        ];

        foreach ($attendanceLogs as $att) {
            StaffAttendance::create([
                'attendance_date' => Carbon::now()->subDays($att['days_ago'])->toDateString(),
                'user_id' => $att['user']->id,
                'student_note' => $att['student_note'],
                'teacher_note' => $att['teacher_note'],
                'created_at' => Carbon::now()->subDays($att['days_ago'])->setHour(rand(7, 16)),
                'updated_at' => Carbon::now()->subDays($att['days_ago'])->setHour(rand(7, 16)),
            ]);
        }

        // 6. Data Dummy Rekap Harian (EOD)
        DailyRecap::query()->delete();

        $adminUser = User::whereRelation('role', 'name', 'Admin')->first();

        $eodData = [
            [
                'days_ago' => 0,
                'recorded_by' => $kasirs[0]->id,
                'verified_by' => null,
                'bni_balance_remaining' => 4250000,
                'cash_amount' => 1655000,
                'stamp_remaining_value' => 45,
                'total' => 5905000,
                'notes' => 'Uang fisik di laci pas Rp 1.655.000, saldo mutasi rekening cocok.',
            ],
            [
                'days_ago' => 1,
                'recorded_by' => $kasirs[1]->id,
                'verified_by' => null,
                'bni_balance_remaining' => 3705000,
                'cash_amount' => 3724000,
                'stamp_remaining_value' => 47,
                'total' => 7429000,
                'notes' => 'Ada setoran tunai koperasi sekolah, kas fisik dihitung 2 kali.',
            ],
            [
                'days_ago' => 2,
                'recorded_by' => $kasirs[2]->id,
                'verified_by' => $adminUser?->id,
                'bni_balance_remaining' => 2950000,
                'cash_amount' => 1250000,
                'stamp_remaining_value' => 49,
                'total' => 4200000,
                'notes' => 'Tutup kas lancar | Catatan Guru: Rekonsiliasi selesai, kas fisik cocok.',
            ],
            [
                'days_ago' => 3,
                'recorded_by' => $kasirs[1]->id,
                'verified_by' => $adminUser?->id,
                'bni_balance_remaining' => 3100000,
                'cash_amount' => 950000,
                'stamp_remaining_value' => 50,
                'total' => 4050000,
                'notes' => 'Semua transaksi tercatat sesuai mutasi | Catatan Guru: Diverifikasi tepat waktu.',
            ],
            [
                'days_ago' => 4,
                'recorded_by' => $kasirs[0]->id,
                'verified_by' => $adminUser?->id,
                'bni_balance_remaining' => 2450000,
                'cash_amount' => 2100000,
                'stamp_remaining_value' => 50,
                'total' => 4550000,
                'notes' => 'Setoran usaha H. Suherman sudah masuk mutasi | Catatan Guru: Sangat teliti.',
            ],
        ];

        foreach ($eodData as $eod) {
            DailyRecap::create([
                'recap_date' => Carbon::now()->subDays($eod['days_ago'])->toDateString(),
                'bni_balance_remaining' => $eod['bni_balance_remaining'],
                'cash_amount' => $eod['cash_amount'],
                'stamp_remaining_value' => $eod['stamp_remaining_value'],
                'total' => $eod['total'],
                'notes' => $eod['notes'],
                'recorded_by' => $eod['recorded_by'],
                'verified_by' => $eod['verified_by'],
                'created_at' => Carbon::now()->subDays($eod['days_ago'])->setHour(16)->setMinute(15),
                'updated_at' => Carbon::now()->subDays($eod['days_ago'])->setHour(16)->setMinute(30),
            ]);
        }
    }
}
