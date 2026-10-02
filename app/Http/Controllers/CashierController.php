<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\StampDutyRecord;
use App\Models\Transaction;
use App\Models\TransactionType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CashierController extends Controller
{
    public function dashboard(): View
    {
        $this->reconcileStampsStockIfNeeded();

        $types = TransactionType::where('is_active', true)->get();
        $stampsStock = StampDutyRecord::latest('id')->value('remaining_stock') ?? 0;
        $todayTransactions = Transaction::with(['customer', 'transactionType'])
            ->whereDate('transaction_date', today())
            ->latest()
            ->get();

        return view('cashier.dashboard', compact('types', 'stampsStock', 'todayTransactions'));
    }

    public function searchCustomers(Request $request): JsonResponse
    {
        $typeId = $request->query('transaction_type_id');
        $search = trim((string) $request->query('q', ''));

        if (! $typeId || $search === '') {
            return response()->json([]);
        }

        $query = Customer::where('transaction_type_id', $typeId)
            ->where('is_default', true)
            ->where(function ($q) use ($search) {
                $q->where('customer_identifier', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%");
            });

        $customers = $query->latest('updated_at')
            ->limit(10)
            ->get(['id', 'customer_identifier', 'customer_name', 'is_default']);

        return response()->json($customers);
    }

    public function checkCustomerDefault(Request $request): JsonResponse
    {
        $typeId = $request->query('transaction_type_id');
        $name = trim((string) $request->query('customer_name', ''));
        $identifier = trim((string) $request->query('customer_identifier', ''));

        if (! $typeId || $name === '') {
            return response()->json([
                'has_default' => false,
                'default_identifier' => null,
                'requires_verification' => true,
            ]);
        }

        $defaultCustomer = Customer::where('transaction_type_id', $typeId)
            ->where('is_default', true)
            ->where(function ($q) use ($name) {
                $q->whereRaw('LOWER(customer_name) = ?', [strtolower($name)])
                    ->orWhere('customer_name', $name);
            })
            ->first();

        if ($defaultCustomer) {
            return response()->json([
                'has_default' => true,
                'default_identifier' => $defaultCustomer->customer_identifier,
                'is_current_default' => ($defaultCustomer->customer_identifier === $identifier),
                'requires_verification' => false,
            ]);
        }

        return response()->json([
            'has_default' => false,
            'default_identifier' => null,
            'requires_verification' => true,
        ]);
    }

    public function storeTransaction(Request $request): RedirectResponse
    {
        $selectedType = TransactionType::find($request->input('transaction_type_id'));
        $isMaterai = $selectedType && (
            in_array(strtolower($selectedType->code), ['materai', 'mtr'], true) ||
            str_contains(strtolower($selectedType->code), 'materai') ||
            str_contains(strtolower($selectedType->name), 'materai')
        );

        $rules = [
            'transaction_type_id' => ['required', 'exists:transaction_types,id'],
            'customer_identifier' => [$isMaterai ? 'nullable' : 'required', 'string', 'max:255'],
            'customer_name' => ['required', 'string', 'max:255'],
            'is_default' => ['nullable', 'in:0,1,true,false'],
            'amount' => [$isMaterai ? 'nullable' : 'required', 'numeric', 'min:0'],
            'stamp_quantity' => ['nullable', 'integer', 'min:1'],
            'stamp_price' => ['nullable', 'numeric', 'min:0'],
            'admin_fee' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:255'],
        ];

        if ($isMaterai && ! $request->filled('amount') && ! $request->filled('stamp_quantity')) {
            $rules['stamp_quantity'] = ['required', 'integer', 'min:1'];
        }

        $validated = $request->validate($rules, [
            'transaction_type_id.required' => 'Jenis transaksi wajib dipilih.',
            'customer_identifier.required' => 'Identitas customer wajib diisi.',
            'customer_name.required' => 'Nama customer wajib diisi.',
            'amount.required' => 'Nominal uang transaksi wajib diisi.',
            'stamp_quantity.required' => 'Jumlah pembelian materai wajib diisi.',
            'stamp_quantity.min' => 'Jumlah pembelian materai minimal 1 pcs.',
            'stamp_quantity.integer' => 'Jumlah pembelian materai harus berupa angka bulat.',
        ]);

        $type = $selectedType ?? TransactionType::findOrFail($validated['transaction_type_id']);
        $stampsStock = StampDutyRecord::latest('id')->value('remaining_stock') ?? 0;

        $quantityPurchased = 1;
        $stampPrice = 11000.0;

        if ($isMaterai) {
            $quantityPurchased = $request->filled('stamp_quantity') ? (int) $request->input('stamp_quantity') : 1;
            $stampPrice = $request->filled('stamp_price') ? (float) $request->input('stamp_price') : 11000.0;

            if ($stampsStock <= 0) {
                return back()->withErrors(['transaction_type_id' => 'Stok materai habis! Transaksi tidak dapat diproses.'])->withInput();
            }

            if ($stampsStock < $quantityPurchased) {
                return back()->withErrors(['stamp_quantity' => "Stok materai tidak mencukupi! Sisa stok: {$stampsStock} pcs."])->withInput();
            }

            $amount = $request->filled('amount') && ! $request->filled('stamp_quantity')
                ? (float) $validated['amount']
                : (float) ($quantityPurchased * $stampPrice);
            $adminFee = 0.0;
        } else {
            $amount = (float) $validated['amount'];
            $adminFee = (float) ($validated['admin_fee'] ?? 0);
        }

        $customerName = trim($validated['customer_name']);
        $customerIdent = trim((string) ($validated['customer_identifier'] ?? ''));

        if ($isMaterai && $customerIdent === '') {
            $customerIdent = 'MTR-'.now()->format('YmdHis').'-'.strtoupper(Str::random(4));
        }

        $isDefaultInput = $request->input('is_default');

        // Cek apakah customer dengan nama ini sudah punya default ID
        $existingDefault = Customer::where('transaction_type_id', $type->id)
            ->where('is_default', true)
            ->where(function ($q) use ($customerName) {
                $q->whereRaw('LOWER(customer_name) = ?', [strtolower($customerName)])
                    ->orWhere('customer_name', $customerName);
            })
            ->first();

        // Tentukan nilai is_default
        $shouldBeDefault = false;
        if (! $isMaterai) {
            if ($isDefaultInput === '1' || $isDefaultInput === 1 || $isDefaultInput === true) {
                $shouldBeDefault = true;
            } elseif ($isDefaultInput === '0' || $isDefaultInput === 0 || $isDefaultInput === false) {
                $shouldBeDefault = false;
            } elseif ($existingDefault && $existingDefault->customer_identifier === $customerIdent) {
                $shouldBeDefault = true;
            }

            // Jika diset default dan sebelumnya ada default lain untuk orang ini, nonaktifkan default sebelumnya
            if ($shouldBeDefault && $existingDefault && $existingDefault->customer_identifier !== $customerIdent) {
                $existingDefault->update(['is_default' => false]);
            }
        }

        // Simpan / update identitas customer untuk jenis transaksi ini
        $customer = Customer::updateOrCreate(
            [
                'transaction_type_id' => $type->id,
                'customer_identifier' => $customerIdent,
            ],
            [
                'customer_name' => $customerName,
                'is_default' => $shouldBeDefault,
                'last_edited_by' => auth()->id(),
            ]
        );

        $todayCount = Transaction::whereDate('transaction_date', today())->count();
        $txNumber = $todayCount + 1;

        $totalPayment = $amount + $adminFee;

        Transaction::create([
            'transaction_number' => $txNumber,
            'transaction_date' => today(),
            'transaction_type_id' => $type->id,
            'customer_id' => $customer->id,
            'amount' => $amount,
            'admin_fee' => $adminFee,
            'total_payment' => $totalPayment,
            'cashier_id' => auth()->id(),
            'notes' => $validated['notes'] ?? null,
        ]);

        // Kurangi stok materai jika transaksi materai
        if ($isMaterai) {
            StampDutyRecord::create([
                'record_date' => today(),
                'quantity_sold' => $quantityPurchased,
                'quantity_purchased' => 0,
                'remaining_stock' => max(0, $stampsStock - $quantityPurchased),
                'amount' => $amount,
                'cashier_id' => auth()->id(),
                'notes' => 'Penjualan Materai '.$quantityPurchased.' pcs @ Rp '.number_format($stampPrice, 0, ',', '.').' (Transaksi #'.$txNumber.')',
            ]);
        }

        return redirect()->route('kasir.dashboard')->with('success', 'Transaksi #'.$txNumber.' ('.$type->name.') atas nama '.$customer->customer_name.' berhasil diproses!');
    }

    public function stamps(): View
    {
        $this->reconcileStampsStockIfNeeded();

        $stampsStock = StampDutyRecord::latest('id')->value('remaining_stock') ?? 0;
        $recentRestocks = StampDutyRecord::with('cashier')
            ->where('quantity_purchased', '>', 0)
            ->latest('id')
            ->take(10)
            ->get();

        return view('cashier.stamps', compact('stampsStock', 'recentRestocks'));
    }

    public function storeStamp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'record_date' => ['required', 'date'],
            'quantity_purchased' => ['required', 'integer', 'min:1'],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:255'],
        ], [
            'record_date.required' => 'Tanggal restock wajib diisi.',
            'record_date.date' => 'Format tanggal tidak valid.',
            'quantity_purchased.required' => 'Jumlah materai wajib diisi.',
            'quantity_purchased.integer' => 'Jumlah materai harus berupa bilangan bulat.',
            'quantity_purchased.min' => 'Jumlah materai minimal 1 pcs.',
            'amount.numeric' => 'Total harga beli harus berupa angka.',
            'amount.min' => 'Total harga beli tidak boleh bernilai negatif.',
            'notes.max' => 'Catatan maksimal 255 karakter.',
        ]);

        $latestRecord = StampDutyRecord::latest('id')->first();
        $currentStock = $latestRecord ? (int) $latestRecord->remaining_stock : 0;
        $quantityPurchased = (int) $validated['quantity_purchased'];
        $amount = (float) ($validated['amount'] ?? 0);
        $newStock = $currentStock + $quantityPurchased;

        StampDutyRecord::create([
            'record_date' => $validated['record_date'],
            'quantity_sold' => 0,
            'quantity_purchased' => $quantityPurchased,
            'remaining_stock' => $newStock,
            'amount' => $amount,
            'cashier_id' => auth()->id(),
            'notes' => $validated['notes'] ?? 'Restock Materai',
        ]);

        return redirect()->route('kasir.stamps')->with('success', 'Restock '.$quantityPurchased.' pcs materai berhasil disimpan. Stok saat ini: '.$newStock.' pcs.');
    }

    public function eod(): View
    {
        return view('cashier.eod');
    }

    /**
     * Rekonsiliasi otomatis data sisa stok materai jika terdapat anomali atau record orphan dari pembatalan transaksi.
     */
    private function reconcileStampsStockIfNeeded(): void
    {
        $needsRecount = false;

        // 1. Bersihkan record pembatalan cacat dari bug destroyTransaction lama (quantity_purchased = 1, notes null, amount >= 20000)
        $buggyRecords = StampDutyRecord::where('quantity_purchased', 1)
            ->whereNull('notes')
            ->where('amount', '>=', 20000)
            ->get();

        foreach ($buggyRecords as $buggy) {
            $buggy->delete();
            $needsRecount = true;
        }

        // 2. Cek apakah ada record penjualan materai yang transaksinya sudah dihapus dari sistem
        $saleRecords = StampDutyRecord::where('quantity_sold', '>', 0)->get();
        foreach ($saleRecords as $sale) {
            if (preg_match('/Transaksi #(\d+)/', (string) $sale->notes, $matches)) {
                $trxNumber = $matches[1];
                $exists = Transaction::where('transaction_number', $trxNumber)->exists();
                if (! $exists) {
                    $sale->delete();
                    $needsRecount = true;
                }
            }
        }

        // 3. Jika ada record yang dibersihkan, rekonsiliasi ulang seluruh rantai remaining_stock
        if ($needsRecount) {
            $records = StampDutyRecord::orderBy('id', 'asc')->get();
            $running = 0;
            foreach ($records as $rec) {
                $running = max(0, $running + (int) $rec->quantity_purchased - (int) $rec->quantity_sold);
                if ($rec->remaining_stock !== $running) {
                    $rec->update(['remaining_stock' => $running]);
                }
            }
        }
    }
}
