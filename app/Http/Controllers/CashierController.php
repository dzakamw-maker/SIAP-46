<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\StampDutyRecord;
use App\Models\Transaction;
use App\Models\TransactionType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CashierController extends Controller
{
    public function dashboard(): View
    {
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
        $validated = $request->validate([
            'transaction_type_id' => ['required', 'exists:transaction_types,id'],
            'customer_identifier' => ['required', 'string', 'max:255'],
            'customer_name' => ['required', 'string', 'max:255'],
            'is_default' => ['nullable', 'in:0,1,true,false'],
            'amount' => ['required', 'numeric', 'min:0'],
            'admin_fee' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:255'],
        ], [
            'transaction_type_id.required' => 'Jenis transaksi wajib dipilih.',
            'customer_identifier.required' => 'Identitas customer wajib diisi.',
            'customer_name.required' => 'Nama customer wajib diisi.',
            'amount.required' => 'Nominal uang transaksi wajib diisi.',
            'admin_fee.required' => 'Biaya admin wajib diisi.',
        ]);

        $type = TransactionType::findOrFail($validated['transaction_type_id']);
        $stampsStock = StampDutyRecord::latest('id')->value('remaining_stock') ?? 0;

        // Cek stok jika transaksi adalah Materai
        if (in_array(strtolower($type->code), ['materai', 'mtr'], true) && $stampsStock <= 0) {
            return back()->withErrors(['transaction_type_id' => 'Stok materai habis! Transaksi tidak dapat diproses.'])->withInput();
        }

        $customerName = trim($validated['customer_name']);
        $customerIdent = trim($validated['customer_identifier']);
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

        $amount = (float) $validated['amount'];
        $adminFee = (float) $validated['admin_fee'];
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
        if (in_array(strtolower($type->code), ['materai', 'mtr'], true)) {
            StampDutyRecord::create([
                'record_date' => today(),
                'quantity_sold' => 1,
                'quantity_purchased' => 0,
                'remaining_stock' => max(0, $stampsStock - 1),
                'amount' => $amount,
                'cashier_id' => auth()->id(),
                'notes' => 'Penjualan Materai Transaksi #'.$txNumber,
            ]);
        }

        return redirect()->route('kasir.dashboard')->with('success', 'Transaksi #'.$txNumber.' ('.$type->name.') atas nama '.$customer->customer_name.' berhasil diproses!');
    }

    public function stamps(): View
    {
        $stampsStock = StampDutyRecord::latest('id')->value('remaining_stock') ?? 0;

        return view('cashier.stamps', compact('stampsStock'));
    }

    public function eod(): View
    {
        return view('cashier.eod');
    }
}
