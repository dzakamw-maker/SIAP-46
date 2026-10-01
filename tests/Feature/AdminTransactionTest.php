<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Role;
use App\Models\StampDutyRecord;
use App\Models\Transaction;
use App\Models\TransactionType;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTransactionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(PreventRequestForgery::class);
    }

    private function createAdminUser(): User
    {
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);

        return User::create([
            'role_id' => $adminRole->id,
            'full_name' => 'Admin Guru',
            'username' => 'admin_guru',
            'password' => 'secret123',
            'is_active' => true,
        ]);
    }

    private function createCashierUser(): User
    {
        $kasirRole = Role::firstOrCreate(['name' => 'Kasir']);

        return User::create([
            'role_id' => $kasirRole->id,
            'full_name' => 'Kasir Siswa',
            'username' => 'kasir_siswa',
            'password' => 'secret123',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_view_transactions_list(): void
    {
        $admin = $this->createAdminUser();
        $cashier = $this->createCashierUser();
        $type = TransactionType::firstOrCreate(
            ['code' => 'setor_tunai_bni'],
            ['name' => 'Setor Tunai BNI', 'is_active' => true]
        );

        $customer = Customer::create([
            'transaction_type_id' => $type->id,
            'customer_identifier' => '12511292',
            'customer_name' => 'Dzaka Testing',
        ]);

        Transaction::create([
            'transaction_number' => 1,
            'transaction_date' => today(),
            'transaction_type_id' => $type->id,
            'customer_id' => $customer->id,
            'amount' => 50000,
            'admin_fee' => 1000,
            'total_payment' => 51000,
            'cashier_id' => $cashier->id,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.transactions'));

        $response->assertStatus(200);
        $response->assertSee('#1');
        $response->assertSee('Dzaka Testing');
        $response->assertSee('Setor Tunai BNI');
        $response->assertSee('Hapus');
    }

    public function test_admin_can_delete_transaction_and_cleans_up_unused_customer(): void
    {
        $admin = $this->createAdminUser();
        $cashier = $this->createCashierUser();
        $type = TransactionType::firstOrCreate(
            ['code' => 'topup_dana'],
            ['name' => 'TOPUP-DANA', 'is_active' => true]
        );

        $customer = Customer::create([
            'transaction_type_id' => $type->id,
            'customer_identifier' => '08123456789',
            'customer_name' => 'Budi Dana',
        ]);

        $trx = Transaction::create([
            'transaction_number' => 1,
            'transaction_date' => today(),
            'transaction_type_id' => $type->id,
            'customer_id' => $customer->id,
            'amount' => 20000,
            'admin_fee' => 1000,
            'total_payment' => 21000,
            'cashier_id' => $cashier->id,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.transactions.destroy', $trx->id));

        $response->assertRedirect(route('admin.transactions'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('transactions', ['id' => $trx->id]);
        // Customer should also be cleaned up since 0 transactions remain
        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }

    public function test_admin_deleting_transaction_retains_customer_if_other_transactions_exist(): void
    {
        $admin = $this->createAdminUser();
        $cashier = $this->createCashierUser();
        $type = TransactionType::firstOrCreate(
            ['code' => 'topup_dana'],
            ['name' => 'TOPUP-DANA', 'is_active' => true]
        );

        $customer = Customer::create([
            'transaction_type_id' => $type->id,
            'customer_identifier' => '08123456789',
            'customer_name' => 'Budi Dana',
        ]);

        $trx1 = Transaction::create([
            'transaction_number' => 1,
            'transaction_date' => today(),
            'transaction_type_id' => $type->id,
            'customer_id' => $customer->id,
            'amount' => 20000,
            'admin_fee' => 1000,
            'total_payment' => 21000,
            'cashier_id' => $cashier->id,
        ]);

        $trx2 = Transaction::create([
            'transaction_number' => 2,
            'transaction_date' => today(),
            'transaction_type_id' => $type->id,
            'customer_id' => $customer->id,
            'amount' => 50000,
            'admin_fee' => 1000,
            'total_payment' => 51000,
            'cashier_id' => $cashier->id,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.transactions.destroy', $trx1->id));

        $response->assertRedirect(route('admin.transactions'));
        $this->assertDatabaseMissing('transactions', ['id' => $trx1->id]);
        $this->assertDatabaseHas('transactions', ['id' => $trx2->id]);
        // Customer should still exist
        $this->assertDatabaseHas('customers', ['id' => $customer->id]);
    }

    public function test_admin_deleting_materai_transaction_refunds_stamp_stock(): void
    {
        $admin = $this->createAdminUser();
        $cashier = $this->createCashierUser();
        $type = TransactionType::firstOrCreate(
            ['code' => 'materai'],
            ['name' => 'Materai', 'is_active' => true]
        );

        StampDutyRecord::create([
            'record_date' => today(),
            'quantity_sold' => 1,
            'quantity_purchased' => 0,
            'remaining_stock' => 9,
            'amount' => 10000,
        ]);

        $customer = Customer::create([
            'transaction_type_id' => $type->id,
            'customer_identifier' => 'SISWA-1',
            'customer_name' => 'Siswa Pembeli',
        ]);

        $trx = Transaction::create([
            'transaction_number' => 1,
            'transaction_date' => today(),
            'transaction_type_id' => $type->id,
            'customer_id' => $customer->id,
            'amount' => 10000,
            'admin_fee' => 0,
            'total_payment' => 10000,
            'cashier_id' => $cashier->id,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.transactions.destroy', $trx->id));

        $response->assertRedirect(route('admin.transactions'));
        $this->assertDatabaseMissing('transactions', ['id' => $trx->id]);

        $latestStamp = StampDutyRecord::latest('id')->first();
        $this->assertEquals(10, $latestStamp->remaining_stock);
    }

    public function test_cashier_cannot_delete_transaction(): void
    {
        $cashier = $this->createCashierUser();
        $type = TransactionType::firstOrCreate(
            ['code' => 'topup_dana'],
            ['name' => 'TOPUP-DANA', 'is_active' => true]
        );

        $customer = Customer::create([
            'transaction_type_id' => $type->id,
            'customer_identifier' => '08123456789',
            'customer_name' => 'Budi Dana',
        ]);

        $trx = Transaction::create([
            'transaction_number' => 1,
            'transaction_date' => today(),
            'transaction_type_id' => $type->id,
            'customer_id' => $customer->id,
            'amount' => 20000,
            'admin_fee' => 1000,
            'total_payment' => 21000,
            'cashier_id' => $cashier->id,
        ]);

        $response = $this->actingAs($cashier)->delete(route('admin.transactions.destroy', $trx->id));

        // Cashier role should be denied (403) from admin transaction deletion
        $response->assertStatus(403);
        $this->assertDatabaseHas('transactions', ['id' => $trx->id]);
    }

    public function test_admin_dashboard_displays_recent_transactions_from_database(): void
    {
        $admin = $this->createAdminUser();
        $cashier = $this->createCashierUser();
        $type = TransactionType::firstOrCreate(
            ['code' => 'setor_tunai_bni'],
            ['name' => 'Setor Tunai BNI', 'is_active' => true]
        );

        $customer = Customer::create([
            'transaction_type_id' => $type->id,
            'customer_identifier' => '99887766',
            'customer_name' => 'Ahmad Sujatmiko',
        ]);

        Transaction::create([
            'transaction_number' => 1,
            'transaction_date' => today(),
            'transaction_type_id' => $type->id,
            'customer_id' => $customer->id,
            'amount' => 750000,
            'admin_fee' => 2000,
            'total_payment' => 752000,
            'cashier_id' => $cashier->id,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Transaksi Terbaru');
        $response->assertSee('Ahmad Sujatmiko');
        $response->assertSee('Setor Tunai BNI');
        $response->assertSee('Rp 750.000');
        $response->assertSee('Selesai');
    }
}
