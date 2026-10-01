@extends('layouts.cashier')

@section('page_title', 'Input Transaksi')

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
        <span>Gagal memproses transaksi:</span>
    </div>
    <ul class="list-disc list-inside space-y-1 text-xs text-red-600 ml-7">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Input Transaksi -->
    <div class="col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800 text-lg">Input Transaksi Baru</h3>
                <span class="text-xs text-gray-500 bg-white border border-gray-200 px-2.5 py-1 rounded-full font-medium">Loket Kasir</span>
            </div>
            <div class="p-6">
                <form id="transactionForm" action="{{ route('kasir.transactions.store') }}" method="POST" onsubmit="return validateTransaction(event)">
                    @csrf
                    <input type="hidden" name="is_default" id="is_default" value="">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <!-- Jenis Transaksi -->
                        <div>
                            <label for="transaction_type_id" class="block mb-2 text-sm font-medium text-gray-700">Jenis Transaksi</label>
                            <select name="transaction_type_id" id="transaction_type_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5 @error('transaction_type_id') border-red-500 @enderror" required>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}" data-code="{{ strtolower($type->code) }}" {{ old('transaction_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('transaction_type_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Identitas Customer -->
                        <div class="relative">
                            <label for="customer_identifier" class="flex items-center mb-2 text-sm font-medium text-gray-700">
                                <span>Identitas Customer</span>
                                <span class="text-xs text-gray-400 font-normal ml-1">(No. Rek / No. HP / IDPEL)</span>
                                <span id="defaultBadge" class="hidden ml-2 text-[10px] text-green-700 bg-green-50 border border-green-200 px-2 py-0.5 rounded-full font-medium">Default</span>
                            </label>
                            <input type="text" name="customer_identifier" id="customer_identifier" value="{{ old('customer_identifier') }}" autocomplete="off" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5 @error('customer_identifier') border-red-500 @enderror" placeholder="Ketik nomor / cari identitas..." required>
                            <div id="identifierSuggestions" class="absolute left-0 right-0 z-30 bg-white border border-gray-200 rounded-lg shadow-lg mt-1 hidden max-h-48 overflow-y-auto divide-y divide-gray-100"></div>
                            @error('customer_identifier')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Nama Customer -->
                    <div class="relative mb-4">
                        <label for="customer_name" class="block mb-2 text-sm font-medium text-gray-700">Nama Customer</label>
                        <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}" autocomplete="off" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5 @error('customer_name') border-red-500 @enderror" placeholder="Nama otomatis terisi atau ketik manual..." required>
                        <div id="nameSuggestions" class="absolute left-0 right-0 z-30 bg-white border border-gray-200 rounded-lg shadow-lg mt-1 hidden max-h-48 overflow-y-auto divide-y divide-gray-100"></div>
                        @error('customer_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nominal Uang & Biaya Admin -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="amount" class="block mb-2 text-sm font-medium text-gray-700">Nominal Uang (Rp)</label>
                            <input type="number" name="amount" id="amount" value="{{ old('amount') }}" min="0" step="any" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5 @error('amount') border-red-500 @enderror" placeholder="0" required>
                            @error('amount')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="admin_fee" class="block mb-2 text-sm font-medium text-gray-700">Biaya Admin (Rp)</label>
                            <input type="number" name="admin_fee" id="admin_fee" value="{{ old('admin_fee', 0) }}" min="0" step="any" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5 @error('admin_fee') border-red-500 @enderror" placeholder="0" required>
                            @error('admin_fee')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Catatan Tambahan (Opsional) -->
                    <div class="mb-4">
                        <label for="notes" class="block mb-2 text-sm font-medium text-gray-700">Catatan <span class="text-xs text-gray-400 font-normal">(Opsional)</span></label>
                        <input type="text" name="notes" id="notes" value="{{ old('notes') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5" placeholder="Contoh: Transaksi reguler / transfer via kartu BNI">
                    </div>

                    <!-- Total Pembayaran Banner -->
                    <div class="mb-6 p-4 bg-orange-50 rounded-xl border border-orange-100 shadow-2xs">
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-xs text-orange-700 uppercase tracking-wider font-semibold block">Total Pembayaran Customer</span>
                                <span class="text-xs text-gray-500 font-normal">(Nominal + Biaya Admin)</span>
                            </div>
                            <span id="totalDisplay" class="text-2xl font-black text-orange-600">Rp 0</span>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="reset" onclick="setTimeout(updateTotal, 50)" class="text-gray-600 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="text-white bg-orange-600 hover:bg-orange-700 focus:ring-4 focus:outline-none focus:ring-orange-300 font-medium rounded-lg text-sm px-6 py-2.5 shadow-sm transition-all hover:scale-[1.01]">
                            Proses Transaksi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Info Sidebar & Transaksi Terakhir -->
    <div class="col-span-1 space-y-6">
        <!-- Status Stok Materai -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <h3 class="text-sm font-medium text-gray-500 mb-1">Status Stok Materai</h3>
            <div class="flex justify-between items-end mb-2">
                <p class="text-2xl font-bold text-gray-800">{{ $stampsStock }} <span class="text-sm font-normal text-gray-500">pcs</span></p>
                @if($stampsStock <= 0)
                    <span class="text-xs text-red-600 bg-red-50 border border-red-200 font-medium px-2 py-0.5 rounded-full">Stok Habis</span>
                @else
                    <span class="text-xs text-green-600 bg-green-50 border border-green-200 font-medium px-2 py-0.5 rounded-full">Tersedia</span>
                @endif
            </div>
            <a href="{{ route('kasir.stamps') }}" class="text-xs text-orange-600 hover:text-orange-700 font-medium flex items-center gap-1">
                Restock Materai &rarr;
            </a>
        </div>

        <!-- Transaksi Hari Ini -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h3 class="font-semibold text-gray-800 text-sm">Riwayat Hari Ini</h3>
                <span class="text-xs font-bold text-orange-600 bg-orange-100/60 px-2 py-0.5 rounded-full">{{ $todayTransactions->count() }} Transaksi</span>
            </div>
            <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                @forelse($todayTransactions as $tx)
                    <div class="p-3.5 text-xs hover:bg-gray-50 transition-colors">
                        <div class="flex justify-between items-start mb-1">
                            <span class="font-bold text-gray-800">#{{ $tx->transaction_number }} • {{ $tx->transactionType?->name }}</span>
                            <span class="font-bold text-orange-600">Rp {{ number_format($tx->total_payment, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-500 text-[11px]">
                            <span class="truncate max-w-[140px]">{{ $tx->customer?->customer_name }} ({{ $tx->customer?->customer_identifier }})</span>
                            <span class="text-gray-400">{{ $tx->created_at->format('H:i') }}</span>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-sm text-gray-400">Belum ada transaksi hari ini</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Modal Verifikasi Identitas Default Customer -->
<div id="defaultVerificationModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/40 backdrop-blur-xs transition-opacity p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full overflow-hidden border border-gray-100">
        <div class="p-6">
            <div class="w-12 h-12 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            
            <h3 class="text-lg font-bold text-gray-900 text-center">Verifikasi Identitas Customer</h3>
            
            <div class="mt-3 text-center text-sm text-gray-600 space-y-2">
                <p>
                    Customer <span id="modalCustomerName" class="font-bold text-gray-800"></span> belum memiliki <strong>identitas default</strong> untuk jenis transaksi ini.
                </p>
                <div class="p-3 bg-gray-50 rounded-lg border border-gray-200 text-xs flex justify-between items-center">
                    <span class="text-gray-500">Nomor / Identitas:</span>
                    <span id="modalCustomerIdent" class="font-bold text-orange-600 font-mono text-sm"></span>
                </div>
                <p class="text-xs text-gray-500 leading-relaxed text-left bg-orange-50/60 p-3 rounded-lg border border-orange-100">
                    💡 <strong>Jadikan Default?</strong><br>
                    • <strong>Ya, Jadikan Default</strong>: Nomor ini akan otomatis disarankan saat nama customer diketik pada transaksi berikutnya.<br>
                    • <strong>Tidak</strong>: Nomor ini hanya dipakai untuk transaksi kali ini (misal kirim uang ke orang lain) dan tidak akan disarankan di pencarian.
                </p>
            </div>

            <div class="mt-6 flex flex-col gap-2">
                <button type="button" id="btnSetDefault" onclick="submitWithDefault('1')" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-medium py-2.5 px-4 rounded-xl text-sm shadow-sm transition-all flex items-center justify-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Ya, Jadikan Identitas Default
                </button>
                <button type="button" id="btnDontSetDefault" onclick="submitWithDefault('0')" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 px-4 rounded-xl text-sm border border-gray-200 transition-colors cursor-pointer">
                    Tidak (Hanya untuk Transaksi Ini)
                </button>
                <button type="button" id="btnCancelModal" onclick="closeDefaultModal()" class="mt-1 text-xs text-gray-400 hover:text-gray-600 text-center py-1 cursor-pointer">
                    Batal & Perbaiki Input
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // 1. Kalkulasi Live Total Pembayaran
    const amountInput = document.getElementById('amount');
    const adminFeeInput = document.getElementById('admin_fee');
    const totalDisplay = document.getElementById('totalDisplay');

    function updateTotal() {
        const amount = parseFloat(amountInput.value) || 0;
        const fee = parseFloat(adminFeeInput.value) || 0;
        const total = amount + fee;
        totalDisplay.textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    amountInput.addEventListener('input', updateTotal);
    adminFeeInput.addEventListener('input', updateTotal);
    updateTotal();

    // 2. Autocomplete Suggestion Identitas & Nama Customer
    const searchUrl = "{{ route('kasir.customers.search') }}";
    let searchTimer = null;

    function fetchSuggestions(query, targetInput) {
        clearTimeout(searchTimer);
        const typeId = document.getElementById('transaction_type_id').value;
        const identifierContainer = document.getElementById('identifierSuggestions');
        const nameContainer = document.getElementById('nameSuggestions');
        const container = targetInput === 'identifier' ? identifierContainer : nameContainer;

        const trimmed = (query || '').trim();
        if (!typeId || trimmed.length === 0) {
            container.innerHTML = '';
            container.classList.add('hidden');
            return;
        }

        searchTimer = setTimeout(() => {
            fetch(`${searchUrl}?transaction_type_id=${typeId}&q=${encodeURIComponent(trimmed)}`)
                .then(res => res.json())
                .then(data => {
                    if (!data || data.length === 0) {
                        container.innerHTML = '';
                        container.classList.add('hidden');
                        return;
                    }

                    container.innerHTML = '';
                    data.forEach(item => {
                        const div = document.createElement('div');
                        div.className = 'p-2.5 hover:bg-orange-50 cursor-pointer text-xs flex justify-between items-center transition-colors';
                        div.innerHTML = `
                            <span class="font-bold text-gray-800">${item.customer_identifier}</span>
                            <span class="text-orange-700 bg-orange-50 border border-orange-200 px-2 py-0.5 rounded text-[11px] font-medium">${item.customer_name}</span>
                        `;
                        div.addEventListener('click', () => {
                            document.getElementById('customer_identifier').value = item.customer_identifier;
                            document.getElementById('customer_name').value = item.customer_name;
                            document.getElementById('is_default').value = '1';
                            document.getElementById('defaultBadge').classList.remove('hidden');
                            identifierContainer.classList.add('hidden');
                            nameContainer.classList.add('hidden');
                        });
                        container.appendChild(div);
                    });
                    container.classList.remove('hidden');
                })
                .catch(() => {
                    container.innerHTML = '';
                    container.classList.add('hidden');
                });
        }, 200);
    }

    const identInput = document.getElementById('customer_identifier');
    identInput.addEventListener('input', (e) => {
        document.getElementById('is_default').value = '';
        document.getElementById('defaultBadge').classList.add('hidden');
        fetchSuggestions(e.target.value, 'identifier');
    });

    const nameInput = document.getElementById('customer_name');
    nameInput.addEventListener('input', (e) => {
        document.getElementById('is_default').value = '';
        document.getElementById('defaultBadge').classList.add('hidden');
        fetchSuggestions(e.target.value, 'name');
    });

    // Tutup dropdown jika klik di luar
    document.addEventListener('click', (e) => {
        if (!e.target.closest('#customer_identifier') && !e.target.closest('#identifierSuggestions')) {
            document.getElementById('identifierSuggestions').classList.add('hidden');
        }
        if (!e.target.closest('#customer_name') && !e.target.closest('#nameSuggestions')) {
            document.getElementById('nameSuggestions').classList.add('hidden');
        }
    });

    document.getElementById('transaction_type_id').addEventListener('change', () => {
        document.getElementById('is_default').value = '';
        document.getElementById('defaultBadge').classList.add('hidden');
        document.getElementById('identifierSuggestions').classList.add('hidden');
        document.getElementById('nameSuggestions').classList.add('hidden');
    });

    // 3. Validasi Form & Verifikasi Identitas Default
    const checkDefaultUrl = "{{ route('kasir.customers.check-default') }}";
    let isSubmitting = false;

    async function validateTransaction(event) {
        if (isSubmitting) {
            return true;
        }

        const typeSelect = document.getElementById('transaction_type_id');
        const selectedOption = typeSelect.options[typeSelect.selectedIndex];
        const typeCode = (selectedOption.getAttribute('data-code') || '').toLowerCase();
        const stampsStock = {{ $stampsStock }};

        if ((typeCode === 'materai' || typeCode === 'mtr') && stampsStock <= 0) {
            alert('Transaksi tidak dapat diproses: Stok materai habis! Silakan lakukan restock terlebih dahulu.');
            event.preventDefault();
            return false;
        }

        const defaultVal = document.getElementById('is_default').value;
        if (defaultVal !== '') {
            return true;
        }

        event.preventDefault();

        const typeId = typeSelect.value;
        const ident = document.getElementById('customer_identifier').value.trim();
        const name = document.getElementById('customer_name').value.trim();

        if (!typeId || !ident || !name) {
            isSubmitting = true;
            document.getElementById('transactionForm').submit();
            return;
        }

        try {
            const res = await fetch(`${checkDefaultUrl}?transaction_type_id=${typeId}&customer_name=${encodeURIComponent(name)}&customer_identifier=${encodeURIComponent(ident)}`);
            const data = await res.json();

            if (data.requires_verification) {
                document.getElementById('modalCustomerName').textContent = name;
                document.getElementById('modalCustomerIdent').textContent = ident;
                document.getElementById('defaultVerificationModal').classList.remove('hidden');
            } else {
                document.getElementById('is_default').value = data.is_current_default ? '1' : '0';
                isSubmitting = true;
                document.getElementById('transactionForm').submit();
            }
        } catch (err) {
            document.getElementById('is_default').value = '0';
            isSubmitting = true;
            document.getElementById('transactionForm').submit();
        }
        return false;
    }

    // Helper fungsi untuk submit modal verifikasi
    window.submitWithDefault = function(val) {
        document.getElementById('is_default').value = val;
        document.getElementById('defaultVerificationModal').classList.add('hidden');
        isSubmitting = true;
        const form = document.getElementById('transactionForm');
        form.submit();
    };

    window.closeDefaultModal = function() {
        document.getElementById('defaultVerificationModal').classList.add('hidden');
        document.getElementById('is_default').value = '';
        isSubmitting = false;
    };
</script>
@endsection
