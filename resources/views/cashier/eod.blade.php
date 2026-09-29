@extends('layouts.cashier')

@section('page_title', 'Rekap Harian (End of Day)')

@section('cashier_content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-semibold text-gray-800 text-lg">Buat Laporan Rekap Harian</h3>
            <p class="text-sm text-gray-500">Form ini diisi oleh kasir pada akhir shift untuk rekonsiliasi uang fisik dan saldo BNI.</p>
        </div>
        <div class="p-6">
            <form>
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Tanggal Rekap</label>
                    <input type="date" value="{{ date('Y-m-d') }}" readonly class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 cursor-not-allowed">
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Sisa Saldo BNI Sistem (Otomatis)</label>
                        <input type="text" readonly value="Rp 4.250.000" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Sisa Saldo BNI Aktual (Sesuai mutasi rekening)</label>
                        <input type="number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5" placeholder="Masukkan angka persis di rekening">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Total Uang Tunai Fisik (Rp)</label>
                        <input type="number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5" placeholder="Hitung uang di laci">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Sisa Materai Fisik (pcs)</label>
                        <input type="number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5" placeholder="Hitung sisa materai">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Catatan Khusus (Jika ada selisih)</label>
                    <textarea class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5" rows="3" placeholder="Tulis catatan di sini..."></textarea>
                </div>

                <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200 mb-6 flex items-start">
                    <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <p class="text-sm text-yellow-800">
                        Pastikan semua data yang diinput sudah sesuai dengan fisik sebenarnya. Data rekap harian yang di-submit akan diverifikasi oleh Guru Pembimbing keesokan harinya.
                    </p>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="text-white bg-orange-600 hover:bg-orange-700 focus:ring-4 focus:outline-none focus:ring-orange-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Kirim Laporan EOD</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
