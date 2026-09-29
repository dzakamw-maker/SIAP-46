@extends('layouts.admin')

@section('page_title', 'Rekap Harian (End of Day)')

@section('admin_content')
@if(session('success'))
<div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm flex items-center justify-between">
    <div class="flex items-center">
        <svg class="w-5 h-5 mr-3 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span>{{ session('success') }}</span>
    </div>
</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6">
        <div class="mb-5">
            <h3 class="text-lg font-semibold text-gray-800">Laporan Rekonsiliasi Kasir (EOD)</h3>
            <p class="text-sm text-gray-500 mt-1">Periksa kesesuaian uang tunai di laci, saldo mutasi BNI, dan sisa materai dari kasir siswa.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 rounded-l-lg">Tanggal</th>
                        <th class="px-4 py-3">Petugas Kasir</th>
                        <th class="px-4 py-3 text-right">Saldo BNI Aktual</th>
                        <th class="px-4 py-3 text-right">Uang Kas Fisik</th>
                        <th class="px-4 py-3 text-center">Sisa Materai</th>
                        <th class="px-4 py-3">Catatan Kasir</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center rounded-r-lg">Aksi Guru</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recaps as $recap)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                            {{ $recap->recap_date ? $recap->recap_date->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="font-medium text-gray-800">{{ $recap->recordedBy->full_name ?? 'Kasir Umum' }}</div>
                            <div class="text-xs text-gray-400">{{ $recap->recordedBy->class_group ?? '-' }}</div>
                        </td>
                        <td class="px-4 py-3 text-right font-medium whitespace-nowrap">
                            Rp {{ number_format($recap->bni_balance_remaining ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-right font-medium text-gray-800 whitespace-nowrap">
                            Rp {{ number_format($recap->cash_amount ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-center whitespace-nowrap">
                            <span class="bg-gray-100 text-gray-700 text-xs px-2.5 py-1 rounded-full font-medium">
                                {{ $recap->stamp_remaining_value ?? 0 }} pcs
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600 max-w-xs truncate" title="{{ $recap->notes }}">
                            {{ $recap->notes ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-center whitespace-nowrap">
                            @if($recap->verified_by)
                                <span class="inline-flex items-center text-xs font-medium bg-green-100 text-green-800 px-2.5 py-1 rounded-full">
                                    <svg class="w-3.5 h-3.5 mr-1 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Terverifikasi
                                </span>
                                <div class="text-xs text-gray-400 mt-0.5">oleh {{ $recap->verifiedBy->full_name ?? 'Guru' }}</div>
                            @else
                                <span class="inline-flex items-center text-xs font-medium bg-amber-100 text-amber-800 px-2.5 py-1 rounded-full">
                                    <svg class="w-3.5 h-3.5 mr-1 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Menunggu Verifikasi
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center whitespace-nowrap">
                            @if($recap->verified_by)
                                <span class="text-xs text-gray-400 italic">Sudah Disetujui</span>
                            @else
                                <button type="button" onclick="openVerifyModal('{{ $recap->id }}', '{{ $recap->recap_date->format('d/m/Y') }}', '{{ $recap->recordedBy->full_name ?? 'Kasir' }}', '{{ number_format($recap->cash_amount ?? 0, 0, ',', '.') }}', '{{ number_format($recap->bni_balance_remaining ?? 0, 0, ',', '.') }}')" class="inline-flex items-center px-3 py-1.5 bg-teal-600 hover:bg-teal-700 text-white text-xs font-medium rounded-lg transition-colors shadow-sm">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Verifikasi
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-500">Belum ada data Rekap Harian (EOD) yang dikirim oleh kasir.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $recaps->links() }}
        </div>
    </div>
</div>

<!-- Modal Verifikasi Rekap Harian -->
<div id="verifyModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/40 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-xl shadow-xl p-6 max-w-md w-full mx-4">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <svg class="w-5 h-5 text-teal-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Verifikasi Laporan EOD
            </h3>
            <button type="button" onclick="closeVerifyModal()" class="text-gray-400 hover:text-gray-600">&times;</button>
        </div>

        <form id="verifyForm" method="POST" action="">
            @csrf
            
            <div class="bg-gray-50 rounded-lg p-3 text-xs text-gray-600 space-y-1.5 mb-4 border border-gray-100">
                <div class="flex justify-between">
                    <span class="text-gray-400">Tanggal Rekap:</span>
                    <span id="modalDate" class="font-medium text-gray-800"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Petugas Kasir:</span>
                    <span id="modalCashier" class="font-medium text-gray-800"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Uang Kas Fisik:</span>
                    <span class="font-semibold text-gray-900">Rp <span id="modalCash"></span></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Saldo BNI Aktual:</span>
                    <span class="font-semibold text-gray-900">Rp <span id="modalBalance"></span></span>
                </div>
            </div>

            <div class="mb-4">
                <label for="teacher_note" class="block text-xs font-medium text-gray-700 mb-1">Catatan Evaluasi Guru (Opsional)</label>
                <textarea name="teacher_note" id="teacher_note" rows="2" class="w-full text-xs rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2.5 border" placeholder="Contoh: Kas fisik dan saldo klop, kerja bagus."></textarea>
            </div>

            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="closeVerifyModal()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-xs font-medium text-gray-700 hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-xs font-medium shadow-sm transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Setujui & Verifikasi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openVerifyModal(id, date, cashier, cash, balance) {
        document.getElementById('modalDate').innerText = date;
        document.getElementById('modalCashier').innerText = cashier;
        document.getElementById('modalCash').innerText = cash;
        document.getElementById('modalBalance').innerText = balance;
        document.getElementById('verifyForm').action = '/admin/eod/' + id + '/verify';
        document.getElementById('verifyModal').classList.remove('hidden');
    }

    function closeVerifyModal() {
        document.getElementById('verifyModal').classList.add('hidden');
    }
</script>
@endsection
