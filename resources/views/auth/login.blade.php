@extends('layouts.app')

@section('title', 'Login - Agen BNI Sekolah')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-orange-100 to-teal-100">
    <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8">
        
        <div class="text-center mb-8">
            <div class="inline-block p-4 rounded-full bg-orange-100 mb-4">
                <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Agen BNI Sekolah</h2>
            <p class="text-gray-500 text-sm mt-2">Silakan masuk menggunakan akun Anda</p>
        </div>

        <form action="/" method="POST">
            @csrf
            
            @if($errors->any())
                <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-700 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="mb-5">
                <label for="username" class="block mb-2 text-sm font-medium text-gray-700">Username</label>
                <input type="text" id="username" name="username" value="{{ old('username') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5" placeholder="Masukkan username" required>
            </div>
            <div class="mb-6">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5" placeholder="••••••••" required>
            </div>
            <button type="submit" class="w-full text-white bg-orange-600 hover:bg-orange-700 focus:ring-4 focus:outline-none focus:ring-orange-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors">
                Masuk
            </button>
        </form>
        
    </div>
</div>
@endsection
