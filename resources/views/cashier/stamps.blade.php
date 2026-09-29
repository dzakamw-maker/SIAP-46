@extends('layouts.cashier')

@section('page_title', 'Restock Materai')

@section('cashier_content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Form Restock Materai -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-semibold text-gray-800 text-lg">Catat Restock Materai Baru</h3>
        </div>
        <div class="p-6">
            <form>
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Tanggal</label>
                    <input type="date" value="{{ date('Y-m-d') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5">
                </div>
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Jumlah Materai Ditambahkan (pcs)</label>
                    <input type="number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5" placeholder="50">
                </div>
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Total Harga Beli (Rp)</label>
                    <input type="number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5" placeholder="500000">
                </div>
                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Catatan Tambahan (Opsional)</label>
                    <textarea class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5" rows="2"></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="text-white bg-orange-600 hover:bg-orange-700 focus:ring-4 focus:outline-none focus:ring-orange-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Simpan Restock</button>
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
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                <h3 class="font-semibold text-gray-800">Riwayat Restock Terbaru</h3>
            </div>
            <div class="divide-y divide-gray-100">
                <div class="p-4 text-center text-sm text-gray-500">Belum ada riwayat restock materai</div>
            </div>
        </div>
    </div>
</div>
@endsection
