@extends('layouts.admin')

@section('page_title', 'Edit User')

@section('admin_content')
<div class="mb-4 flex">
    <a href="{{ route('admin.users') }}" class="text-gray-500 hover:text-gray-700 flex items-center text-sm font-medium">
        &larr; Kembali ke Daftar User
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden max-w-3xl">
    <div class="p-6">
        <div class="mb-6 pb-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800">Edit Data User</h3>
            <p class="text-sm text-gray-500 mt-1">Perbarui data user: <span class="font-medium text-gray-700">{{ $user->full_name }}</span></p>
        </div>

        @if($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
            <div class="flex items-center mb-2 font-medium">
                <svg class="w-5 h-5 mr-2 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>Mohon periksa kembali form pengisian:</span>
            </div>
            <ul class="list-disc list-inside space-y-1 text-xs text-red-600 ml-7">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Nama Lengkap -->
                <div class="col-span-1 md:col-span-2">
                    <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="full_name" id="full_name" value="{{ old('full_name', $user->full_name) }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2 px-3 border @error('full_name') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror" placeholder="Masukkan nama lengkap" required>
                    @error('full_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2 px-3 border @error('username') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror" placeholder="Masukkan username" required>
                    @error('username')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role -->
                <div>
                    <label for="role_id" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select name="role_id" id="role_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2 px-3 border @error('role_id') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror" required>
                        <option value="">-- Pilih Role --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ (string) old('role_id', $user->role_id) === (string) $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- NIS (Opsional) -->
                <div>
                    <label for="student_number" class="block text-sm font-medium text-gray-700 mb-1">NIS <span class="text-gray-400 font-normal">(Opsional)</span></label>
                    <input type="text" name="student_number" id="student_number" value="{{ old('student_number', $user->student_number) }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2 px-3 border @error('student_number') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror" placeholder="Nomor Induk Siswa">
                    @error('student_number')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kelas (Opsional) -->
                <div>
                    <label for="class_group" class="block text-sm font-medium text-gray-700 mb-1">Kelas <span class="text-gray-400 font-normal">(Opsional)</span></label>
                    <input type="text" name="class_group" id="class_group" value="{{ old('class_group', $user->class_group) }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2 px-3 border @error('class_group') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror" placeholder="Contoh: XII RPL 1">
                    @error('class_group')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-gray-400 font-normal text-xs">(Kosongkan jika tidak diubah)</span></label>
                    <div class="relative">
                        <input type="password" name="password" id="password" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2 px-3 border pr-10 @error('password') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror" placeholder="Masukkan password baru jika ingin mengubah">
                        <button type="button" onclick="const p = document.getElementById('password'); const icon = this.querySelector('svg'); if(p.type === 'password'){ p.type = 'text'; icon.innerHTML = '<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21\'/>'; } else { p.type = 'password'; icon.innerHTML = '<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M15 12a3 3 0 11-6 0 3 3 0 016 0z\' /><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z\' />'; }" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Aktif -->
                <div>
                    <label for="is_active" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="is_active" id="is_active" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2 px-3 border @error('is_active') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">
                        <option value="1" {{ (string) old('is_active', (string)(int)$user->is_active) === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ (string) old('is_active', (string)(int)$user->is_active) === '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @error('is_active')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6 border-t pt-4">
                <a href="{{ route('admin.users') }}" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm font-medium">
                    Batal
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
