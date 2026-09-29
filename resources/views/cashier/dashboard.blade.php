@extends('layouts.cashier')

@section('page_title', 'Input Transaksi')

@section('cashier_content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Input Transaksi -->
    <div class="col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-semibold text-gray-800 text-lg">Input Transaksi Baru</h3>
            </div>
            <div class="p-6">
                <form>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">Jenis Transaksi</label>
                            <select name="transaction_type_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5">
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">Identitas Customer (No. Rek / HP)</label>
                            <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5" placeholder="Masukkan nomor">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Nama Customer</label>
                        <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5" placeholder="Nama otomatis atau isi manual">
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">Nominal Uang (Rp)</label>
                            <input type="number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5" placeholder="0">
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">Biaya Admin (Rp)</label>
                            <input type="number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5" value="0">
                        </div>
                    </div>
                    <div class="mb-6 p-4 bg-orange-50 rounded-lg border border-orange-100">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-orange-800">Total Pembayaran Customer:</span>
                            <span class="text-xl font-bold text-orange-600">Rp 0</span>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 mr-2">Batal</button>
                        <button type="submit" class="text-white bg-orange-600 hover:bg-orange-700 focus:ring-4 focus:outline-none focus:ring-orange-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Proses Transaksi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Info Sidebar & Transaksi Terakhir -->
    <div class="col-span-1 space-y-6">
        <!-- Status Saldo -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <h3 class="text-sm font-medium text-gray-500 mb-1">Status Sisa Saldo BNI</h3>
            <p class="text-2xl font-bold text-gray-800 mb-2">Rp 4.250.000</p>
            <div class="w-full bg-gray-200 rounded-full h-1.5 mb-1">
                <div class="bg-orange-500 h-1.5 rounded-full" style="width: 45%"></div>
            </div>
            <p class="text-xs text-gray-500 text-right">Aman</p>
        </div>

        <!-- Transaksi Hari Ini -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                <h3 class="font-semibold text-gray-800">Riwayat Hari Ini</h3>
            </div>
            <div class="divide-y divide-gray-100 max-h-80 overflow-y-auto">
                <div class="p-4 text-center text-sm text-gray-500">Belum ada transaksi hari ini</div>
            </div>
        </div>
    </div>
</div>
@endsection
