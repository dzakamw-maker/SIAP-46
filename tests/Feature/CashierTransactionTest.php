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
}
