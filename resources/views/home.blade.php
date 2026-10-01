@extends('layouts.app')

@section('title', 'SIAP46 | Platform Kasir & Mini Bank Mitra Agen BNI 46 Sekolah')

@section('content')
<div class="min-h-screen flex flex-col bg-white text-slate-800 font-sans selection:bg-[#005e6a] selection:text-white antialiased">

    <!-- Top Announcement Bar (Official BNI Agen46 Info) -->
    <div class="bg-[#005e6a]/5 border-b border-[#005e6a]/15 text-xs py-2 px-4 text-slate-600">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-2">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-[#005e6a] text-white">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#f15a23] mr-1.5 animate-pulse"></span>
                    Mitra Agen BNI 46
                </span>
                <span class="text-slate-800 font-medium">Unit Mini Bank & Loket Kasir Sekolah • Business Center Lantai 1</span>
            </div>
            <div class="flex items-center gap-4 text-[11px] text-slate-600">
                <span>Operasional: <strong class="text-slate-900">07.30 - 15.30 WIB</strong></span>
                <span class="hidden md:inline">•</span>
                <span class="hidden md:inline font-semibold text-[#005e6a]">Teaching Factory Kejuruan Akuntansi</span>
            </div>
        </div>
    </div>

    <!-- Navigation Header (Clean Minimalist with Strong BNI Brand Elements) -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Logo Brand -->
                <a href="{{ route('home') }}" class="flex items-center group">
                    <span class="text-2xl font-black tracking-tight text-slate-900">SIAP<span class="text-[#f15a23]">46</span></span>
                </a>

                <!-- Centered Nav Links -->
                <nav class="hidden lg:flex items-center gap-8 text-sm font-semibold text-slate-600">
                    <a href="#beranda" class="hover:text-[#005e6a] transition-colors">Beranda</a>
                    <a href="#dashboard" class="hover:text-[#005e6a] transition-colors">Demo POS</a>
                    <a href="#layanan" class="hover:text-[#005e6a] transition-colors">Daftar Layanan</a>
                    <a href="#fitur" class="hover:text-[#005e6a] transition-colors">Fitur Sistem</a>
                    <a href="#tarif" class="hover:text-[#005e6a] transition-colors">Tarif Biaya</a>
                    <a href="#simulasi" class="hover:text-[#005e6a] transition-colors">Simulasi</a>
                    <a href="#faq" class="hover:text-[#005e6a] transition-colors">FAQ</a>
                </nav>

                <!-- Right Action Button -->
                <div class="flex items-center gap-4">
                    @auth
                        @if(auth()->user()->role && (auth()->user()->role->name === 'Admin' || auth()->user()->role->name === 'Guru'))
                            <a href="{{ route('admin.dashboard') }}" class="px-6 py-2.5 rounded-full text-sm font-bold text-white bg-[#005e6a] hover:bg-[#004750] shadow-sm transition-all hover:scale-105">
                                Panel Admin
                            </a>
                        @else
                            <a href="{{ route('kasir.dashboard') }}" class="px-6 py-2.5 rounded-full text-sm font-bold text-white bg-[#005e6a] hover:bg-[#004750] shadow-sm transition-all hover:scale-105">
                                Panel Kasir
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-2.5 rounded-full text-sm font-bold text-white bg-[#005e6a] hover:bg-[#004750] shadow-xs transition-all hover:scale-105">
                            Login Petugas
                        </a>
                    @endauth

                    <!-- Mobile Menu Button -->
                    <button type="button" onclick="document.getElementById('mobileMenu').classList.toggle('hidden')" class="lg:hidden p-2 text-slate-600 hover:text-slate-900 rounded-lg focus:outline-none" aria-label="Menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div id="mobileMenu" class="hidden lg:hidden border-t border-slate-200 bg-white px-4 pt-3 pb-5 space-y-2">
            <a href="#beranda" onclick="document.getElementById('mobileMenu').classList.add('hidden')" class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50">Beranda</a>
            <a href="#dashboard" onclick="document.getElementById('mobileMenu').classList.add('hidden')" class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50">Demo POS</a>
            <a href="#layanan" onclick="document.getElementById('mobileMenu').classList.add('hidden')" class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50">Daftar Layanan</a>
            <a href="#fitur" onclick="document.getElementById('mobileMenu').classList.add('hidden')" class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50">Fitur Sistem</a>
            <a href="#tarif" onclick="document.getElementById('mobileMenu').classList.add('hidden')" class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50">Tarif Biaya</a>
            <a href="#simulasi" onclick="document.getElementById('mobileMenu').classList.add('hidden')" class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50">Simulasi</a>
            <a href="#faq" onclick="document.getElementById('mobileMenu').classList.add('hidden')" class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50">FAQ</a>
        </div>
    </header>

    <!-- SECTION 1: HERO SECTION WITH HIGH-FIDELITY BNI DASHBOARD PREVIEW -->
    <section id="beranda" class="relative pt-16 pb-20 lg:pt-24 lg:pb-32 bg-gradient-to-b from-[#005e6a]/5 via-white to-white text-center px-4 overflow-hidden">
        
        <div class="max-w-5xl mx-auto space-y-6">
            
            <!-- Pill Tag -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white border border-[#005e6a]/20 text-xs font-semibold text-[#005e6a] shadow-2xs">
                <span class="w-2 h-2 rounded-full bg-[#f15a23]"></span>
                <span>Mitra Resmi Agen46 PT Bank Negara Indonesia (Persero) Tbk</span>
            </div>

            <!-- Big Centered Headline with BNI Accent -->
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-900 tracking-tight leading-[1.12]">
                Pencatatan Transaksi & Kasir <br class="hidden sm:inline">
                <span class="text-[#f15a23]">Agen BNI 46</span> Sekolah yang Terstandar
            </h1>

            <!-- Subtitle -->
            <p class="text-slate-600 text-base sm:text-lg max-w-2xl mx-auto leading-relaxed font-normal">
                Kelola transaksi perbankan siswa, setor & tarik tunai BNI, transfer dana, pembayaran tagihan, top-up e-wallet, dan rekonsiliasi harian (EOD) secara akurat sesuai standar operasional perbankan BNI.
            </p>

            <!-- CTA Buttons with Official BNI Colors -->
            <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-full text-sm font-bold text-white bg-[#f15a23] hover:bg-[#d94814] shadow-md shadow-[#f15a23]/25 transition-all hover:scale-105 inline-flex items-center justify-center gap-2">
                    <span>Akses Kasir Agen46</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </a>
                <a href="#layanan" class="w-full sm:w-auto px-8 py-3.5 rounded-full text-sm font-bold text-[#005e6a] bg-white hover:bg-[#005e6a]/5 border-2 border-[#005e6a] transition-colors shadow-2xs">
                    Daftar Layanan & Tarif
                </a>
            </div>

        </div>

        <!-- High-Fidelity Desktop Dashboard Preview (BNI Agen46 Terminal Aesthetic) -->
        <div id="dashboard" class="max-w-6xl mx-auto mt-16 px-2 sm:px-4">
            <div class="rounded-2xl border border-slate-200/90 bg-white shadow-2xl shadow-slate-300/40 overflow-hidden text-left">
                
                <!-- Terminal Window Header -->
                <div class="bg-slate-100/90 border-b border-slate-200 px-4 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-slate-300"></span>
                        <span class="w-3 h-3 rounded-full bg-slate-300"></span>
                        <span class="w-3 h-3 rounded-full bg-slate-300"></span>
                        <div class="flex items-center gap-1.5 ml-2">
                            <span class="w-2 h-2 rounded-full bg-[#f15a23]"></span>
                            <span class="text-xs font-bold text-[#005e6a] font-mono">bni-agen46.pos/terminal-01</span>
                        </div>
                    </div>
                    <div class="text-[11px] font-semibold text-slate-700 flex items-center gap-2">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-[#005e6a] text-white">ID: 46-SMK-JKT</span>
                        <span class="text-slate-600">Petugas: Siswa Piket Kasir</span>
                    </div>
                </div>

                <!-- Dashboard Interior Body -->
                <div class="p-6 bg-slate-50/50 space-y-6">
                    
                    <!-- Top 4 Summary Metric Cards with BNI Branding Accents -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="p-4 rounded-xl bg-white border border-slate-200 shadow-2xs relative overflow-hidden">
                            <div class="absolute top-0 left-0 right-0 h-1 bg-[#005e6a]"></div>
                            <div class="text-xs text-slate-500 font-medium">Jam Kerja Operasional</div>
                            <div class="text-2xl font-black text-slate-900 mt-1">07.30 - 15.30</div>
                            <div class="text-[11px] text-[#005e6a] mt-1 font-bold">Senin - Jumat (WIB)</div>
                        </div>
                        <div class="p-4 rounded-xl bg-white border border-slate-200 shadow-2xs relative overflow-hidden">
                            <div class="absolute top-0 left-0 right-0 h-1 bg-[#005e6a]"></div>
                            <div class="text-xs text-slate-500 font-medium">Kas Masuk (Setoran BNI)</div>
                            <div class="text-2xl font-black text-slate-900 mt-1">Rp 2.850.000</div>
                            <div class="text-[11px] text-emerald-700 mt-1 font-semibold">Uang Tunai Laci Kasir</div>
                        </div>
                        <div class="p-4 rounded-xl bg-white border border-slate-200 shadow-2xs relative overflow-hidden">
                            <div class="absolute top-0 left-0 right-0 h-1 bg-[#f15a23]"></div>
                            <div class="text-xs text-slate-500 font-medium">Kas Keluar (Tarik Tunai)</div>
                            <div class="text-2xl font-black text-slate-900 mt-1">Rp 900.000</div>
                            <div class="text-[11px] text-[#f15a23] mt-1 font-bold">Debit EDC BNI</div>
                        </div>
                        <div class="p-4 rounded-xl bg-white border border-slate-200 shadow-2xs relative overflow-hidden">
                            <div class="absolute top-0 left-0 right-0 h-1 bg-[#005e6a]"></div>
                            <div class="text-xs text-slate-500 font-medium">Status Rekonsiliasi (EOD)</div>
                            <div class="text-2xl font-black text-slate-900 mt-1">Seimbang</div>
                            <div class="text-[11px] text-[#005e6a] mt-1 font-bold">Selisih: Rp 0 (Cocok)</div>
                        </div>
                    </div>

                    <!-- Split Panel: Transaction Chart Visual + Live Orders Table -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                        
                        <!-- Left Chart Visual (Teal Line Graph with Orange Data Points) -->
                        <div class="lg:col-span-7 bg-white p-5 rounded-xl border border-slate-200 shadow-2xs space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-bold text-slate-900">Grafik Kepadatan Jam Operasional</h4>
                                    <p class="text-xs text-slate-500">Tingkat kunjungan siswa tertinggi terjadi saat jam istirahat sekolah</p>
                                </div>
                                <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-[#005e6a]/10 text-[#005e6a] border border-[#005e6a]/20">Real-Time</span>
                            </div>

                            <!-- SVG Chart Graph Mockup in BNI Teal & Orange -->
                            <div class="pt-4">
                                <svg class="w-full h-44" viewBox="0 0 500 160" fill="none">
                                    <path d="M 0,130 C 50,130 90,70 140,70 C 190,70 190,95 235,95 C 280,95 295,35 340,35 C 385,35 420,75 500,75" stroke="#005e6a" stroke-width="3" fill="none" />
                                    <path d="M 0,130 C 50,130 90,70 140,70 C 190,70 190,95 235,95 C 280,95 295,35 340,35 C 385,35 420,75 500,75 L 500,160 L 0,160 Z" fill="url(#chartGradient)" opacity="0.12" />
                                    <defs>
                                        <linearGradient id="chartGradient" x1="0" y1="0" x2="0" y2="1">
                                            <stop offset="0%" stop-color="#005e6a" />
                                            <stop offset="100%" stop-color="#005e6a" stop-opacity="0" />
                                        </linearGradient>
                                    </defs>
                                    <!-- Key BNI Orange markers precisely on curve points -->
                                    <circle cx="140" cy="70" r="5" fill="#f15a23" stroke="#ffffff" stroke-width="2" />
                                    <circle cx="340" cy="35" r="6" fill="#f15a23" stroke="#ffffff" stroke-width="2" />
                                    <circle cx="500" cy="75" r="5" fill="#f15a23" stroke="#ffffff" stroke-width="2" />
                                </svg>
                                <div class="flex justify-between text-[11px] text-slate-500 pt-2 border-t border-slate-100 font-medium">
                                    <span>07.00 (Buka Loket)</span>
                                    <span class="font-bold text-[#005e6a]">09.45 (Istirahat 1)</span>
                                    <span class="font-bold text-[#f15a23]">12.00 (Puncak Istirahat 2)</span>
                                    <span>15.00 (Tutup Buku EOD)</span>
                                </div>
                            </div>
                        </div>

                        <!-- Right Working Hours / Shift Schedule Panel -->
                        <div class="lg:col-span-5 bg-white p-5 rounded-xl border border-slate-200 shadow-2xs space-y-3">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-bold text-slate-900">Jadwal Jam Kerja Loket</h4>
                                <span class="text-xs text-[#005e6a] font-bold">Senin - Jumat</span>
                            </div>

                            <div class="space-y-2 text-xs">
                                <div class="p-2.5 rounded-lg bg-[#005e6a]/5 border border-[#005e6a]/15 flex items-center justify-between">
                                    <div>
                                        <div class="font-bold text-slate-900">Sesi Pagi (Sebelum Masuk)</div>
                                        <div class="text-[11px] text-slate-500">Buka laci kasir & layanan awal siswa</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-[#005e6a]">07.30 - 09.45</div>
                                        <span class="text-[10px] text-emerald-700 font-semibold">Aktif</span>
                                    </div>
                                </div>

                                <div class="p-2.5 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-between">
                                    <div>
                                        <div class="font-bold text-slate-800">Sesi Istirahat 1 & 2 (Puncak)</div>
                                        <div class="text-[11px] text-slate-500">Waktu utama transaksi siswa & guru</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-slate-900">09.45 - 13.00</div>
                                        <span class="text-[10px] text-[#f15a23] font-semibold">Padat</span>
                                    </div>
                                </div>

                                <div class="p-2.5 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-between">
                                    <div>
                                        <div class="font-bold text-slate-800">Sesi Sore & Tutup Buku (EOD)</div>
                                        <div class="text-[11px] text-slate-500">Hitung kas fisik & verifikasi guru</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-slate-900">13.00 - 15.30</div>
                                        <span class="text-[10px] text-slate-600 font-semibold">Rekap</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </section>

    <!-- SECTION 2: OFFICIAL BNI BRAND & SERVICE LOGO STRIP -->
    <section class="py-12 border-y border-slate-200 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">
                Melayani Negeri, Kebanggaan Bangsa • Jaringan Layanan Resmi Agen BNI 46
            </p>
            <div class="flex flex-wrap items-center justify-center gap-6 sm:gap-10 text-sm font-bold">
                <span class="flex items-center gap-2 text-slate-700"><span class="w-2 h-2 rounded-full bg-[#005e6a]"></span> Bank BNI (Agen46)</span>
                <span class="flex items-center gap-2 text-slate-700"><span class="w-2 h-2 rounded-full bg-[#005e6a]"></span> PLN Listrik</span>
                <span class="flex items-center gap-2 text-slate-700"><span class="w-2 h-2 rounded-full bg-[#005e6a]"></span> BPJS Kesehatan</span>
                <span class="flex items-center gap-2 text-slate-700"><span class="w-2 h-2 rounded-full bg-[#005e6a]"></span> DANA & ShopeePay</span>
                <span class="flex items-center gap-2 text-slate-700"><span class="w-2 h-2 rounded-full bg-[#005e6a]"></span> GoPay & OVO</span>
                <span class="flex items-center gap-2 text-slate-700"><span class="w-2 h-2 rounded-full bg-[#005e6a]"></span> Bea Meterai 10rb</span>
                <span class="flex items-center gap-2 text-slate-700"><span class="w-2 h-2 rounded-full bg-[#005e6a]"></span> Pulsa All Operator</span>
            </div>
        </div>
    </section>

    <!-- SECTION 3: DARK TEAL SEARCH/ESTIMATION BANNER (BNI Agen46 Identity) -->
    <section class="py-16 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-3xl bg-[#004750] text-white p-8 sm:p-12 text-center space-y-6 shadow-xl relative overflow-hidden border border-[#005e6a]">
                
                <div class="space-y-2">
                    <span class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-[#f15a23] bg-white/10 px-3 py-1 rounded-full border border-white/10">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#f15a23]"></span>
                        Pencarian Cepat Tarif Loket Agen46
                    </span>
                    <h3 class="text-2xl sm:text-3xl font-black text-white">Transparansi Tarif & Simulasi Transaksi</h3>
                    <p class="text-slate-200 text-sm max-w-lg mx-auto">
                        Cek estimasi biaya administrasi perbankan BNI dan pembayaran tagihan sebelum bertransaksi di loket sekolah.
                    </p>
                </div>

                <div class="max-w-xl mx-auto flex flex-col sm:flex-row gap-2 justify-center">
                    <a href="#simulasi" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 rounded-full text-sm font-bold text-white bg-[#f15a23] hover:bg-[#d94814] shadow-md transition-colors">
                        Buka Kalkulator Simulasi Biaya &rarr;
                    </a>
                </div>

                <div class="pt-2 flex flex-wrap items-center justify-center gap-2 text-xs text-slate-300">
                    <span class="text-slate-400 font-medium">Layanan Populer:</span>
                    <span class="bg-[#005e6a] px-3 py-1 rounded-full border border-white/20 text-white font-semibold">Setor Tunai BNI (Rp 2.500)</span>
                    <span class="bg-[#005e6a]/70 px-3 py-1 rounded-full border border-white/10 text-slate-200">Top Up DANA (Rp 2.000)</span>
                    <span class="bg-[#005e6a]/70 px-3 py-1 rounded-full border border-white/10 text-slate-200">Token PLN (Rp 3.000)</span>
                    <span class="bg-[#005e6a]/70 px-3 py-1 rounded-full border border-white/10 text-slate-200">Materai 10rb (Rp 10.000)</span>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 4: ALTERNATING FEATURE ROWS (Zig-Zag Layout with BNI Context) -->
    <section id="fitur" class="py-20 bg-slate-50/60 border-t border-slate-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-20">
            
            <div class="text-center max-w-2xl mx-auto space-y-3">
                <span class="text-xs font-bold uppercase tracking-wider text-[#005e6a] bg-[#005e6a]/10 px-3 py-1 rounded-full border border-[#005e6a]/20">Standar Industri Perbankan</span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900">Operasional Mini Bank Sekolah yang Handal</h2>
                <p class="text-slate-600 text-sm sm:text-base">Menerapkan prinsip kepercayaan & profesionalisme (#005e6a) serta semangat energi keterbukaan (#f15a23) dalam kurikulum Teaching Factory.</p>
            </div>

            <!-- Row 1: Text Left, Card Right -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                <div class="lg:col-span-6 space-y-4 text-left">
                    <span class="text-xs font-bold uppercase text-[#005e6a]">01 // Layanan Terpadu Agen46</span>
                    <h3 class="text-2xl sm:text-3xl font-bold text-slate-900">Multi-Channel POS Kasir Terstandar</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Mendukung input kilat seluruh spektrum layanan Agen46: setor tunai ke rekening BNI, transfer bank, tarik tunai kartu debit, pengisian e-wallet, dan pembelian token listrik dalam satu antarmuka kasir cepat.
                    </p>
                    <div class="pt-2 text-xs font-bold text-[#005e6a] flex items-center gap-2">
                        <span>Pencatatan instan dengan validasi nomor rekening</span> &rarr;
                    </div>
                </div>
                <div class="lg:col-span-6 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm text-left space-y-3">
                    <div class="flex items-center justify-between text-xs font-bold text-slate-500 uppercase">
                        <span>Simulasi Input Kasir</span>
                        <span class="text-[#005e6a]">Mitra Agen46</span>
                    </div>
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2 text-xs">
                        <div class="flex justify-between font-bold text-slate-900">
                            <span>Layanan: Setor Tunai Rekening BNI</span>
                            <span>Rp 250.000</span>
                        </div>
                        <div class="flex justify-between text-slate-500">
                            <span>Biaya Admin Resmi Loket:</span>
                            <span>Rp 2.500</span>
                        </div>
                        <div class="pt-2 border-t border-slate-200 flex justify-between font-black text-slate-900 text-sm">
                            <span>Total Diterima Kasir:</span>
                            <span class="text-[#005e6a]">Rp 252.500</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row 2: Card Left, Text Right -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                <div class="lg:col-span-6 lg:order-1 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm text-left space-y-3">
                    <div class="flex items-center justify-between text-xs font-bold text-slate-500 uppercase">
                        <span>Struk Thermal Standar BNI</span>
                        <span class="text-[#f15a23] font-bold">58mm / 80mm</span>
                    </div>
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-1 font-mono text-[11px] text-slate-700">
                        <div class="text-center font-bold text-slate-900 border-b border-dashed border-slate-300 pb-2 mb-2">
                            *** MITRA RESMI AGEN46 BNI ***<br>
                            UNIT MINI BANK SEKOLAH<br>
                            <span class="text-[10px] text-slate-500">ID AGEN: 46-SMK-JKT-01</span>
                        </div>
                        <div class="flex justify-between"><span>No. Reff:</span><span>BNI-2026-88129</span></div>
                        <div class="flex justify-between"><span>Tanggal:</span><span>01/10/2026 10:14</span></div>
                        <div class="flex justify-between"><span>Layanan:</span><span>Setor Tunai BNI</span></div>
                        <div class="flex justify-between"><span>Petugas:</span><span>Kasir Siswa (Shift 1)</span></div>
                        <div class="flex justify-between font-bold text-[#005e6a] pt-2 border-t border-dashed border-slate-300 mt-2">
                            <span>STATUS:</span><span>BERHASIL / LUNAS</span>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-6 lg:order-2 space-y-4 text-left">
                    <span class="text-xs font-bold uppercase text-[#005e6a]">02 // Bukti Sah Industri</span>
                    <h3 class="text-2xl sm:text-3xl font-bold text-slate-900">Pencetakan Bukti Struk Fisik Agen46</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Setiap transaksi yang berhasil otomatis menghasilkan struk resmi berformat perbankan dengan identitas Agen BNI 46 sekolah, nomor referensi unik, dan rincian transaksi sebagai bukti sah bagi siswa maupun guru.
                    </p>
                    <div class="pt-2 text-xs font-bold text-[#005e6a] flex items-center gap-2">
                        <span>Memenuhi standar audit transaksi keuangan</span> &rarr;
                    </div>
                </div>
            </div>

            <!-- Row 3: Text Left, Card Right -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                <div class="lg:col-span-6 space-y-4 text-left">
                    <span class="text-xs font-bold uppercase text-[#005e6a]">03 // Rekonsiliasi & Audit</span>
                    <h3 class="text-2xl sm:text-3xl font-bold text-slate-900">Tutup Buku Harian (End of Day / EOD)</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Memastikan perputaran uang kas laci fisik selalu cocok 100% dengan mutasi sistem. Di akhir shift, siswa merekap fisik uang kas dan Guru Pembimbing melakukan verifikasi serta persetujuan resmi.
                    </p>
                    <div class="pt-2 text-xs font-bold text-[#005e6a] flex items-center gap-2">
                        <span>Pencegahan selisih kas dan pembiasaan etika perbankan</span> &rarr;
                    </div>
                </div>
                <div class="lg:col-span-6 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm text-left space-y-3">
                    <div class="flex items-center justify-between text-xs font-bold text-slate-500 uppercase">
                        <span>Verifikasi EOD Pembimbing</span>
                        <span class="text-emerald-700">Seimbang</span>
                    </div>
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2 text-xs">
                        <div class="flex justify-between items-center pb-2 border-b border-slate-200">
                            <span class="text-slate-500">Saldo Pembukuan Sistem:</span>
                            <span class="font-bold text-slate-900">Rp 1.650.000</span>
                        </div>
                        <div class="flex justify-between items-center pb-2 border-b border-slate-200">
                            <span class="text-slate-500">Uang Fisik Laci Dihitung:</span>
                            <span class="font-bold text-slate-900">Rp 1.650.000</span>
                        </div>
                        <div class="flex justify-between items-center text-slate-900 font-bold pt-1">
                            <span>Status Selisih Kas:</span>
                            <span class="text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200 text-[11px]">✓ Rp 0 (Disetujui Guru)</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- SECTION 5: DAFTAR LAYANAN KATALOG (6-Card Grid with BNI Core Service Highlight) -->
    <section id="layanan" class="py-20 bg-white border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
                <span class="text-xs font-bold uppercase tracking-wider text-[#005e6a] bg-[#005e6a]/10 px-3 py-1 rounded-full border border-[#005e6a]/20">Katalog Lengkap</span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900">Daftar Layanan Transaksi Sekolah</h2>
                <p class="text-slate-600 text-sm">Layanan perbankan Agen BNI 46 dan pembayaran tagihan resmi siap dilayani di loket sekolah.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 text-left">
                
                <div class="p-7 rounded-3xl bg-slate-50 border border-slate-200 hover:border-slate-300 transition-colors space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center font-bold text-slate-900">1</div>
                    <h4 class="font-bold text-slate-900 text-lg">Setor & Tarik Tunai BNI</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">Setor uang tabungan siswa ke rekening BNI atau tarik tunai via kartu debit resmi tanpa perlu keluar gerbang sekolah.</p>
                </div>

                <div class="p-7 rounded-3xl bg-slate-50 border border-slate-200 hover:border-slate-300 transition-colors space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center font-bold text-slate-900">2</div>
                    <h4 class="font-bold text-slate-900 text-lg">Transfer Antar Bank</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">Kirim uang langsung ke bank lain (BCA, BRI, Mandiri, BSI) secara real-time melalui jaringan interbank BNI.</p>
                </div>

                <div class="p-7 rounded-3xl bg-slate-50 border border-slate-200 hover:border-slate-300 transition-colors space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center font-bold text-slate-900">3</div>
                    <h4 class="font-bold text-slate-900 text-lg">Top-Up Dompet Digital</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">Pengisian saldo DANA, ShopeePay, GoPay, dan OVO cepat cukup sebutkan nomor HP terdaftar.</p>
                </div>

                <div class="p-7 rounded-3xl bg-slate-50 border border-slate-200 hover:border-slate-300 transition-colors space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center font-bold text-slate-900">4</div>
                    <h4 class="font-bold text-slate-900 text-lg">Token Listrik & PLN</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">Beli token listrik PLN prabayar (20rb s/d 1jt) atau pelunasan tagihan listrik pascabayar keluarga.</p>
                </div>

                <div class="p-7 rounded-3xl bg-slate-50 border border-slate-200 hover:border-slate-300 transition-colors space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center font-bold text-slate-900">5</div>
                    <h4 class="font-bold text-slate-900 text-lg">BPJS & Tagihan Utilitas</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">Pembayaran iuran BPJS Kesehatan dan tagihan air PDAM tepat waktu untuk kemudahan warga sekolah.</p>
                </div>

                <div class="p-7 rounded-3xl bg-slate-50 border border-slate-200 hover:border-slate-300 transition-colors space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center font-bold text-slate-900">6</div>
                    <h4 class="font-bold text-slate-900 text-lg">Bea Meterai Rp 10.000</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">Penyediaan materai fisik resmi untuk kelengkapan berkas PKL / magang siswa dan dokumen administrasi.</p>
                </div>

            </div>

        </div>
    </section>

    <!-- SECTION 6: PRICING / TARIF 3-CARD (Featured BNI Teal Center Card) -->
    <section id="tarif" class="py-20 bg-slate-50 border-t border-slate-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4 mb-16">
            <span class="text-xs font-bold uppercase tracking-wider text-[#005e6a] bg-white px-3 py-1 rounded-full border border-[#005e6a]/20">Biaya Administrasi Resmi</span>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900">Tarif Layanan Bersahabat & Transparan</h2>
            <p class="text-slate-600 text-sm max-w-xl mx-auto">Tarif resmi standar Agen BNI 46 tanpa biaya tersembunyi untuk seluruh siswa dan guru.</p>
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch text-left">
                
                <!-- Card 1: Featured BNI Teal Card -->
                <div class="rounded-3xl p-8 bg-[#005e6a] text-white shadow-xl flex flex-col justify-between space-y-6 border border-[#004750] relative overflow-hidden">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase text-slate-200">Kategori 01</span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-[#f15a23] text-white">RESMI AGEN46</span>
                        </div>
                        <h4 class="text-xl font-bold text-white">Layanan Perbankan BNI</h4>
                        <div class="flex items-baseline gap-1">
                            <span class="text-3xl font-black text-white">Rp 2.500</span>
                            <span class="text-xs text-slate-200">/ transaksi</span>
                        </div>
                        <p class="text-xs text-slate-100 leading-relaxed">Setor tunai ke rekening BNI atau tarik tunai via kartu debit resmi.</p>
                        
                        <div class="pt-4 border-t border-white/20 space-y-2.5 text-xs text-white">
                            <div class="flex items-center gap-2"><span class="text-[#f15a23] font-black">✓</span> Setor Tunai Rekening BNI</div>
                            <div class="flex items-center gap-2"><span class="text-[#f15a23] font-black">✓</span> Tarik Tunai Kartu Debit BNI</div>
                            <div class="flex items-center gap-2"><span class="text-[#f15a23] font-black">✓</span> Cetak Struk Bukti Fisik</div>
                        </div>
                    </div>
                    <a href="#simulasi" class="w-full py-3 rounded-full text-center text-xs font-bold text-white bg-[#f15a23] hover:bg-[#d94814] transition-colors shadow-sm">
                        Simulasi Transaksi
                    </a>
                </div>

                <!-- Card 2 -->
                <div class="rounded-3xl p-8 bg-white border border-slate-200 shadow-sm flex flex-col justify-between space-y-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase text-slate-500">Kategori 02</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-800">POPULER</span>
                        </div>
                        <h4 class="text-xl font-bold text-slate-900">Dompet Digital & E-Wallet</h4>
                        <div class="flex items-baseline gap-1">
                            <span class="text-3xl font-black text-slate-900">Rp 2.000</span>
                            <span class="text-xs text-slate-500">/ transaksi</span>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed">Top-up saldo DANA, ShopeePay, GoPay, dan OVO masuk seketika.</p>
                        
                        <div class="pt-4 border-t border-slate-100 space-y-2.5 text-xs text-slate-600">
                            <div class="flex items-center gap-2"><span class="text-[#005e6a] font-black">✓</span> DANA, ShopeePay, GoPay, OVO</div>
                            <div class="flex items-center gap-2"><span class="text-[#005e6a] font-black">✓</span> Pulsa Semua Operator Seluler</div>
                            <div class="flex items-center gap-2"><span class="text-[#005e6a] font-black">✓</span> Saldo Masuk Hitungan Detik</div>
                        </div>
                    </div>
                    <a href="#simulasi" class="w-full py-3 rounded-full text-center text-xs font-bold text-slate-900 bg-slate-100 hover:bg-slate-200 transition-colors">
                        Simulasi Transaksi
                    </a>
                </div>

                <!-- Card 3 -->
                <div class="rounded-3xl p-8 bg-white border border-slate-200 shadow-sm flex flex-col justify-between space-y-6">
                    <div class="space-y-4">
                        <div class="text-xs font-bold uppercase text-slate-500">Kategori 03</div>
                        <h4 class="text-xl font-bold text-slate-900">Tagihan & Materai</h4>
                        <div class="flex items-baseline gap-1">
                            <span class="text-3xl font-black text-slate-900">Rp 2.500</span>
                            <span class="text-xs text-slate-500">+ Materai 10rb</span>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed">Pembayaran tagihan listrik PLN, BPJS, PDAM, dan pembelian materai.</p>
                        
                        <div class="pt-4 border-t border-slate-100 space-y-2.5 text-xs text-slate-600">
                            <div class="flex items-center gap-2"><span class="text-[#005e6a] font-black">✓</span> Token PLN & Pasca Bayar</div>
                            <div class="flex items-center gap-2"><span class="text-[#005e6a] font-black">✓</span> Iuran BPJS Kesehatan</div>
                            <div class="flex items-center gap-2"><span class="text-[#005e6a] font-black">✓</span> Materai Resmi Rp 10.000</div>
                        </div>
                    </div>
                    <a href="#simulasi" class="w-full py-3 rounded-full text-center text-xs font-bold text-slate-900 bg-slate-100 hover:bg-slate-200 transition-colors">
                        Simulasi Transaksi
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 7: INTERACTIVE FEE SIMULATOR -->
    <section id="simulasi" class="py-20 bg-white border-t border-slate-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center space-y-2 mb-12">
                <span class="text-xs font-bold uppercase tracking-wider text-[#005e6a] bg-[#005e6a]/10 px-3 py-1 rounded-full border border-[#005e6a]/20">Kalkulator Interaktif</span>
                <h2 class="text-3xl font-black text-slate-900">Simulasi Biaya & Total Bayar</h2>
                <p class="text-slate-600 text-sm">Hitung estimasi total yang perlu disiapkan sebelum datang ke loket Agen BNI 46 sekolah.</p>
            </div>

            <div class="bg-slate-50 rounded-3xl p-8 border border-slate-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center text-left">
                    
                    <div class="space-y-5">
                        <div>
                            <label for="serviceType" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Pilih Layanan</label>
                            <select id="serviceType" onchange="updateSimulation()" class="w-full bg-white border border-slate-300 text-slate-800 text-sm rounded-xl focus:ring-[#005e6a] focus:border-[#005e6a] block p-3.5 font-medium">
                                <option value="setor_bni" data-fee="2500" data-note="Layanan resmi Agen46. Bawa uang tunai & sebutkan 10 digit nomor rekening BNI tujuan.">Setor Tunai Rekening BNI (Agen46)</option>
                                <option value="setor_antar_bank" data-fee="6500" data-note="Transfer ke bank lain (BCA, BRI, Mandiri) melalui jaringan BNI. Saldo masuk real-time.">Setor Tunai Antar Bank</option>
                                <option value="tarik_bni" data-fee="3000" data-note="Tarik tunai lewat mesin EDC menggunakan kartu debit BNI Anda.">Tarik Tunai Kartu BNI</option>
                                <option value="topup_ewallet" data-fee="2000" data-note="Tersedia untuk DANA, ShopeePay, GoPay, dan OVO. Cukup berikan nomor HP akun.">Top Up E-Wallet (DANA / ShopeePay / GoPay)</option>
                                <option value="pln_token" data-fee="3000" data-note="Sebutkan ID Pelanggan PLN. 20 digit kode token langsung terbit di struk.">Token Listrik PLN (Prepaid)</option>
                                <option value="bpjs" data-fee="2500" data-note="Pembayaran iuran BPJS Kesehatan per bulan. Bawa Nomor Kartu BPJS.">Iuran BPJS Kesehatan</option>
                                <option value="materai" data-fee="0" data-price="10000" data-note="Harga materai resmi Rp 10.000 / keping tanpa biaya tambahan.">Materai Resmi Rp 10.000</option>
                                <option value="pulsa" data-fee="1500" data-note="Tersedia semua operator (Telkomsel, Indosat, XL, Tri, Smartfren).">Pulsa Seluler Semua Operator</option>
                            </select>
                        </div>

                        <div>
                            <label for="simAmount" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Nominal Uang Transaksi (Rp)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 font-semibold text-sm">Rp</span>
                                <input type="number" id="simAmount" value="50000" min="5000" step="5000" oninput="updateSimulation()" class="w-full bg-white border border-slate-300 text-slate-800 text-sm rounded-xl focus:ring-[#005e6a] focus:border-[#005e6a] block pl-10 p-3.5 font-bold" placeholder="50.000">
                            </div>
                        </div>
                    </div>

                    <!-- Live Calculation Card -->
                    <div class="bg-white p-6 rounded-2xl border-2 border-[#005e6a]/20 shadow-2xs space-y-4">
                        <div class="flex items-center justify-between text-[11px] font-bold uppercase tracking-wider text-[#005e6a]">
                            <span>Kalkulasi Pembayaran</span>
                            <span class="text-[#f15a23]">Loket Agen46</span>
                        </div>
                        
                        <div class="space-y-2.5 text-sm">
                            <div class="flex justify-between items-center text-slate-600">
                                <span>Nominal Layanan:</span>
                                <span id="resAmount" class="font-semibold text-slate-900">Rp 50.000</span>
                            </div>
                            <div class="flex justify-between items-center text-slate-600">
                                <span>Biaya Admin Loket:</span>
                                <span id="resFee" class="font-bold text-[#005e6a]">Rp 2.500</span>
                            </div>
                            <div class="pt-3 border-t border-slate-200 flex justify-between items-center text-base">
                                <span class="font-bold text-slate-900">Total Dibayarkan:</span>
                                <span id="resTotal" class="font-black text-[#005e6a] text-xl">Rp 52.500</span>
                            </div>
                        </div>

                        <div class="p-3.5 rounded-xl bg-[#005e6a]/5 border border-[#005e6a]/15 text-xs text-slate-700">
                            <span class="font-bold text-[#005e6a] block mb-0.5">Petunjuk Transaksi:</span>
                            <p id="resNote" class="text-slate-600 leading-relaxed">
                                Layanan resmi Agen46. Bawa uang tunai pas & sebutkan nomor rekening tujuan ke petugas loket.
                            </p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>

    <!-- SECTION 8: FAQ ACCORDION -->
    <section id="faq" class="py-20 bg-slate-50 border-t border-slate-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12 text-center">
            
            <div class="space-y-2">
                <span class="text-xs font-bold uppercase tracking-wider text-[#005e6a] bg-white px-3 py-1 rounded-full border border-[#005e6a]/20">FAQ</span>
                <h2 class="text-3xl font-black text-slate-900">Frequently Asked Questions</h2>
                <p class="text-slate-600 text-sm">Pertanyaan umum seputar loket kemitraan Agen BNI 46 dan sistem SIAP46.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-left">
                <details class="group rounded-2xl bg-white border border-slate-200 p-5 [&_summary::-webkit-details-marker]:hidden" open>
                    <summary class="flex items-center justify-between cursor-pointer font-bold text-slate-900 text-sm">
                        <span>Apa itu loket Mitra Agen BNI 46 di sekolah?</span>
                        <span class="ml-4 shrink-0 rounded-full bg-slate-100 p-1.5 text-slate-500 group-open:-rotate-180 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </span>
                    </summary>
                    <p class="mt-3 text-xs text-slate-600 leading-relaxed">
                        Loket Agen46 adalah kemitraan resmi dengan PT Bank Negara Indonesia (Persero) Tbk untuk menghadirkan layanan perbankan langsung di sekolah. Dikelola oleh siswa kejuruan sebagai sarana praktik kerja nyata (Teaching Factory) dengan pengawasan guru.
                    </p>
                </details>

                <details class="group rounded-2xl bg-white border border-slate-200 p-5 [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex items-center justify-between cursor-pointer font-bold text-slate-900 text-sm">
                        <span>Apakah siswa non-nasabah BNI bisa bertransaksi?</span>
                        <span class="ml-4 shrink-0 rounded-full bg-slate-100 p-1.5 text-slate-500 group-open:-rotate-180 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </span>
                    </summary>
                    <p class="mt-3 text-xs text-slate-600 leading-relaxed">
                        Tentu saja bisa! Seluruh transaksi seperti pembayaran token PLN, iuran BPJS, top-up e-wallet (DANA, ShopeePay, GoPay), pulsa, dan pembelian meterai dapat dilayani langsung dengan pembayaran tunai (cash).
                    </p>
                </details>

                <details class="group rounded-2xl bg-white border border-slate-200 p-5 [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex items-center justify-between cursor-pointer font-bold text-slate-900 text-sm">
                        <span>Apakah setiap transaksi langsung dapat struk fisik?</span>
                        <span class="ml-4 shrink-0 rounded-full bg-slate-100 p-1.5 text-slate-500 group-open:-rotate-180 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </span>
                    </summary>
                    <p class="mt-3 text-xs text-slate-600 leading-relaxed">
                        Ya. Loket kasir dilengkapi printer thermal standar perbankan yang otomatis menerbitkan struk fisik berisi identitas Agen46, nomor referensi sah, waktu transaksi, dan nominal pembayaran.
                    </p>
                </details>

                <details class="group rounded-2xl bg-white border border-slate-200 p-5 [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex items-center justify-between cursor-pointer font-bold text-slate-900 text-sm">
                        <span>Kapan waktu operasional loket di sekolah?</span>
                        <span class="ml-4 shrink-0 rounded-full bg-slate-100 p-1.5 text-slate-500 group-open:-rotate-180 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </span>
                    </summary>
                    <p class="mt-3 text-xs text-slate-600 leading-relaxed">
                        Loket beroperasi Senin - Jumat pukul 07.30 - 15.30 WIB di Business Center Lantai 1. Siswa dapat bertransaksi sebelum pelajaran dimulai, saat istirahat 1 & 2, atau setelah jam pulang sekolah.
                    </p>
                </details>
            </div>

        </div>
    </section>

    <!-- SECTION 9: FOOTER (Large Dark Minimalist Footer with BNI Brand Heritage) -->
    <footer class="border-t-2 border-[#005e6a] bg-[#041a1e] text-slate-400 text-xs py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-10 text-left">
                
                <div class="md:col-span-2 space-y-4">
                    <div>
                        <span class="text-xl font-black text-white tracking-tight">SIAP<span class="text-[#f15a23]">46</span></span>
                    </div>
                    <p class="text-xs text-slate-300 leading-relaxed max-w-sm">
                        Sistem Informasi Administrasi & Transaksi Praktik Kasir Mitra Agen BNI 46 Sekolah. Memadukan kekokohan dan profesionalisme (<span class="text-[#008d9e] font-semibold">Teal #005e6a</span>) dengan semangat energi dan keterbukaan (<span class="text-[#f15a23] font-semibold">Oranye #f15a23</span>).
                    </p>
                    <div class="text-[11px] text-slate-500 pt-2">
                        &copy; {{ date('Y') }} SIAP46 • Kemitraan Agen46 PT Bank Negara Indonesia (Persero) Tbk.
                    </div>
                </div>

                <div>
                    <h5 class="text-xs font-bold text-white uppercase tracking-wider mb-4">Navigasi Utama</h5>
                    <ul class="space-y-2.5 text-slate-400">
                        <li><a href="#beranda" class="hover:text-white transition-colors">Beranda</a></li>
                        <li><a href="#dashboard" class="hover:text-white transition-colors">Demo Antarmuka POS</a></li>
                        <li><a href="#layanan" class="hover:text-white transition-colors">Daftar Layanan</a></li>
                        <li><a href="#fitur" class="hover:text-white transition-colors">Fitur Sistem</a></li>
                    </ul>
                </div>

                <div>
                    <h5 class="text-xs font-bold text-white uppercase tracking-wider mb-4">Operasional Loket</h5>
                    <ul class="space-y-2.5 text-slate-400">
                        <li><a href="#tarif" class="hover:text-white transition-colors">Tarif Biaya Layanan</a></li>
                        <li><a href="#simulasi" class="hover:text-white transition-colors">Simulasi Biaya Admin</a></li>
                        <li><a href="#faq" class="hover:text-white transition-colors">FAQ</a></li>
                        <li><span class="text-slate-500">Business Center Lt. 1</span></li>
                    </ul>
                </div>

                <div>
                    <h5 class="text-xs font-bold text-white uppercase tracking-wider mb-4">Akses Petugas</h5>
                    <ul class="space-y-3">
                        <li>
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-xs font-bold text-white bg-[#005e6a] hover:bg-[#004750] px-4 py-2.5 rounded-xl border border-white/10 transition-colors shadow-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                                <span>Login Petugas / Kasir</span>
                            </a>
                        </li>
                        <li class="text-[11px] text-slate-400 leading-relaxed">
                            Khusus siswa kasir yang bertugas dan guru pembimbing Teaching Factory terdaftar.
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </footer>

</div>

<!-- Interactive Calculator Script -->
<script>
function formatRupiah(num) {
    return 'Rp ' + Number(num).toLocaleString('id-ID');
}

function updateSimulation() {
    const select = document.getElementById('serviceType');
    if (!select) return;
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

document.addEventListener('DOMContentLoaded', function() {
    updateSimulation();
});
</script>
@endsection
