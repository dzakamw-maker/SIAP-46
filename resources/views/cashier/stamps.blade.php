@extends('layouts.cashier')

@section('page_title', 'Restock Materai')

@section('cashier_content')
@if(session('success'))
<div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm flex items-center justify-between shadow-sm">
    <div class="flex items-center">
        <svg class="w-5 h-5 mr-3 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
</div>
@endif

@if($errors->any())
<div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm shadow-sm">
    <div class="flex items-center mb-1 font-medium">
        <svg class="w-5 h-5 mr-2 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <span>Gagal menyimpan restock materai:</span>
    </div>
    <ul class="list-disc list-inside space-y-1 text-xs text-red-600 ml-7">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Form Restock Materai -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-semibold text-gray-800 text-lg">Catat Restock Materai Baru</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('kasir.stamps.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="record_date" class="block mb-2 text-sm font-medium text-gray-700">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" id="record_date" name="record_date" value="{{ old('record_date', date('Y-m-d')) }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5">
                </div>
                <div class="mb-4">
                    <label for="quantity_purchased" class="block mb-2 text-sm font-medium text-gray-700">Jumlah Materai Ditambahkan (pcs) <span class="text-red-500">*</span></label>
                    <input type="number" id="quantity_purchased" name="quantity_purchased" value="{{ old('quantity_purchased') }}" min="1" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5" placeholder="Contoh: 50">
                </div>
                <div class="mb-4">
                    <label for="amount" class="block mb-2 text-sm font-medium text-gray-700">Total Harga Beli (Rp)</label>
                    <input type="number" id="amount" name="amount" value="{{ old('amount') }}" min="0" step="any" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5" placeholder="Contoh: 500000">
                </div>
                <div class="mb-6">
                    <label for="notes" class="block mb-2 text-sm font-medium text-gray-700">Catatan Tambahan (Opsional)</label>
                    <textarea id="notes" name="notes" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5" rows="2" placeholder="Contoh: Pembelian materai di Kantor Pos">{{ old('notes') }}</textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="text-white bg-orange-600 hover:bg-orange-700 focus:ring-4 focus:outline-none focus:ring-orange-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center cursor-pointer transition-colors">Simpan Restock</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Stok Materai -->
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-sm font-medium text-gray-500 mb-1">Informasi Stok Materai</h3>
            <div class="flex justify-between items-end mb-4">
                <p class="text-3xl font-bold text-gray-800">{{ $stampsStock }} <span class="text-lg font-normal text-gray-500">pcs</span></p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800">Riwayat Restock Terbaru</h3>
                <span class="text-xs text-gray-500 font-medium">{{ count($recentRestocks) }} Catatan</span>
            </div>
            <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                @forelse($recentRestocks as $record)
                    <div class="p-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-emerald-100 text-emerald-800">
                                    +{{ $record->quantity_purchased }} pcs
                                </span>
                                <span class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($record->record_date)->translatedFormat('d M Y') }}
                                </span>
                            </div>
                            <p class="text-sm font-medium text-gray-800 mt-1">
                                {{ $record->notes ?: 'Restock Materai' }}
                            </p>
                            <p class="text-xs text-gray-500">
                                Oleh: {{ $record->cashier?->full_name ?? 'Petugas' }}
                            </p>
                        </div>
                        <div class="text-right">
                            @if($record->amount > 0)
                                <p class="text-sm font-semibold text-gray-900">
                                    Rp {{ number_format($record->amount, 0, ',', '.') }}
                                </p>
                            @endif
                            <p class="text-xs text-gray-500 mt-0.5">
                                Sisa: <span class="font-medium text-gray-700">{{ $record->remaining_stock }} pcs</span>
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center text-sm text-gray-500">Belum ada riwayat restock materai</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
