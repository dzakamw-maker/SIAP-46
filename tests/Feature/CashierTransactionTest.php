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

class CashierTransactionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(PreventRequestForgery::class);
    }

    private function createCashierUser(): User
    {
        $kasirRole = Role::firstOrCreate(['name' => 'Kasir']);

        return User::create([
            'role_id' => $kasirRole->id,
            'full_name' => 'Kasir Siswa',
            'username' => 'kasir_test',
            'password' => 'secret123',
            'is_active' => true,
        ]);
    }

    public function test_cashier_can_view_dashboard(): void
    {
        $cashier = $this->createCashierUser();
        $type = TransactionType::firstOrCreate(
            ['code' => 'setor_tunai_bni'],
            ['name' => 'Setor Tunai BNI', 'is_active' => true]
        );

        $response = $this->actingAs($cashier)->get(route('kasir.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Input Transaksi Baru');
        $response->assertSee($type->name);
    }

    public function test_customer_search_returns_filtered_suggestions_by_transaction_type(): void
    {
        $cashier = $this->createCashierUser();

        $type1 = TransactionType::firstOrCreate(
            ['code' => 'pln_prepaid'],
            ['name' => 'PLN- PREPAID', 'is_active' => true]
        );
        $type2 = TransactionType::firstOrCreate(
            ['code' => 'topup_dana'],
            ['name' => 'TOPUP-DANA', 'is_active' => true]
        );

        Customer::create([
            'transaction_type_id' => $type1->id,
            'customer_identifier' => '14123456789',
            'customer_name' => 'Bapak Joko PLN',
            'is_default' => true,
        ]);

        Customer::create([
            'transaction_type_id' => $type2->id,
            'customer_identifier' => '08123456789',
            'customer_name' => 'Joko DANA',
            'is_default' => true,
        ]);

        // Customer yang bukan default (misal transaksi kirim uang sekali pakai)
        Customer::create([
            'transaction_type_id' => $type1->id,
            'customer_identifier' => '14999999999',
            'customer_name' => 'Joko Non Default',
            'is_default' => false,
        ]);

        // Search for type 1 (PLN) with query 'Joko' -> only default returns
        $response = $this->actingAs($cashier)->getJson(route('kasir.customers.search', [
            'transaction_type_id' => $type1->id,
            'q' => 'Joko',
        ]));

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment([
            'customer_identifier' => '14123456789',
            'customer_name' => 'Bapak Joko PLN',
        ]);
        $response->assertJsonMissing([
            'customer_identifier' => '14999999999',
        ]);

        // Search by identifier
        $response2 = $this->actingAs($cashier)->getJson(route('kasir.customers.search', [
            'transaction_type_id' => $type1->id,
            'q' => '14123',
        ]));

        $response2->assertStatus(200);
        $response2->assertJsonCount(1);
        $response2->assertJsonFragment([
            'customer_identifier' => '14123456789',
        ]);
    }

    public function test_customer_search_returns_empty_when_query_is_empty(): void
    {
        $cashier = $this->createCashierUser();
        $type = TransactionType::firstOrCreate(
            ['code' => 'pln_prepaid'],
            ['name' => 'PLN- PREPAID', 'is_active' => true]
        );

        Customer::create([
            'transaction_type_id' => $type->id,
            'customer_identifier' => '14123456789',
            'customer_name' => 'Bapak Joko PLN',
        ]);

        $response = $this->actingAs($cashier)->getJson(route('kasir.customers.search', [
            'transaction_type_id' => $type->id,
            'q' => '',
        ]));

        $response->assertStatus(200);
        $response->assertExactJson([]);
    }

    public function test_cashier_can_store_transaction_and_creates_new_customer(): void
    {
        $cashier = $this->createCashierUser();
        $type = TransactionType::firstOrCreate(
            ['code' => 'setor_tunai_bni'],
            ['name' => 'Setor Tunai BNI', 'is_active' => true]
        );

        $response = $this->actingAs($cashier)->post(route('kasir.transactions.store'), [
            'transaction_type_id' => $type->id,
            'customer_identifier' => '00987654321',
            'customer_name' => 'Siti Aisyah',
            'amount' => 150000,
            'admin_fee' => 2500,
            'notes' => 'Setor uang saku',
        ]);

        $response->assertRedirect(route('kasir.dashboard'));
        $response->assertSessionHas('success');

        // Customer created
        $this->assertDatabaseHas('customers', [
            'transaction_type_id' => $type->id,
            'customer_identifier' => '00987654321',
            'customer_name' => 'Siti Aisyah',
        ]);

        // Transaction created
        $this->assertDatabaseHas('transactions', [
            'transaction_type_id' => $type->id,
            'amount' => 150000,
            'admin_fee' => 2500,
            'total_payment' => 152500,
            'cashier_id' => $cashier->id,
            'notes' => 'Setor uang saku',
        ]);
    }

    public function test_cashier_stores_transaction_with_existing_customer_updates_name_and_reuses_record(): void
    {
        $cashier = $this->createCashierUser();
        $type = TransactionType::firstOrCreate(
            ['code' => 'topup_dana'],
            ['name' => 'TOPUP-DANA', 'is_active' => true]
        );

        $existingCustomer = Customer::create([
            'transaction_type_id' => $type->id,
            'customer_identifier' => '081299998888',
            'customer_name' => 'Ahmad Lama',
        ]);

        $response = $this->actingAs($cashier)->post(route('kasir.transactions.store'), [
            'transaction_type_id' => $type->id,
            'customer_identifier' => '081299998888',
            'customer_name' => 'Ahmad Baru',
            'amount' => 50000,
            'admin_fee' => 2000,
        ]);

        $response->assertRedirect(route('kasir.dashboard'));

        $this->assertEquals(1, Customer::where('transaction_type_id', $type->id)->where('customer_identifier', '081299998888')->count());
        $existingCustomer->refresh();
        $this->assertEquals('Ahmad Baru', $existingCustomer->customer_name);

        $this->assertDatabaseHas('transactions', [
            'customer_id' => $existingCustomer->id,
            'amount' => 50000,
            'total_payment' => 52000,
        ]);
    }

    public function test_materai_transaction_decrements_stamp_stock(): void
    {
        $cashier = $this->createCashierUser();
        $type = TransactionType::firstOrCreate(
            ['code' => 'materai'],
            ['name' => 'Materai', 'is_active' => true]
        );

        // Initial stock 10
        StampDutyRecord::create([
            'record_date' => today(),
            'quantity_sold' => 0,
            'quantity_purchased' => 10,
            'remaining_stock' => 10,
            'amount' => 100000,
        ]);

        $response = $this->actingAs($cashier)->post(route('kasir.transactions.store'), [
            'transaction_type_id' => $type->id,
            'customer_identifier' => 'SISWA-001',
            'customer_name' => 'Rina Siswa',
            'amount' => 10000,
            'admin_fee' => 0,
        ]);

        $response->assertRedirect(route('kasir.dashboard'));

        $latestRecord = StampDutyRecord::latest('id')->first();
        $this->assertEquals(9, $latestRecord->remaining_stock);
        $this->assertEquals(1, $latestRecord->quantity_sold);
    }

    public function test_materai_transaction_fails_if_stock_is_zero(): void
    {
        $cashier = $this->createCashierUser();
        $type = TransactionType::firstOrCreate(
            ['code' => 'materai'],
            ['name' => 'Materai', 'is_active' => true]
        );

        // No stock or remaining_stock = 0
        StampDutyRecord::create([
            'record_date' => today(),
            'quantity_sold' => 0,
            'quantity_purchased' => 0,
            'remaining_stock' => 0,
            'amount' => 0,
        ]);

        $response = $this->actingAs($cashier)->post(route('kasir.transactions.store'), [
            'transaction_type_id' => $type->id,
            'customer_identifier' => 'SISWA-001',
            'customer_name' => 'Rina Siswa',
            'amount' => 10000,
            'admin_fee' => 0,
        ]);

        $response->assertSessionHasErrors(['transaction_type_id']);
        $this->assertEquals(0, Transaction::count());
    }

    public function test_check_customer_default_returns_requires_verification_when_no_default_exists(): void
    {
        $cashier = $this->createCashierUser();
        $type = TransactionType::firstOrCreate(
            ['code' => 'setor_tunai_bni'],
            ['name' => 'Setor Tunai BNI', 'is_active' => true]
        );

        $response = $this->actingAs($cashier)->getJson(route('kasir.customers.check-default', [
            'transaction_type_id' => $type->id,
            'customer_name' => 'Dzaka Baru',
            'customer_identifier' => '12511292',
        ]));

        $response->assertStatus(200);
        $response->assertJson([
            'has_default' => false,
            'requires_verification' => true,
        ]);
    }

    public function test_check_customer_default_returns_not_requires_verification_when_default_exists(): void
    {
        $cashier = $this->createCashierUser();
        $type = TransactionType::firstOrCreate(
            ['code' => 'setor_tunai_bni'],
            ['name' => 'Setor Tunai BNI', 'is_active' => true]
        );

        Customer::create([
            'transaction_type_id' => $type->id,
            'customer_identifier' => '12511292',
            'customer_name' => 'Dzaka Tetap',
            'is_default' => true,
        ]);

        $response = $this->actingAs($cashier)->getJson(route('kasir.customers.check-default', [
            'transaction_type_id' => $type->id,
            'customer_name' => 'Dzaka Tetap',
            'customer_identifier' => '12511292',
        ]));

        $response->assertStatus(200);
        $response->assertJson([
            'has_default' => true,
            'default_identifier' => '12511292',
            'is_current_default' => true,
            'requires_verification' => false,
        ]);
    }

    public function test_cashier_can_save_transaction_as_default_customer(): void
    {
        $cashier = $this->createCashierUser();
        $type = TransactionType::firstOrCreate(
            ['code' => 'setor_tunai_bni'],
            ['name' => 'Setor Tunai BNI', 'is_active' => true]
        );

        $response = $this->actingAs($cashier)->post(route('kasir.transactions.store'), [
            'transaction_type_id' => $type->id,
            'customer_identifier' => '12511292',
            'customer_name' => 'Dzaka Default',
            'is_default' => '1',
            'amount' => 50000,
            'admin_fee' => 1000,
        ]);

        $response->assertRedirect(route('kasir.dashboard'));

        $this->assertDatabaseHas('customers', [
            'transaction_type_id' => $type->id,
            'customer_identifier' => '12511292',
            'customer_name' => 'Dzaka Default',
            'is_default' => true,
        ]);
    }

    public function test_cashier_can_save_transaction_as_non_default_customer(): void
    {
        $cashier = $this->createCashierUser();
        $type = TransactionType::firstOrCreate(
            ['code' => 'setor_tunai_bni'],
            ['name' => 'Setor Tunai BNI', 'is_active' => true]
        );

        $response = $this->actingAs($cashier)->post(route('kasir.transactions.store'), [
            'transaction_type_id' => $type->id,
            'customer_identifier' => '99998888',
            'customer_name' => 'Budi Sekali Saja',
            'is_default' => '0',
            'amount' => 50000,
            'admin_fee' => 1000,
        ]);

        $response->assertRedirect(route('kasir.dashboard'));

        $this->assertDatabaseHas('customers', [
            'transaction_type_id' => $type->id,
            'customer_identifier' => '99998888',
            'customer_name' => 'Budi Sekali Saja',
            'is_default' => false,
        ]);

        // Autocomplete search should NOT return non-default customer
        $searchResponse = $this->actingAs($cashier)->getJson(route('kasir.customers.search', [
            'transaction_type_id' => $type->id,
            'q' => 'Budi',
        ]));
        $searchResponse->assertStatus(200);
        $searchResponse->assertJsonCount(0);
    }

    public function test_customer_with_default_sending_money_to_another_id_preserves_default(): void
    {
        $cashier = $this->createCashierUser();
        $type = TransactionType::firstOrCreate(
            ['code' => 'setor_tunai_bni'],
            ['name' => 'Setor Tunai BNI', 'is_active' => true]
        );

        // Budi has default ID '11111111'
        $defaultCustomer = Customer::create([
            'transaction_type_id' => $type->id,
            'customer_identifier' => '11111111',
            'customer_name' => 'Budi Utama',
            'is_default' => true,
        ]);

        // Budi sends money to someone else: '22222222', with is_default = '0'
        $response = $this->actingAs($cashier)->post(route('kasir.transactions.store'), [
            'transaction_type_id' => $type->id,
            'customer_identifier' => '22222222',
            'customer_name' => 'Budi Utama',
            'is_default' => '0',
            'amount' => 100000,
            'admin_fee' => 2000,
        ]);

        $response->assertRedirect(route('kasir.dashboard'));

        // Default remains 11111111
        $defaultCustomer->refresh();
        $this->assertTrue($defaultCustomer->is_default);

        // 22222222 is saved as non-default
        $this->assertDatabaseHas('customers', [
            'transaction_type_id' => $type->id,
            'customer_identifier' => '22222222',
            'customer_name' => 'Budi Utama',
            'is_default' => false,
        ]);

        // Searching 'Budi' still only suggests his default '11111111'
        $search = $this->actingAs($cashier)->getJson(route('kasir.customers.search', [
            'transaction_type_id' => $type->id,
            'q' => 'Budi',
        ]));
        $search->assertJsonCount(1);
        $search->assertJsonFragment([
            'customer_identifier' => '11111111',
        ]);
        $search->assertJsonMissing([
            'customer_identifier' => '22222222',
        ]);
    }

    public function test_cashier_can_view_stamps_page(): void
    {
        $cashier = $this->createCashierUser();

        StampDutyRecord::create([
            'record_date' => today(),
            'quantity_sold' => 0,
            'quantity_purchased' => 25,
            'remaining_stock' => 25,
            'amount' => 250000,
            'cashier_id' => $cashier->id,
            'notes' => 'Restock awal',
        ]);

        $response = $this->actingAs($cashier)->get(route('kasir.stamps'));

        $response->assertStatus(200);
        $response->assertSee('Restock Materai');
        $response->assertSee('25');
        $response->assertSee('Restock awal');
    }

    public function test_cashier_can_restock_stamps_successfully(): void
    {
        $cashier = $this->createCashierUser();

        // Previous stock 10
        StampDutyRecord::create([
            'record_date' => today(),
            'quantity_sold' => 0,
            'quantity_purchased' => 10,
            'remaining_stock' => 10,
            'amount' => 100000,
            'cashier_id' => $cashier->id,
        ]);

        $response = $this->actingAs($cashier)->post(route('kasir.stamps.store'), [
            'record_date' => today()->format('Y-m-d'),
            'quantity_purchased' => 50,
            'amount' => 500000,
            'notes' => 'Beli dari kantor pos pusat',
        ]);

        $response->assertRedirect(route('kasir.stamps'));
        $response->assertSessionHas('success');

        $latestRecord = StampDutyRecord::latest('id')->first();
        $this->assertEquals(60, $latestRecord->remaining_stock);
        $this->assertEquals(50, $latestRecord->quantity_purchased);
        $this->assertEquals(0, $latestRecord->quantity_sold);
        $this->assertEquals('Beli dari kantor pos pusat', $latestRecord->notes);
        $this->assertEquals($cashier->id, $latestRecord->cashier_id);
    }

    public function test_cashier_restock_stamps_validation_errors(): void
    {
        $cashier = $this->createCashierUser();

        $response = $this->actingAs($cashier)->post(route('kasir.stamps.store'), [
            'record_date' => '',
            'quantity_purchased' => 0,
            'amount' => -1000,
        ]);

        $response->assertSessionHasErrors(['record_date', 'quantity_purchased', 'amount']);
    }

    public function test_materai_transaction_without_customer_identifier_succeeds_and_generates_identifier(): void
    {
        $cashier = $this->createCashierUser();
        $type = TransactionType::firstOrCreate(
            ['code' => 'materai'],
            ['name' => 'Materai', 'is_active' => true]
        );

        StampDutyRecord::create([
            'record_date' => today(),
            'quantity_sold' => 0,
            'quantity_purchased' => 5,
            'remaining_stock' => 5,
            'amount' => 50000,
        ]);

        $response = $this->actingAs($cashier)->post(route('kasir.transactions.store'), [
            'transaction_type_id' => $type->id,
            'customer_name' => 'Pembeli Materai Langsung',
            'amount' => 10000,
            // customer_identifier & admin_fee omitted
        ]);

        $response->assertRedirect(route('kasir.dashboard'));
        $response->assertSessionHas('success');

        $transaction = Transaction::latest('id')->first();
        $this->assertEquals(0, $transaction->admin_fee);
        $this->assertEquals(10000, $transaction->total_payment);
        $this->assertStringStartsWith('MTR-', $transaction->customer->customer_identifier);
        $this->assertEquals('Pembeli Materai Langsung', $transaction->customer->customer_name);
    }

    public function test_non_materai_transaction_requires_customer_identifier(): void
    {
        $cashier = $this->createCashierUser();
        $type = TransactionType::firstOrCreate(
            ['code' => 'pln_prepaid'],
            ['name' => 'PLN- PREPAID', 'is_active' => true]
        );

        $response = $this->actingAs($cashier)->post(route('kasir.transactions.store'), [
            'transaction_type_id' => $type->id,
            'customer_name' => 'Pelanggan PLN',
            'amount' => 50000,
            // customer_identifier omitted
        ]);

        $response->assertSessionHasErrors(['customer_identifier']);
    }

    public function test_transaction_with_omitted_admin_fee_defaults_to_zero(): void
    {
        $cashier = $this->createCashierUser();
        $type = TransactionType::firstOrCreate(
            ['code' => 'topup_dana'],
            ['name' => 'TOPUP-DANA', 'is_active' => true]
        );

        $response = $this->actingAs($cashier)->post(route('kasir.transactions.store'), [
            'transaction_type_id' => $type->id,
            'customer_identifier' => '081234567890',
            'customer_name' => 'Pelanggan Dana',
            'amount' => 20000,
            // admin_fee omitted
        ]);

        $response->assertRedirect(route('kasir.dashboard'));
        $transaction = Transaction::latest('id')->first();
        $this->assertEquals(0, $transaction->admin_fee);
        $this->assertEquals(20000, $transaction->total_payment);
    }

    public function test_materai_transaction_with_quantity_and_default_price(): void
    {
        $cashier = $this->createCashierUser();
        $type = TransactionType::firstOrCreate(
            ['code' => 'materai'],
            ['name' => 'Materai', 'is_active' => true]
        );

        StampDutyRecord::create([
            'record_date' => today(),
            'quantity_sold' => 0,
            'quantity_purchased' => 10,
            'remaining_stock' => 10,
            'amount' => 100000,
        ]);

        $response = $this->actingAs($cashier)->post(route('kasir.transactions.store'), [
            'transaction_type_id' => $type->id,
            'customer_name' => 'Pembeli 3 Materai',
            'stamp_quantity' => 3,
            // stamp_price omitted -> defaults to 11.000
        ]);

        $response->assertRedirect(route('kasir.dashboard'));
        $response->assertSessionHas('success');

        $transaction = Transaction::latest('id')->first();
        $this->assertEquals(0, $transaction->admin_fee);
        $this->assertEquals(33000, $transaction->amount);
        $this->assertEquals(33000, $transaction->total_payment);

        $stampRecord = StampDutyRecord::latest('id')->first();
        $this->assertEquals(3, $stampRecord->quantity_sold);
        $this->assertEquals(7, $stampRecord->remaining_stock);
        $this->assertEquals(33000, $stampRecord->amount);
    }

    public function test_materai_transaction_with_custom_price(): void
    {
        $cashier = $this->createCashierUser();
        $type = TransactionType::firstOrCreate(
            ['code' => 'materai'],
            ['name' => 'Materai', 'is_active' => true]
        );

        StampDutyRecord::create([
            'record_date' => today(),
            'quantity_sold' => 0,
            'quantity_purchased' => 5,
            'remaining_stock' => 5,
            'amount' => 50000,
        ]);

        $response = $this->actingAs($cashier)->post(route('kasir.transactions.store'), [
            'transaction_type_id' => $type->id,
            'customer_name' => 'Pembeli Materai Khusus',
            'stamp_quantity' => 2,
            'stamp_price' => 12000,
        ]);

        $response->assertRedirect(route('kasir.dashboard'));
        $transaction = Transaction::latest('id')->first();
        $this->assertEquals(24000, $transaction->amount);
        $this->assertEquals(24000, $transaction->total_payment);

        $stampRecord = StampDutyRecord::latest('id')->first();
        $this->assertEquals(2, $stampRecord->quantity_sold);
        $this->assertEquals(3, $stampRecord->remaining_stock);
    }

    public function test_materai_transaction_insufficient_stock_returns_error(): void
    {
        $cashier = $this->createCashierUser();
        $type = TransactionType::firstOrCreate(
            ['code' => 'materai'],
            ['name' => 'Materai', 'is_active' => true]
        );

        StampDutyRecord::create([
            'record_date' => today(),
            'quantity_sold' => 0,
            'quantity_purchased' => 2,
            'remaining_stock' => 2,
            'amount' => 20000,
        ]);

        $response = $this->actingAs($cashier)->post(route('kasir.transactions.store'), [
            'transaction_type_id' => $type->id,
            'customer_name' => 'Beli Melebihi Stok',
            'stamp_quantity' => 5,
        ]);

        $response->assertSessionHasErrors(['stamp_quantity']);
    }

    public function test_dashboard_renders_materai_inputs_and_admin_fee_placeholder(): void
    {
        $cashier = $this->createCashierUser();
        $type = TransactionType::firstOrCreate(
            ['code' => 'materai'],
            ['name' => 'Materai', 'is_active' => true]
        );

        $response = $this->actingAs($cashier)->get(route('kasir.dashboard', ['transaction_type_id' => $type->id]));

        $response->assertStatus(200);
        $response->assertSee('name="stamp_quantity"', false);
        $response->assertSee('placeholder="0"', false);
        $response->assertSee('name="stamp_price"', false);
        $response->assertSee('value="11000"', false);
        $response->assertSee('Default Rp 11.000', false);
    }

    public function test_admin_deleting_materai_transaction_restores_exact_stock_not_just_one(): void
    {
        $cashier = $this->createCashierUser();
        $admin = User::create([
            'role_id' => Role::firstOrCreate(['name' => 'Admin'])->id,
            'full_name' => 'Admin Sekolah',
            'username' => 'admin_test_delete',
            'password' => 'secret123',
            'is_active' => true,
        ]);

        $type = TransactionType::firstOrCreate(
            ['code' => 'materai'],
            ['name' => 'Materai', 'is_active' => true]
        );

        // 1. Restock 50 pcs materai
        StampDutyRecord::create([
            'record_date' => today(),
            'quantity_sold' => 0,
            'quantity_purchased' => 50,
            'remaining_stock' => 50,
            'amount' => 500000,
            'cashier_id' => $cashier->id,
            'notes' => 'Restock 50 pcs',
        ]);

        // 2. Beli 10 pcs materai
        $this->actingAs($cashier)->post(route('kasir.transactions.store'), [
            'transaction_type_id' => $type->id,
            'customer_name' => 'Pembeli 10 Materai',
            'stamp_quantity' => 10,
            'stamp_price' => 11000,
        ]);

        // Sisa stok saat ini harus 40 pcs
        $this->assertEquals(40, StampDutyRecord::latest('id')->value('remaining_stock'));
        $transaction = Transaction::latest('id')->first();
        $this->assertEquals(110000, $transaction->amount);

        // 3. Admin menghapus transaksi tersebut
        $deleteResponse = $this->actingAs($admin)->delete(route('admin.transactions.destroy', $transaction->id));
        $deleteResponse->assertRedirect(route('admin.transactions'));
        $deleteResponse->assertSessionHas('success');

        // Transaksi harus sudah terhapus
        $this->assertNull(Transaction::find($transaction->id));

        // Sisa stok materai HARUS kembali ke 50 pcs (bukan 41 pcs!)
        $this->assertEquals(50, StampDutyRecord::latest('id')->value('remaining_stock'));
    }

    public function test_cashier_dashboard_auto_reconciles_legacy_buggy_delete_records(): void
    {
        $cashier = $this->createCashierUser();

        // 1. Record restock 50
        StampDutyRecord::create([
            'record_date' => today(),
            'quantity_sold' => 0,
            'quantity_purchased' => 50,
            'remaining_stock' => 50,
            'amount' => 500000,
            'notes' => 'Restock Awal',
        ]);

        // 2. Record penjualan 10 pcs yang transaksinya sudah terhapus
        StampDutyRecord::create([
            'record_date' => today(),
            'quantity_sold' => 10,
            'quantity_purchased' => 0,
            'remaining_stock' => 40,
            'amount' => 110000,
            'notes' => 'Penjualan Materai 10 pcs @ Rp 11.000 (Transaksi #99999)',
        ]);

        // 3. Record cacat bug versi lama (+1 padahal pembelian 10 pcs)
        StampDutyRecord::create([
            'record_date' => today(),
            'quantity_sold' => 0,
            'quantity_purchased' => 1,
            'remaining_stock' => 41,
            'amount' => 110000,
            'notes' => null, // notes null khas bug lama
        ]);

        // Sebelum dibuka, sisa stok salah di angka 41
        $this->assertEquals(41, StampDutyRecord::latest('id')->value('remaining_stock'));

        // Kasir membuka dashboard
        $response = $this->actingAs($cashier)->get(route('kasir.dashboard'));
        $response->assertStatus(200);

        // Sisa stok otomatis terekonsiliasi kembali menjadi 50 pcs!
        $this->assertEquals(50, StampDutyRecord::latest('id')->value('remaining_stock'));
        $response->assertSee('50');
    }

    public function test_cashier_dashboard_ensures_bayar_spp_transaction_type_exists(): void
    {
        $cashier = $this->createCashierUser();

        // Pastikan belum ada 'bayar_spp'
        TransactionType::where('code', 'bayar_spp')->delete();
        $this->assertDatabaseMissing('transaction_types', ['code' => 'bayar_spp']);

        // Kasir mengakses dashboard
        $response = $this->actingAs($cashier)->get(route('kasir.dashboard'));
        $response->assertStatus(200);

        // 'bayar_spp' harus otomatis terbuat dan tampil di halaman
        $this->assertDatabaseHas('transaction_types', [
            'code' => 'bayar_spp',
            'name' => 'Bayar SPP',
            'is_active' => true,
        ]);
        $response->assertSee('Bayar SPP');
    }

    public function test_cashier_can_process_bayar_spp_transaction_with_auto_prefix(): void
    {
        $cashier = $this->createCashierUser();
        $sppType = TransactionType::firstOrCreate(
            ['code' => 'bayar_spp'],
            ['name' => 'Bayar SPP', 'is_active' => true]
        );

        // Kasir menginput NIS '12511177' di form samping label '98844565'
        $response = $this->actingAs($cashier)->post(route('kasir.transactions.store'), [
            'transaction_type_id' => $sppType->id,
            'customer_identifier' => '12511177', // Hanya NIS
            'customer_name' => 'Muhammad Rizky (XI RPL 1)',
            'amount' => 250000,
            'admin_fee' => 0,
            'notes' => 'SPP Bulan Oktober 2026',
        ]);

        $response->assertRedirect(route('kasir.dashboard'));
        $response->assertSessionHas('success');

        // Customer (Siswa) tersimpan dengan identitas lengkap 9884456512511177
        $this->assertDatabaseHas('customers', [
            'transaction_type_id' => $sppType->id,
            'customer_identifier' => '9884456512511177',
            'customer_name' => 'Muhammad Rizky (XI RPL 1)',
        ]);

        // Transaksi tersimpan
        $this->assertDatabaseHas('transactions', [
            'transaction_type_id' => $sppType->id,
            'amount' => 250000,
            'admin_fee' => 0,
            'total_payment' => 250000,
            'cashier_id' => $cashier->id,
            'notes' => 'SPP Bulan Oktober 2026',
        ]);
    }

    public function test_bayar_spp_does_not_duplicate_prefix_if_already_present(): void
    {
        $cashier = $this->createCashierUser();
        $sppType = TransactionType::firstOrCreate(
            ['code' => 'bayar_spp'],
            ['name' => 'Bayar SPP', 'is_active' => true]
        );

        // Jika dikirim sudah dengan prefix 9884456512511177
        $response = $this->actingAs($cashier)->post(route('kasir.transactions.store'), [
            'transaction_type_id' => $sppType->id,
            'customer_identifier' => '9884456512511177',
            'customer_name' => 'Fajar Pratama (XI RPL 2)',
            'amount' => 250000,
            'admin_fee' => 0,
        ]);

        $response->assertRedirect(route('kasir.dashboard'));

        // Tidak boleh menjadi 9884456598844565...
        $this->assertDatabaseHas('customers', [
            'transaction_type_id' => $sppType->id,
            'customer_identifier' => '9884456512511177',
        ]);
    }
}
