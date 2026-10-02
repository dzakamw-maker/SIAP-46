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
                    
                    @php
                        $selectedTypeId = old('transaction_type_id', request('transaction_type_id', $types->first()?->id));
                        $selectedTypeModel = $types->firstWhere('id', $selectedTypeId) ?? $types->first();
                        $initialIsMaterai = $selectedTypeModel && (str_contains(strtolower($selectedTypeModel->code ?? ''), 'materai') || str_contains(strtolower($selectedTypeModel->code ?? ''), 'mtr') || str_contains(strtolower($selectedTypeModel->name ?? ''), 'materai'));
                        $initialIsSpp = $selectedTypeModel && (str_contains(strtolower($selectedTypeModel->code ?? ''), 'spp') || str_contains(strtolower($selectedTypeModel->name ?? ''), 'spp'));
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <!-- Jenis Transaksi -->
                        <div id="transactionTypeWrapper" class="transition-all {{ $initialIsMaterai ? 'md:col-span-2' : '' }}">
                            <label for="transaction_type_id" class="block mb-2 text-sm font-medium text-gray-700">Jenis Transaksi</label>
                            <select name="transaction_type_id" id="transaction_type_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5 @error('transaction_type_id') border-red-500 @enderror" required>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}" data-code="{{ strtolower($type->code) }}" {{ $selectedTypeId == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('transaction_type_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Identitas Customer -->
                        <div class="relative transition-all {{ $initialIsMaterai ? 'hidden' : '' }}" id="customerIdentifierWrapper">
                            <label for="customer_identifier" class="flex items-center mb-2 text-sm font-medium text-gray-700">
                                <span id="identifierTitle">{{ $initialIsSpp ? 'Identitas Siswa (NIS/NISN)' : 'Identitas Customer' }}</span>
                                <span class="text-xs text-gray-400 font-normal ml-1" id="identifierHint">{{ $initialIsSpp ? '(Nomor VA: 98844565 + NIS)' : '(No. Rek / No. HP / IDPEL)' }}</span>
                                <span id="defaultBadge" class="hidden ml-2 text-[10px] text-green-700 bg-green-50 border border-green-200 px-2 py-0.5 rounded-full font-medium">Default</span>
                            </label>

                            @php
                                $displayIdent = old('customer_identifier');
                                if ($initialIsSpp && $displayIdent && str_starts_with($displayIdent, '98844565')) {
                                    $displayIdent = substr($displayIdent, 8);
                                }
                            @endphp

                            <div class="relative flex rounded-lg">
                                <span id="sppPrefixAddon" class="items-center px-3.5 rounded-l-lg border border-r-0 border-gray-300 bg-gray-100 text-gray-700 font-mono text-sm font-bold select-none tracking-wider transition-all {{ $initialIsSpp ? 'inline-flex' : 'hidden' }}" style="{{ $initialIsSpp ? 'display: inline-flex;' : 'display: none !important;' }}">
                                    98844565
                                </span>
                                <input type="text" name="customer_identifier" id="customer_identifier" value="{{ $displayIdent }}" autocomplete="off" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5 transition-all {{ $initialIsSpp ? 'rounded-r-lg font-mono' : 'rounded-lg' }} @error('customer_identifier') border-red-500 @enderror" placeholder="{{ $initialIsSpp ? 'Ketik NIS siswa (contoh: 12511177)...' : 'Ketik nomor / cari identitas...' }}" {{ $initialIsMaterai ? '' : 'required' }}>
                            </div>

                            <div id="identifierSuggestions" class="absolute left-0 right-0 z-30 bg-white border border-gray-200 rounded-lg shadow-lg mt-1 hidden max-h-48 overflow-y-auto divide-y divide-gray-100"></div>
                            @error('customer_identifier')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Nama Customer -->
                    <div class="relative mb-4">
                        <label for="customer_name" id="nameLabel" class="block mb-2 text-sm font-medium text-gray-700">{{ $initialIsSpp ? 'Nama Siswa' : 'Nama Customer' }}</label>
                        <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}" autocomplete="off" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5 @error('customer_name') border-red-500 @enderror" placeholder="{{ $initialIsSpp ? 'Nama siswa otomatis terisi atau ketik manual...' : 'Nama otomatis terisi atau ketik manual...' }}" required>
                        <div id="nameSuggestions" class="absolute left-0 right-0 z-30 bg-white border border-gray-200 rounded-lg shadow-lg mt-1 hidden max-h-48 overflow-y-auto divide-y divide-gray-100"></div>
                        @error('customer_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Regular: Nominal Uang & Biaya Admin -->
                    <div id="regularAmountWrapper" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 {{ $initialIsMaterai ? 'hidden' : '' }}">
                        <div>
                            <label for="amount" id="amountLabel" class="block mb-2 text-sm font-medium text-gray-700">{{ $initialIsSpp ? 'Nominal SPP (Rp)' : 'Nominal Uang (Rp)' }}</label>
                            <input type="number" name="amount" id="amount" value="{{ old('amount') }}" min="0" step="any" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5 @error('amount') border-red-500 @enderror" placeholder="0" {{ $initialIsMaterai ? '' : 'required' }}>
                            @error('amount')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="admin_fee" class="block mb-2 text-sm font-medium text-gray-700">Biaya Admin (Rp)</label>
                            <input type="number" name="admin_fee" id="admin_fee" value="{{ old('admin_fee') }}" min="0" step="any" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5 @error('admin_fee') border-red-500 @enderror" placeholder="0">
                            @error('admin_fee')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Materai: Jumlah Pembelian & Harga Satuan -->
                    <div id="materaiAmountWrapper" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 {{ $initialIsMaterai ? '' : 'hidden' }}">
                        <div>
                            <label for="stamp_quantity" class="block mb-2 text-sm font-medium text-gray-700">
                                <span>Jumlah Pembelian</span>
                                <span class="text-xs text-gray-400 font-normal ml-1">(pcs)</span>
                            </label>
                            <input type="number" name="stamp_quantity" id="stamp_quantity" value="{{ old('stamp_quantity') }}" min="1" step="1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5 @error('stamp_quantity') border-red-500 @enderror" placeholder="0" {{ $initialIsMaterai ? 'required' : '' }}>
                            @error('stamp_quantity')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="stamp_price" class="block mb-2 text-sm font-medium text-gray-700">
                                <span>Harga Jual per pcs (Rp)</span>
                                <span class="text-xs text-orange-600 bg-orange-50 border border-orange-200 px-1.5 py-0.5 rounded font-medium ml-1">Default Rp 11.000</span>
                            </label>
                            <input type="number" name="stamp_price" id="stamp_price" value="{{ old('stamp_price', 11000) }}" min="0" step="any" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5 @error('stamp_price') border-red-500 @enderror" placeholder="11000">
                            @error('stamp_price')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Catatan Tambahan (Opsional) -->
                    <div class="mb-4">
                        <label for="notes" class="block mb-2 text-sm font-medium text-gray-700">Catatan <span class="text-xs text-gray-400 font-normal">(Opsional)</span></label>
                        <input type="text" name="notes" id="notes" value="{{ old('notes') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5" placeholder="{{ $initialIsSpp ? 'Contoh: SPP Bulan Oktober / Kelas XI RPL 1' : 'Contoh: Transaksi reguler / transfer via kartu BNI' }}">
                    </div>

                    <!-- Total Pembayaran Banner -->
                    <div class="mb-6 p-4 bg-orange-50 rounded-xl border border-orange-100 shadow-2xs">
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-xs text-orange-700 uppercase tracking-wider font-semibold block">Total Pembayaran Customer</span>
                                <span id="totalSubtitle" class="text-xs text-gray-500 font-normal">{{ $initialIsMaterai ? '(Jumlah Pembelian × Harga Satuan)' : '(Nominal + Biaya Admin)' }}</span>
                            </div>
                            <span id="totalDisplay" class="text-2xl font-black text-orange-600">Rp 0</span>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="reset" onclick="setTimeout(() => { updateTotal(); syncTransactionType(); }, 50)" class="text-gray-600 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 transition-colors">
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
    // 1. Elemen DOM Utama
    const typeSelect = document.getElementById('transaction_type_id');
    const identWrapper = document.getElementById('customerIdentifierWrapper');
    const typeWrapper = document.getElementById('transactionTypeWrapper');
    const identInput = document.getElementById('customer_identifier');
    const nameInput = document.getElementById('customer_name');
    const amountInput = document.getElementById('amount');
    const adminFeeInput = document.getElementById('admin_fee');
    const stampQtyInput = document.getElementById('stamp_quantity');
    const stampPriceInput = document.getElementById('stamp_price');
    const regularAmountWrapper = document.getElementById('regularAmountWrapper');
    const materaiAmountWrapper = document.getElementById('materaiAmountWrapper');
    const totalDisplay = document.getElementById('totalDisplay');
    const totalSubtitle = document.getElementById('totalSubtitle');

    function checkIsMaterai(option) {
        if (!option) return false;
        const code = (option.getAttribute('data-code') || '').toLowerCase();
        const text = (option.textContent || option.innerText || '').toLowerCase();
        return code.includes('materai') || code.includes('mtr') || text.includes('materai');
    }

    function checkIsSpp(option) {
        if (!option) return false;
        const code = (option.getAttribute('data-code') || '').toLowerCase();
        const text = (option.textContent || option.innerText || '').toLowerCase();
        return code.includes('spp') || text.includes('spp');
    }

    // 2. Kalkulasi Live Total Pembayaran
    function updateTotal() {
        const selectedOption = typeSelect ? typeSelect.options[typeSelect.selectedIndex] : null;
        const isMaterai = checkIsMaterai(selectedOption);

        if (isMaterai) {
            const qty = parseFloat(stampQtyInput ? stampQtyInput.value : 0) || 0;
            const price = parseFloat(stampPriceInput ? stampPriceInput.value : 0) || 0;
            const total = qty * price;
            totalDisplay.textContent = 'Rp ' + total.toLocaleString('id-ID');
            if (totalSubtitle) {
                totalSubtitle.textContent = '(Jumlah Pembelian × Harga Satuan)';
            }
            if (amountInput) {
                amountInput.value = total > 0 ? total : '';
            }
            if (adminFeeInput) {
                adminFeeInput.value = '';
            }
        } else {
            const amount = parseFloat(amountInput.value) || 0;
            const fee = parseFloat(adminFeeInput.value) || 0;
            const total = amount + fee;
            totalDisplay.textContent = 'Rp ' + total.toLocaleString('id-ID');
            if (totalSubtitle) {
                totalSubtitle.textContent = '(Nominal + Biaya Admin)';
            }
        }
    }

    amountInput.addEventListener('input', updateTotal);
    adminFeeInput.addEventListener('input', updateTotal);
    if (stampQtyInput) {
        stampQtyInput.addEventListener('input', updateTotal);
    }
    if (stampPriceInput) {
        stampPriceInput.addEventListener('input', updateTotal);
    }

    // 3. Autocomplete Suggestion Identitas & Nama Customer
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
                        
                        const selectedOption = typeSelect ? typeSelect.options[typeSelect.selectedIndex] : null;
                        const isSpp = checkIsSpp(selectedOption);

                        let identDisplay = item.customer_identifier;
                        if (isSpp && item.customer_identifier.startsWith('98844565')) {
                            identDisplay = `<span class="text-gray-400 font-mono font-medium">98844565</span><span class="font-bold font-mono text-orange-600">${item.customer_identifier.substring(8)}</span>`;
                        } else {
                            identDisplay = `<span class="font-bold text-gray-800">${item.customer_identifier}</span>`;
                        }

                        div.innerHTML = `
                            <div class="flex items-center gap-1">${identDisplay}</div>
                            <span class="text-orange-700 bg-orange-50 border border-orange-200 px-2 py-0.5 rounded text-[11px] font-medium">${item.customer_name}</span>
                        `;
                        div.addEventListener('click', () => {
                            let identVal = item.customer_identifier;
                            if (isSpp && identVal.startsWith('98844565')) {
                                identVal = identVal.substring(8);
                            }
                            document.getElementById('customer_identifier').value = identVal;
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

    identInput.addEventListener('input', (e) => {
        const selectedOption = typeSelect ? typeSelect.options[typeSelect.selectedIndex] : null;
        const isSpp = checkIsSpp(selectedOption);

        if (isSpp && identInput.value.startsWith('98844565')) {
            identInput.value = identInput.value.substring(8);
        }

        document.getElementById('is_default').value = '';
        document.getElementById('defaultBadge').classList.add('hidden');
        fetchSuggestions(e.target.value, 'identifier');
    });

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

    // 4. Toggle Tampilan Jenis Transaksi (Materai vs SPP vs Reguler)
    function syncTransactionType() {
        const selectedOption = typeSelect.options[typeSelect.selectedIndex];
        const isMaterai = checkIsMaterai(selectedOption);
        const isSpp = checkIsSpp(selectedOption);

        const identHint = document.getElementById('identifierHint');
        const identTitle = document.getElementById('identifierTitle');
        const nameLabel = document.getElementById('nameLabel');
        const amountLabel = document.getElementById('amountLabel');
        const notesInput = document.getElementById('notes');

        if (isMaterai) {
            identWrapper.classList.add('hidden');
            typeWrapper.classList.add('md:col-span-2');
            identInput.removeAttribute('required');
            identInput.value = '';

            if (regularAmountWrapper) regularAmountWrapper.classList.add('hidden');
            if (materaiAmountWrapper) materaiAmountWrapper.classList.remove('hidden');

            amountInput.removeAttribute('required');
            if (stampQtyInput) stampQtyInput.setAttribute('required', 'required');

            const sppPrefixAddon = document.getElementById('sppPrefixAddon');
            if (sppPrefixAddon) {
                sppPrefixAddon.classList.add('hidden');
                sppPrefixAddon.classList.remove('inline-flex');
                sppPrefixAddon.style.display = 'none';
            }
        } else {
            identWrapper.classList.remove('hidden');
            typeWrapper.classList.remove('md:col-span-2');
            identInput.setAttribute('required', 'required');

            if (regularAmountWrapper) regularAmountWrapper.classList.remove('hidden');
            if (materaiAmountWrapper) materaiAmountWrapper.classList.add('hidden');

            amountInput.setAttribute('required', 'required');
            if (stampQtyInput) stampQtyInput.removeAttribute('required');

            const sppPrefixAddon = document.getElementById('sppPrefixAddon');
            if (isSpp) {
                if (sppPrefixAddon) {
                    sppPrefixAddon.classList.remove('hidden');
                    sppPrefixAddon.classList.add('inline-flex');
                    sppPrefixAddon.style.display = 'inline-flex';
                }
                identInput.classList.remove('rounded-lg');
                identInput.classList.add('rounded-r-lg', 'font-mono');
                if (identTitle) identTitle.textContent = 'Identitas Siswa (NIS/NISN)';
                if (identHint) identHint.textContent = '(Nomor VA: 98844565 + NIS)';
                identInput.placeholder = 'Ketik NIS siswa (contoh: 12511177)...';
                if (nameLabel) nameLabel.textContent = 'Nama Siswa';
                nameInput.placeholder = 'Nama siswa otomatis terisi atau ketik manual...';
                if (amountLabel) amountLabel.textContent = 'Nominal SPP (Rp)';
                if (notesInput && (!notesInput.value || notesInput.value === '')) {
                    notesInput.placeholder = 'Contoh: SPP Bulan Oktober / Kelas XI RPL 1';
                }
                if (identInput.value.startsWith('98844565')) {
                    identInput.value = identInput.value.substring(8);
                }
            } else {
                if (sppPrefixAddon) {
                    sppPrefixAddon.classList.add('hidden');
                    sppPrefixAddon.classList.remove('inline-flex');
                    sppPrefixAddon.style.display = 'none';
                }
                identInput.classList.add('rounded-lg');
                identInput.classList.remove('rounded-r-lg', 'font-mono');
                if (identTitle) identTitle.textContent = 'Identitas Customer';
                if (identHint) identHint.textContent = '(No. Rek / No. HP / IDPEL)';
                identInput.placeholder = 'Ketik nomor / cari identitas...';
                if (nameLabel) nameLabel.textContent = 'Nama Customer';
                nameInput.placeholder = 'Nama otomatis terisi atau ketik manual...';
                if (amountLabel) amountLabel.textContent = 'Nominal Uang (Rp)';
                if (notesInput && (!notesInput.value || notesInput.value === '')) {
                    notesInput.placeholder = 'Contoh: Transaksi reguler / transfer via kartu BNI';
                }
            }
        }

        document.getElementById('is_default').value = '';
        document.getElementById('defaultBadge').classList.add('hidden');
        document.getElementById('identifierSuggestions').classList.add('hidden');
        document.getElementById('nameSuggestions').classList.add('hidden');

        updateTotal();
    }

    typeSelect.addEventListener('change', syncTransactionType);
    syncTransactionType();

    // 5. Validasi Form & Verifikasi Identitas Default
    const checkDefaultUrl = "{{ route('kasir.customers.check-default') }}";
    let isSubmitting = false;

    async function validateTransaction(event) {
        if (isSubmitting) {
            return true;
        }

        const selectedOption = typeSelect.options[typeSelect.selectedIndex];
        const isMaterai = checkIsMaterai(selectedOption);
        const isSpp = checkIsSpp(selectedOption);
        const stampsStock = {{ $stampsStock }};

        if (isMaterai) {
            const qty = parseInt(stampQtyInput ? stampQtyInput.value : 0, 10) || 0;
            if (qty <= 0) {
                alert('Silakan masukkan jumlah pembelian materai (minimal 1 pcs).');
                if (stampQtyInput) stampQtyInput.focus();
                event.preventDefault();
                return false;
            }

            if (stampsStock <= 0) {
                alert('Transaksi tidak dapat diproses: Stok materai habis! Silakan lakukan restock terlebih dahulu.');
                event.preventDefault();
                return false;
            }

            if (qty > stampsStock) {
                alert(`Transaksi tidak dapat diproses: Stok materai tidak mencukupi! Sisa stok saat ini: ${stampsStock} pcs, jumlah diminta: ${qty} pcs.`);
                if (stampQtyInput) stampQtyInput.focus();
                event.preventDefault();
                return false;
            }

            updateTotal();
            isSubmitting = true;
            return true;
        }

        const defaultVal = document.getElementById('is_default').value;
        if (defaultVal !== '') {
            return true;
        }

        event.preventDefault();

        const typeId = typeSelect.value;
        const ident = identInput.value.trim();
        const name = document.getElementById('customer_name').value.trim();

        if (!typeId || !ident || !name) {
            isSubmitting = true;
            document.getElementById('transactionForm').submit();
            return;
        }

        const fullIdent = (isSpp && !ident.startsWith('98844565')) ? ('98844565' + ident) : ident;

        try {
            const res = await fetch(`${checkDefaultUrl}?transaction_type_id=${typeId}&customer_name=${encodeURIComponent(name)}&customer_identifier=${encodeURIComponent(fullIdent)}`);
            const data = await res.json();

            if (data.requires_verification) {
                document.getElementById('modalCustomerName').textContent = name;
                document.getElementById('modalCustomerIdent').textContent = fullIdent;
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
