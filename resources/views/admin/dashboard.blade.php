@extends('layouts.admin')

@section('page_title', 'Dashboard')

@section('admin_content')
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Transaksi Hari Ini</p>
                    <h3 class="text-2xl font-bold text-gray-800">42</h3>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Nominal</p>
                    <h3 class="text-2xl font-bold text-gray-800">Rp 12.500.000</h3>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <p class="text-sm font-medium text-gray-500 mb-1">Sisa Saldo BNI</p>
                    <h3 class="text-2xl font-bold text-orange-600">Rp 4.250.000</h3>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <p class="text-sm font-medium text-gray-500 mb-1">Kasir Hadir</p>
                    <h3 class="text-2xl font-bold text-teal-600">3 / 4 Siswa</h3>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Transactions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="font-semibold text-gray-800">Transaksi Terbaru</h3>
                        <a href="{{ route('admin.transactions') }}" class="text-sm text-teal-600 hover:underline">Lihat Semua</a>
                    </div>
                    <div class="p-6">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-gray-500 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 rounded-l-lg">Nasabah</th>
                                    <th class="px-4 py-2">Jenis</th>
                                    <th class="px-4 py-2">Nominal</th>
                                    <th class="px-4 py-2 rounded-r-lg">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b">
                                    <td class="px-4 py-3 font-medium">Budi Santoso</td>
                                    <td class="px-4 py-3"><span class="bg-blue-100 text-blue-800 text-xs px-2.5 py-0.5 rounded">Setor Tunai</span></td>
                                    <td class="px-4 py-3">Rp 500.000</td>
                                    <td class="px-4 py-3 text-green-600">Selesai</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="px-4 py-3 font-medium">Siti Aminah</td>
                                    <td class="px-4 py-3"><span class="bg-purple-100 text-purple-800 text-xs px-2.5 py-0.5 rounded">Tarik Tunai</span></td>
                                    <td class="px-4 py-3">Rp 200.000</td>
                                    <td class="px-4 py-3 text-green-600">Selesai</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 font-medium">Joko Widodo</td>
                                    <td class="px-4 py-3"><span class="bg-orange-100 text-orange-800 text-xs px-2.5 py-0.5 rounded">Beli Materai</span></td>
                                    <td class="px-4 py-3">Rp 12.000</td>
                                    <td class="px-4 py-3 text-green-600">Selesai</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Presensi Kasir -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="font-semibold text-gray-800">Presensi Kasir Hari Ini</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-800 font-bold">AS</div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Agus Setiawan</p>
                                    <p class="text-xs text-gray-500">Shift Pagi (07:00 - 12:00)</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                <span class="w-2 h-2 mr-1 bg-green-500 rounded-full"></span>
                                Hadir
                            </span>
                        </div>
                    </div>
                </div>
            </div>
@endsection
