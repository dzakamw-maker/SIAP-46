@extends('layouts.app')

@section('title', 'Beranda - Agen BNI 46 Sekolah | SIAP-46')

@section('content')
<div class="min-h-screen flex flex-col bg-slate-50 text-slate-800">

    <!-- Top Notification / Info Bar -->
    <div class="bg-gradient-to-r from-teal-900 to-teal-800 text-white text-xs py-2 px-4 border-b border-teal-700/50">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-2 text-center sm:text-left">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5 animate-pulse"></span>
                    Loket Buka
                </span>
                <span class="text-teal-100">Mini Bank & Agen BNI 46 Sekolah • Gedung Business Center (Lantai 1)</span>
            </div>
            <div class="flex items-center gap-4 text-teal-200 text-[11px]">
                <span>Senin - Jumat: 07.30 - 15.30 WIB</span>
                <span class="hidden md:inline">• Layanan Resmi Civitas Sekolah</span>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-slate-200/80 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-18">
                <!-- Brand / Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center text-white font-bold shadow-md shadow-orange-500/20 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xl font-bold tracking-tight text-slate-900">SIAP<span class="text-orange-600">-46</span></span>
                            <span class="text-[10px] font-semibold uppercase tracking-wider bg-teal-50 text-teal-700 px-2 py-0.5 rounded-full border border-teal-200">Agen BNI</span>
                        </div>
                        <p class="text-xs text-slate-500 font-medium hidden sm:block">Sistem Informasi & Transaksi Agen Sekolah</p>
                    </div>
                </a>

                <!-- Desktop Nav Links -->
                <nav class="hidden md:flex items-center gap-6 text-sm font-medium text-slate-600">
                    <a href="#beranda" class="hover:text-teal-700 transition-colors">Beranda</a>
                    <a href="#layanan" class="hover:text-teal-700 transition-colors">Daftar Layanan</a>
                    <a href="#simulasi" class="hover:text-teal-700 transition-colors">Simulasi Biaya</a>
                    <a href="#jadwal" class="hover:text-teal-700 transition-colors">Jadwal & Lokasi</a>
                    <a href="#faq" class="hover:text-teal-700 transition-colors">FAQ</a>
                </nav>

                <!-- Auth Buttons -->
                <div class="flex items-center gap-3">
                    @auth
                        @if(auth()->user()->role && auth()->user()->role->name === 'Admin')
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-teal-700 hover:bg-teal-800 rounded-lg shadow-sm transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                <span>Panel Admin</span>
                            </a>
                        @else
                            <a href="{{ route('kasir.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-orange-600 hover:bg-orange-700 rounded-lg shadow-sm transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                <span>Panel Kasir</span>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-slate-700 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors border border-slate-200">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <span>Login Petugas</span>
                        </a>
                    @endauth

                    <!-- Mobile Menu Button -->
                    <button type="button" onclick="document.getElementById('mobileMenu').classList.toggle('hidden')" class="md:hidden p-2 text-slate-600 hover:text-slate-900 rounded-lg focus:outline-none" aria-label="Menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div id="mobileMenu" class="hidden md:hidden border-t border-slate-200 bg-white px-4 pt-3 pb-5 space-y-2">
            <a href="#beranda" onclick="document.getElementById('mobileMenu').classList.add('hidden')" class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-100">Beranda</a>
            <a href="#layanan" onclick="document.getElementById('mobileMenu').classList.add('hidden')" class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-100">Daftar Layanan</a>
            <a href="#simulasi" onclick="document.getElementById('mobileMenu').classList.add('hidden')" class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-100">Simulasi Biaya</a>
            <a href="#jadwal" onclick="document.getElementById('mobileMenu').classList.add('hidden')" class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-100">Jadwal & Lokasi</a>
            <a href="#faq" onclick="document.getElementById('mobileMenu').classList.add('hidden')" class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-100">FAQ</a>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="beranda" class="relative overflow-hidden bg-gradient-to-b from-teal-50/70 via-white to-slate-50 pt-12 pb-16 lg:pt-20 lg:pb-24 border-b border-slate-200/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <!-- Hero Left: Text & CTA -->
                <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-orange-100 text-orange-800 text-xs font-semibold border border-orange-200">
                        <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                        Mitra Resmi Agen BNI 46 • Teaching Factory
                    </div>
                    
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        Layanan Perbankan & Pembayaran Digital <span class="text-teal-700 underline decoration-orange-500 decoration-wavy decoration-2">Mudah di Sekolah</span>
                    </h1>

                    <p class="text-base sm:text-lg text-slate-600 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                        Kini siswa, guru, dan staf tidak perlu izin keluar gerbang sekolah untuk setor tunai, tarik uang, top-up e-wallet, atau bayar tagihan. Cukup kunjungi loket Agen BNI 46 sekolah kami!
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-2">
                        <a href="#layanan" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 text-base font-semibold text-white bg-teal-700 hover:bg-teal-800 rounded-xl shadow-md shadow-teal-700/20 transition-all hover:-translate-y-0.5">
                            <span>Lihat Layanan Tersedia</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </a>
                        <a href="#simulasi" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 text-base font-semibold text-slate-700 bg-white hover:bg-slate-100 rounded-xl border border-slate-300 shadow-xs transition-all hover:-translate-y-0.5">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                            <span>Cek Biaya Admin</span>
                        </a>
                    </div>

                    <!-- Trust Indicators -->
                    <div class="pt-6 grid grid-cols-3 gap-4 border-t border-slate-200/80 text-center lg:text-left">
                        <div>
                            <div class="text-2xl font-bold text-slate-900">100%</div>
                            <div class="text-xs text-slate-500 font-medium">Resmi & Tercatat</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-orange-600">Instan</div>
                            <div class="text-xs text-slate-500 font-medium">Struk Bukti Langsung</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-teal-700">Rp 0</div>
                            <div class="text-xs text-slate-500 font-medium">Tanpa Antre Luar</div>
                        </div>
                    </div>
                </div>

                <!-- Hero Right: Interactive Loket Card -->
                <div class="lg:col-span-5">
                    <div class="relative mx-auto max-w-md bg-white rounded-2xl p-6 sm:p-8 shadow-xl shadow-slate-200/60 border border-slate-200/80">
                        <div class="absolute -top-3 right-6 bg-orange-600 text-white text-[11px] font-bold uppercase tracking-wider px-3 py-1 rounded-full shadow-sm">
                            Loket Aktif Hari Ini
                        </div>

                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-xl bg-teal-100 text-teal-800 flex items-center justify-center font-bold">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" /></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">Loket Mini Bank Siswa</h3>
                                <p class="text-xs text-slate-500">Operasional Praktik Siswa Kejuruan</p>
                            </div>
                        </div>

                        <div class="space-y-3.5 mb-6 text-sm">
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-slate-500">Status Loket:</span>
                                <span class="inline-flex items-center gap-1.5 font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-200 text-xs">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    Siap Melayani
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-slate-500">Jam Layanan:</span>
                                <span class="font-semibold text-slate-800">07.30 - 15.30 WIB</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-slate-500">Lokasi:</span>
                                <span class="font-semibold text-slate-800 text-right">Lab Bank Mini / BC Lt. 1</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <span class="text-slate-500">Ketersediaan Materai:</span>
                                <span class="font-semibold text-teal-700">Tersedia (Rp 10.000)</span>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-orange-50 to-amber-50 p-4 rounded-xl border border-orange-200/70 text-xs text-orange-950 space-y-1">
                            <div class="font-bold flex items-center gap-1.5 text-orange-900">
                                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Informasi Siswa & Guru:
                            </div>
                            <p class="text-slate-600">
                                Transaksi dapat dilakukan tunai langsung di kasir loket. Bukti struk pembayaran langsung dicetak saat itu juga.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Keunggulan Section -->
    <section class="py-12 bg-white border-b border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <div class="p-5 rounded-xl bg-slate-50 border border-slate-200 hover:border-teal-300 transition-colors">
                    <div class="w-10 h-10 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </div>
                    <h4 class="font-bold text-slate-900 mb-1">Mitra Resmi BNI</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">Terhubung langsung ke jaringan Bank BNI dan perbankan nasional yang aman.</p>
                </div>

                <div class="p-5 rounded-xl bg-slate-50 border border-slate-200 hover:border-orange-300 transition-colors">
                    <div class="w-10 h-10 rounded-lg bg-orange-100 text-orange-700 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h4 class="font-bold text-slate-900 mb-1">Hemat Waktu & Tenaga</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">Cukup saat jam istirahat atau sepulang sekolah tanpa perlu repot pergi ke luar.</p>
                </div>

                <div class="p-5 rounded-xl bg-slate-50 border border-slate-200 hover:border-teal-300 transition-colors">
                    <div class="w-10 h-10 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                    <h4 class="font-bold text-slate-900 mb-1">Biaya Terjangkau</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">Biaya administrasi standar Agen 46, transparan dan bersahabat bagi pelajar.</p>
                </div>

                <div class="p-5 rounded-xl bg-slate-50 border border-slate-200 hover:border-orange-300 transition-colors">
                    <div class="w-10 h-10 rounded-lg bg-orange-100 text-orange-700 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                    </div>
                    <h4 class="font-bold text-slate-900 mb-1">Teaching Factory SMK</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">Memberikan pengalaman kerja nyata bagi siswa dalam operasional kasir perbankan.</p>
                </div>

            </div>
        </div>
    </section>

    <!-- Katalog Layanan Section -->
    <section id="layanan" class="py-16 lg:py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-14">
                <span class="text-xs font-bold uppercase tracking-wider text-orange-600 bg-orange-100 px-3 py-1 rounded-full border border-orange-200">Katalog Layanan</span>
                <h2 class="text-3xl font-extrabold text-slate-900 mt-3">Layanan Lengkap yang Siap Membantu Anda</h2>
                <p class="text-sm sm:text-base text-slate-600 mt-2">Semua transaksi diproses secara real-time dan terverifikasi dengan tanda terima cetak resmi.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <!-- Category 1: Perbankan -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold border border-teal-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-base">Transaksi Perbankan</h3>
                            <p class="text-xs text-slate-500">Layanan rekening BNI & Bank Lain</p>
                        </div>
                    </div>
                    <ul class="space-y-3 text-sm text-slate-600">
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <div>
                                <span class="font-semibold text-slate-800 block">Setor Tunai BNI</span>
                                <span class="text-xs text-slate-500">Langsung masuk ke rekening tabungan siswa/guru</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <div>
                                <span class="font-semibold text-slate-800 block">Setor Tunai Antar Bank</span>
                                <span class="text-xs text-slate-500">Transfer ke BCA, BRI, Mandiri, BSI, dll</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <div>
                                <span class="font-semibold text-slate-800 block">Tarik Tunai BNI</span>
                                <span class="text-xs text-slate-500">Ambil uang saku/kiriman praktis di loket</span>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Category 2: E-Wallet -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-700 flex items-center justify-center font-bold border border-orange-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-base">Top Up Dompet Digital</h3>
                            <p class="text-xs text-slate-500">Pengisian saldo instan</p>
                        </div>
                    </div>
                    <ul class="space-y-3 text-sm text-slate-600">
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <div>
                                <span class="font-semibold text-slate-800 block">DANA & ShopeePay (SPay)</span>
                                <span class="text-xs text-slate-500">Top-up cepat cukup sebutkan no. handphone</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <div>
                                <span class="font-semibold text-slate-800 block">GoPay & OVO</span>
                                <span class="text-xs text-slate-500">Isi saldo untuk transportasi online & jajan</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <div>
                                <span class="font-semibold text-slate-800 block">LinkAja</span>
                                <span class="text-xs text-slate-500">Dukungan saldo transaksi dompet digital lainnya</span>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Category 3: Listrik & Utilitas -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center font-bold border border-amber-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-base">Tagihan Listrik & Utilitas</h3>
                            <p class="text-xs text-slate-500">PLN, BPJS, PDAM, & Telkom</p>
                        </div>
                    </div>
                    <ul class="space-y-3 text-sm text-slate-600">
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <div>
                                <span class="font-semibold text-slate-800 block">Token Listrik (PLN Prepaid)</span>
                                <span class="text-xs text-slate-500">Nominal 20rb s/d 1jt kode token langsung terbit</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <div>
                                <span class="font-semibold text-slate-800 block">Tagihan PLN Pascabayar & BPJS</span>
                                <span class="text-xs text-slate-500">Cek tagihan & bayar tanpa denda keterlambatan</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <div>
                                <span class="font-semibold text-slate-800 block">PDAM & IndiHome/Telkom</span>
                                <span class="text-xs text-slate-500">Pembayaran rekening air dan internet bulanan</span>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Category 4: Pulsa & Paket Data -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold border border-blue-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" /></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-base">Pulsa & Paket Data</h3>
                            <p class="text-xs text-slate-500">Semua operator seluler di Indonesia</p>
                        </div>
                    </div>
                    <ul class="space-y-3 text-sm text-slate-600">
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <div>
                                <span class="font-semibold text-slate-800 block">Telkomsel & By.U</span>
                                <span class="text-xs text-slate-500">Pulsa reguler dan paket kuota data belajar</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <div>
                                <span class="font-semibold text-slate-800 block">Indosat IM3 & Tri (3)</span>
                                <span class="text-xs text-slate-500">Pengisian pulsa cepat masuk detik itu juga</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <div>
                                <span class="font-semibold text-slate-800 block">XL Axiata, Axis & Smartfren</span>
                                <span class="text-xs text-slate-500">Pilihan nominal lengkap 5.000 s/d 100.000</span>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Category 5: Materai & Administrasi -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center font-bold border border-purple-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-base">Bea Meterai Resmi</h3>
                            <p class="text-xs text-slate-500">Kelengkapan dokumen sekolah & PKL</p>
                        </div>
                    </div>
                    <ul class="space-y-3 text-sm text-slate-600">
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <div>
                                <span class="font-semibold text-slate-800 block">Materai Tempel Rp 10.000</span>
                                <span class="text-xs text-slate-500">Materai fisik resmi dari Kantor Pos / Peruri</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <div>
                                <span class="font-semibold text-slate-800 block">Berkas PKL / Prakerin</span>
                                <span class="text-xs text-slate-500">Surat perjanjian kerjasama industri & magang</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <div>
                                <span class="font-semibold text-slate-800 block">Surat Pernyataan & Beasiswa</span>
                                <span class="text-xs text-slate-500">Tersedia langsung di loket kasir tanpa ke kantor pos</span>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Category 6: Info Kasir Internal -->
                <div class="bg-gradient-to-br from-teal-900 to-teal-800 rounded-2xl p-6 text-white shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-xl bg-white/10 text-white flex items-center justify-center font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-white text-base">Portal Petugas Kasir</h3>
                                <p class="text-xs text-teal-200">Khusus Siswa & Guru Pembimbing</p>
                            </div>
                        </div>
                        <p class="text-xs text-teal-100 leading-relaxed mb-6">
                            Siswa bertugas kasir dan guru pembimbing dapat masuk ke sistem untuk input transaksi nasabah, cetak struk, rekap presensi, dan verifikasi tutup buku harian (EOD).
                        </p>
                    </div>

                    <a href="{{ route('login') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-teal-950 bg-white hover:bg-teal-50 rounded-xl shadow-xs transition-colors">
                        <span>Masuk ke Sistem Kasir</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- Simulator Biaya Admin Section -->
    <section id="simulasi" class="py-16 lg:py-20 bg-white border-y border-slate-200">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-10">
                <span class="text-xs font-bold uppercase tracking-wider text-teal-700 bg-teal-50 px-3 py-1 rounded-full border border-teal-200">Kalkulator Interaktif</span>
                <h2 class="text-3xl font-extrabold text-slate-900 mt-3">Simulasi & Cek Biaya Admin</h2>
                <p class="text-sm text-slate-600 mt-2">Pilih jenis layanan di bawah ini untuk mengetahui estimasi biaya administrasi secara transparan.</p>
            </div>

            <div class="bg-slate-50 rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                    
                    <!-- Left: Form Input -->
                    <div class="space-y-5">
                        <div>
                            <label for="serviceType" class="block text-sm font-semibold text-slate-800 mb-2">Pilih Jenis Transaksi</label>
                            <select id="serviceType" onchange="updateSimulation()" class="w-full bg-white border border-slate-300 text-slate-800 text-sm rounded-xl focus:ring-teal-600 focus:border-teal-600 block p-3 shadow-xs">
                                <option value="setor_bni" data-fee="2500" data-note="Cukup bawa uang tunai & sebutkan 10 digit nomor rekening BNI tujuan.">Setor Tunai Rekening BNI</option>
                                <option value="setor_antar_bank" data-fee="6500" data-note="Berlaku untuk transfer ke bank lain (BCA, BRI, Mandiri, dll). Uang masuk seketika.">Setor Tunai Antar Bank (Transfer)</option>
                                <option value="tarik_bni" data-fee="3000" data-note="Menggunakan kartu debit BNI melalui terminal EDC resmi.">Tarik Tunai Kartu BNI</option>
                                <option value="topup_ewallet" data-fee="2000" data-note="Tersedia untuk DANA, ShopeePay, GoPay, dan OVO. Cukup berikan nomor telepon akun.">Top Up E-Wallet (DANA / ShopeePay / GoPay)</option>
                                <option value="pln_token" data-fee="3000" data-note="Sebutkan 11-12 digit Nomor Meter / ID Pelanggan PLN. 20 digit token langsung tercetak di struk.">Token Listrik PLN (Prepaid)</option>
                                <option value="bpjs" data-fee="2500" data-note="Pembayaran iuran BPJS Kesehatan per bulan keluarga. Cukup bawa Nomor Kartu BPJS.">Iuran BPJS Kesehatan</option>
                                <option value="materai" data-fee="0" data-price="10000" data-note="Harga materai resmi Rp 10.000 / keping tanpa biaya tambahan.">Materai Resmi Rp 10.000</option>
                                <option value="pulsa" data-fee="1500" data-note="Tersedia semua operator (Telkomsel, Indosat, XL, Tri, Smartfren).">Pulsa Seluler Semua Operator</option>
                            </select>
                        </div>

                        <div>
                            <label for="simAmount" class="block text-sm font-semibold text-slate-800 mb-2">Nominal Transaksi (Rp)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 font-semibold text-sm">Rp</span>
                                <input type="number" id="simAmount" value="50000" min="5000" step="5000" oninput="updateSimulation()" class="w-full bg-white border border-slate-300 text-slate-800 text-sm rounded-xl focus:ring-teal-600 focus:border-teal-600 block pl-10 p-3 shadow-xs" placeholder="50.000">
                            </div>
                            <p class="text-xs text-slate-400 mt-1.5">*Ketik nominal uang yang ingin ditransaksikan</p>
                        </div>
                    </div>

                    <!-- Right: Live Result Card -->
                    <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-sm space-y-4">
                        <div class="text-xs uppercase font-bold tracking-wider text-slate-400">Rincian Estimasi Biaya</div>
                        
                        <div class="space-y-2.5 text-sm">
                            <div class="flex justify-between items-center text-slate-600">
                                <span>Nominal Layanan:</span>
                                <span id="resAmount" class="font-medium text-slate-900">Rp 50.000</span>
                            </div>
                            <div class="flex justify-between items-center text-slate-600">
                                <span>Biaya Admin Agen:</span>
                                <span id="resFee" class="font-semibold text-orange-600">Rp 2.500</span>
                            </div>
                            <div class="pt-3 border-t border-slate-100 flex justify-between items-center text-base">
                                <span class="font-bold text-slate-900">Total Pembayaran:</span>
                                <span id="resTotal" class="font-extrabold text-teal-700 text-lg sm:text-xl">Rp 52.500</span>
                            </div>
                        </div>

                        <div class="p-3.5 rounded-lg bg-teal-50 border border-teal-100 text-xs text-teal-900">
                            <div class="font-bold mb-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Ketentuan Transaksi:
                            </div>
                            <p id="resNote" class="text-slate-600 leading-relaxed">
                                Cukup bawa uang tunai & sebutkan 10 digit nomor rekening BNI tujuan.
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- Jadwal & Lokasi Pelayanan Section -->
    <section id="jadwal" class="py-16 lg:py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-stretch">
                
                <!-- Card 1: Waktu Operasional Optimal -->
                <div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-50 text-teal-700 text-xs font-semibold border border-teal-200 mb-4">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Jam Pelayanan Siswa & Guru
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-2">Waktu Layanan Loket</h3>
                        <p class="text-sm text-slate-600 mb-6">Agar kegiatan belajar mengajar (KBM) tidak terganggu, berikut adalah waktu terbaik untuk bertransaksi:</p>

                        <div class="space-y-4">
                            <div class="flex items-start gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100">
                                <div class="w-9 h-9 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center font-bold text-sm shrink-0">1</div>
                                <div>
                                    <h4 class="font-bold text-slate-800 text-sm">Sebelum Bel Masuk KBM (07.00 - 07.25 WIB)</h4>
                                    <p class="text-xs text-slate-500 mt-0.5">Sangat pas untuk setor uang saku atau beli pulsa sebelum kelas dimulai.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4 p-4 rounded-xl bg-orange-50 border border-orange-100">
                                <div class="w-9 h-9 rounded-lg bg-orange-200 text-orange-800 flex items-center justify-center font-bold text-sm shrink-0">2</div>
                                <div>
                                    <h4 class="font-bold text-slate-800 text-sm">Jam Istirahat 1 & Istirahat 2 (Sholat & Makan)</h4>
                                    <p class="text-xs text-slate-500 mt-0.5">Waktu paling ramai untuk top-up e-wallet dan pembayaran tagihan.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100">
                                <div class="w-9 h-9 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center font-bold text-sm shrink-0">3</div>
                                <div>
                                    <h4 class="font-bold text-slate-800 text-sm">Sepulang Sekolah (15.00 - 15.30 WIB)</h4>
                                    <p class="text-xs text-slate-500 mt-0.5">Pelayanan transaksi sebelum penutupan kasir harian (End of Day).</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Lokasi & Fasilitas -->
                <div class="bg-white rounded-2xl p-8 border border-slate-200 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-orange-50 text-orange-700 text-xs font-semibold border border-orange-200 mb-4">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            Lokasi Loket Sekolah
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-2">Lokasi & Petugas Loket</h3>
                        <p class="text-sm text-slate-600 mb-6">Loket didesain menyerupai mini bank perbankan profesional untuk pembelajaran praktik industri.</p>

                        <div class="space-y-4 text-sm text-slate-600">
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-2">
                                <div class="font-bold text-slate-800 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                    Ruang Business Center / Mini Bank Lantai 1
                                </div>
                                <p class="text-xs text-slate-500">
                                    Terletak di area depan sekolah dekat lobi utama, mudah diakses oleh seluruh siswa dan guru.
                                </p>
                            </div>

                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-2">
                                <div class="font-bold text-slate-800 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                    Petugas Piket Siswa & Guru Pembimbing
                                </div>
                                <p class="text-xs text-slate-500">
                                    Dilayani oleh siswa kejuruan yang bertugas piket bergilir di bawah supervisi ketat Guru Pembimbing Administrasi Keuangan.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                        <span>Fasilitas: Thermal Struk, EDC BNI, Kasir POS</span>
                        <span class="text-emerald-600 font-semibold">● CCTV Aktif</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-16 lg:py-20 bg-white border-t border-slate-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="text-xs font-bold uppercase tracking-wider text-orange-600 bg-orange-100 px-3 py-1 rounded-full border border-orange-200">FAQ</span>
                <h2 class="text-3xl font-extrabold text-slate-900 mt-3">Pertanyaan yang Sering Diajukan</h2>
            </div>

            <div class="space-y-4">
                <details class="group border border-slate-200 rounded-xl p-5 bg-slate-50 [&_summary::-webkit-details-marker]:hidden" open>
                    <summary class="flex items-center justify-between cursor-pointer font-bold text-slate-800 text-sm sm:text-base">
                        <span>Apakah harus punya rekening BNI untuk melakukan transaksi di loket?</span>
                        <span class="ml-4 shrink-0 rounded-full bg-white p-1.5 text-slate-500 shadow-xs group-open:-rotate-180 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </span>
                    </summary>
                    <p class="mt-3 text-sm text-slate-600 leading-relaxed">
                        Tidak perlu! Anda dapat bertransaksi setor tunai, pembayaran tagihan PLN/BPJS, beli pulsa, dan top-up e-wallet langsung menggunakan uang tunai (cash) tanpa harus memiliki buku tabungan BNI.
                    </p>
                </details>

                <details class="group border border-slate-200 rounded-xl p-5 bg-slate-50 [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex items-center justify-between cursor-pointer font-bold text-slate-800 text-sm sm:text-base">
                        <span>Apakah transaksi langsung mendapatkan bukti tanda terima / struk?</span>
                        <span class="ml-4 shrink-0 rounded-full bg-white p-1.5 text-slate-500 shadow-xs group-open:-rotate-180 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </span>
                    </summary>
                    <p class="mt-3 text-sm text-slate-600 leading-relaxed">
                        Ya, setiap transaksi yang berhasil di loket kasir sekolah akan langsung dicetakkan struk kertas fisik yang memuat nomor referensi, tanggal, nominal, dan kode validasi transaksi resmi.
                    </p>
                </details>

                <details class="group border border-slate-200 rounded-xl p-5 bg-slate-50 [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex items-center justify-between cursor-pointer font-bold text-slate-800 text-sm sm:text-base">
                        <span>Bagaimana jika saldo tidak masuk atau ada kendala transaksi?</span>
                        <span class="ml-4 shrink-0 rounded-full bg-white p-1.5 text-slate-500 shadow-xs group-open:-rotate-180 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </span>
                    </summary>
                    <p class="mt-3 text-sm text-slate-600 leading-relaxed">
                        Semua transaksi tersimpan rapi di sistem database SIAP-46 dan termonitor oleh Guru Pembimbing. Anda cukup membawa struk bukti transaksi ke loket untuk dilakukan pengecekan status secara real-time.
                    </p>
                </details>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="mt-auto bg-slate-900 text-slate-400 text-sm border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-orange-600 flex items-center justify-center text-white font-bold text-sm">
                            46
                        </div>
                        <span class="text-xl font-bold text-white tracking-tight">SIAP-46</span>
                        <span class="text-xs bg-slate-800 text-teal-400 px-2 py-0.5 rounded border border-slate-700">Agen BNI Sekolah</span>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed max-w-md">
                        Sistem Informasi Administrasi & Transaksi Praktik Kasir Agen BNI 46 di Lingkungan Sekolah. Wahana pembelajaran Teaching Factory berstandar industri perbankan nasional.
                    </p>
                    <div class="text-xs text-slate-500">
                        &copy; {{ date('Y') }} SIAP-46. Hak Cipta Dilindungi Undang-Undang.
                    </div>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-slate-200 uppercase tracking-wider mb-4">Navigasi Halaman</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="#beranda" class="hover:text-white transition-colors">Beranda</a></li>
                        <li><a href="#layanan" class="hover:text-white transition-colors">Katalog Layanan</a></li>
                        <li><a href="#simulasi" class="hover:text-white transition-colors">Simulasi Biaya Admin</a></li>
                        <li><a href="#jadwal" class="hover:text-white transition-colors">Jadwal Operasional</a></li>
                        <li><a href="#faq" class="hover:text-white transition-colors">FAQ</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-slate-200 uppercase tracking-wider mb-4">Akses Petugas</h4>
                    <ul class="space-y-2 text-xs">
                        <li>
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-orange-400 hover:text-orange-300 font-medium transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                                Login Kasir & Admin
                            </a>
                        </li>
                        <li class="text-slate-500 pt-2 text-[11px]">
                            Gunakan akun terdaftar yang telah diverifikasi oleh Administrator Sekolah.
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </footer>

