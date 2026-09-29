@extends('layouts.admin')

@section('page_title', 'Laporan Transaksi')

@section('admin_content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50">
                <tr>
                    <th class="px-4 py-3 rounded-l-lg">Tanggal</th>
                    <th class="px-4 py-3">Nasabah</th>
                    <th class="px-4 py-3">Jenis Transaksi</th>
                    <th class="px-4 py-3">Kasir</th>
                    <th class="px-4 py-3 text-right">Nominal</th>
                    <th class="px-4 py-3 text-right">Biaya Admin</th>
                    <th class="px-4 py-3 text-right rounded-r-lg">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $trx)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $trx->transaction_date->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">{{ $trx->customer->customer_name ?? $trx->customer->customer_identifier }}</td>
                    <td class="px-4 py-3">{{ $trx->transactionType->name }}</td>
                    <td class="px-4 py-3">{{ $trx->cashier->full_name }}</td>
                    <td class="px-4 py-3 text-right">Rp {{ number_format($trx->amount, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-right">Rp {{ number_format($trx->admin_fee, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-right font-medium">Rp {{ number_format($trx->total_payment, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">Belum ada data transaksi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection
