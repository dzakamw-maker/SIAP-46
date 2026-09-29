@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-50">
    
    <!-- Sidebar -->
    <aside class="w-64 bg-orange-600 text-white flex flex-col shadow-lg z-10">
        <div class="h-16 flex items-center justify-center border-b border-orange-500">
            <h1 class="text-xl font-bold flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                Kasir Agen BNI
            </h1>
        </div>
        <div class="p-4 bg-orange-700">
            <p class="text-xs text-orange-200">Kasir Aktif</p>
            <p class="font-medium">{{ auth()->user()->full_name }}</p>
        </div>
        <nav class="flex-1 px-4 py-4 space-y-2">
            <a href="{{ route('kasir.dashboard') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('kasir.dashboard') ? 'bg-white text-orange-600' : 'text-white hover:bg-orange-500' }} rounded-lg font-medium shadow-sm transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                Input Transaksi
            </a>
            <a href="{{ route('kasir.stamps') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('kasir.stamps') ? 'bg-white text-orange-600' : 'text-white hover:bg-orange-500' }} rounded-lg font-medium shadow-sm transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                Penjualan Materai
            </a>
            <a href="{{ route('kasir.eod') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('kasir.eod') ? 'bg-white text-orange-600' : 'text-white hover:bg-orange-500' }} rounded-lg font-medium shadow-sm transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                Rekap Harian (EOD)
            </a>
        </nav>
        <div class="p-4">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center justify-center px-4 py-2 bg-orange-700 hover:bg-orange-800 rounded transition-colors text-sm w-full">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Keluar / Tutup Shift
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col overflow-hidden relative">
        <header class="h-16 bg-white border-b border-gray-200 flex items-center px-6 justify-between shadow-sm z-0">
            <h2 class="text-xl font-semibold text-gray-800">@yield('page_title', 'Workspace Kasir')</h2>
            <div class="flex items-center space-x-4">
                <div class="text-right">
                    <p class="text-xs text-gray-500">Tanggal</p>
                    <p class="text-sm font-medium">{{ date('d F Y') }}</p>
                </div>
            </div>
        </header>
        
        <div class="flex-1 overflow-auto p-6">
            @yield('cashier_content')
        </div>
    </main>
</div>
@endsection
