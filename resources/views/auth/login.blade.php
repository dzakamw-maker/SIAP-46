@extends('layouts.app')

@section('title', 'Login - Mitra Agen BNI 46 Sekolah')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#fff5f0] via-white to-[#e6f1f2] px-4 py-8">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl border border-slate-200/80 p-8 sm:p-10 relative overflow-hidden">
        
        <!-- Top BNI Accent Bar -->
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-[#005e6a] via-[#005e6a] to-[#f15a23]"></div>

        <div class="text-center mb-8">
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">SIAP<span class="text-[#f15a23]">46</span></h2>
            <div class="inline-flex items-center gap-1.5 mt-2 px-3 py-0.5 rounded-full bg-[#005e6a]/10 text-[#005e6a] text-xs font-bold border border-[#005e6a]/20">
                <span class="w-1.5 h-1.5 rounded-full bg-[#f15a23]"></span>
                Mitra Resmi Agen BNI 46
            </div>
            <p class="text-slate-500 text-xs mt-3">Silakan masuk menggunakan akun Anda</p>
        </div>

        <form action="{{ route('login') }}" method="POST">
            @csrf
            
            @if($errors->any())
                <div class="mb-5 p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs font-medium">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="mb-4">
                <label for="username" class="block mb-2 text-xs font-bold uppercase tracking-wider text-slate-700">Username</label>
                <input type="text" id="username" name="username" value="{{ old('username') }}" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-xl focus:ring-[#005e6a] focus:border-[#005e6a] block w-full p-3 font-medium transition-colors" placeholder="Masukkan username" required autofocus>
            </div>
            <div class="mb-6">
                <label for="password" class="block mb-2 text-xs font-bold uppercase tracking-wider text-slate-700">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-xl focus:ring-[#005e6a] focus:border-[#005e6a] block w-full p-3 pr-10 font-medium transition-colors" placeholder="••••••••" required>
                    <button type="button" onclick="const p = document.getElementById('password'); const icon = this.querySelector('svg'); if(p.type === 'password'){ p.type = 'text'; icon.innerHTML = '<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21\'/>'; } else { p.type = 'password'; icon.innerHTML = '<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M15 12a3 3 0 11-6 0 3 3 0 016 0z\' /><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z\' />'; }" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>
            <button type="submit" class="w-full text-white bg-[#f15a23] hover:bg-[#d94814] focus:ring-4 focus:outline-none focus:ring-[#f15a23]/30 font-bold rounded-xl text-sm px-5 py-3.5 text-center shadow-md shadow-[#f15a23]/20 transition-all hover:scale-[1.01]">
                Masuk ke Sistem Kasir
            </button>
        </form>
        
        <div class="mt-6 pt-5 border-t border-slate-100 text-center">
            <a href="{{ route('home') }}" class="text-xs font-bold text-[#005e6a] hover:text-[#004750] inline-flex items-center gap-1.5 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Beranda
            </a>
        </div>
        
    </div>
</div>
@endsection