</div>

<!-- Interactive Simulation Vanilla JS Script -->
<script>
function formatRupiah(num) {
    return 'Rp ' + Number(num).toLocaleString('id-ID');
}

function updateSimulation() {
    const select = document.getElementById('serviceType');
    const selectedOption = select.options[select.selectedIndex];
    const fee = parseInt(selectedOption.getAttribute('data-fee') || '0', 10);
    const fixedPrice = selectedOption.getAttribute('data-price');
    const note = selectedOption.getAttribute('data-note') || '';
    
    const amountInput = document.getElementById('simAmount');
    let amount = parseInt(amountInput.value || '0', 10);

    if (fixedPrice) {
        amount = parseInt(fixedPrice, 10);
        amountInput.value = fixedPrice;
        amountInput.setAttribute('disabled', 'disabled');
    } else {
        amountInput.removeAttribute('disabled');
    }

    const total = amount + fee;

    document.getElementById('resAmount').textContent = formatRupiah(amount);
    document.getElementById('resFee').textContent = fee > 0 ? formatRupiah(fee) : 'Gratis';
    document.getElementById('resTotal').textContent = formatRupiah(total);
    document.getElementById('resNote').textContent = note;
}

// Initial calculation on page load
document.addEventListener('DOMContentLoaded', function() {
    updateSimulation();
});
</script>
@endsection
