@extends('layouts.cashier')

@section('page_title', 'Penjualan Materai')

@section('cashier_content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Form Jual Materai -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-semibold text-gray-800 text-lg">Catat Penjualan Materai</h3>
        </div>
        <div class="p-6">
            <form>
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Tanggal</label>
                    <input type="date" value="{{ date('Y-m-d') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5">
                </div>
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Jumlah Materai Terjual (pcs)</label>
                    <input type="number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5" placeholder="1">
                </div>
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Total Uang Diterima (Rp)</label>
                    <input type="number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5" placeholder="12000">
                </div>
                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Catatan Tambahan (Opsional)</label>
                    <textarea class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5" rows="2"></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="text-white bg-orange-600 hover:bg-orange-700 focus:ring-4 focus:outline-none focus:ring-orange-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Simpan Penjualan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Stok Materai -->
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-sm font-medium text-gray-500 mb-1">Informasi Stok Materai</h3>
            <div class="flex justify-between items-end mb-4">
                <p class="text-3xl font-bold text-gray-800">45 <span class="text-lg font-normal text-gray-500">pcs</span></p>
                <button class="text-sm text-orange-600 font-medium hover:underline">Restock / Tambah Stok</button>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2 mb-1">
                <div class="bg-orange-500 h-2 rounded-full" style="width: 45%"></div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                <h3 class="font-semibold text-gray-800">Riwayat Penjualan Terbaru</h3>
            </div>
            <div class="divide-y divide-gray-100">
                <div class="p-4 text-center text-sm text-gray-500">Belum ada riwayat penjualan meterai</div>
            </div>
        </div>
    </div>
</div>
@endsection
