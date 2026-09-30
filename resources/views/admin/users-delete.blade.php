@extends('layouts.admin')

@section('page_title', 'Hapus User')

@section('admin_content')
<div class="mb-4 flex">
    <a href="{{ route('admin.users') }}" class="text-gray-500 hover:text-gray-700 flex items-center text-sm font-medium">
        &larr; Kembali ke Daftar User
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden max-w-2xl">
    <div class="p-6">
        <div class="flex items-start space-x-4 mb-6">
            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Hapus User</h3>
                <p class="text-sm text-gray-500 mt-1">
                    Apakah Anda yakin ingin menghapus user ini? Tindakan ini tidak dapat dibatalkan dan seluruh hak akses user akan dihapus.
                </p>
            </div>
        </div>

        <!-- Detail User yang akan dihapus -->
        <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 space-y-3 mb-6">
            <div class="flex justify-between items-center text-sm py-1 border-b border-gray-200/60">
                <span class="text-gray-500 font-medium">Nama Lengkap</span>
                <span class="text-gray-800 font-semibold">{{ $user->full_name }}</span>
            </div>
            <div class="flex justify-between items-center text-sm py-1 border-b border-gray-200/60">
                <span class="text-gray-500 font-medium">Username</span>
                <span class="text-gray-800">{{ $user->username }}</span>
            </div>
            <div class="flex justify-between items-center text-sm py-1 border-b border-gray-200/60">
                <span class="text-gray-500 font-medium">Role</span>
                <span class="{{ $user->role && $user->role->name == 'Admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }} text-xs px-2.5 py-0.5 rounded font-medium">
                    {{ $user->role->name ?? '-' }}
                </span>
            </div>
            <div class="flex justify-between items-center text-sm py-1 border-b border-gray-200/60">
                <span class="text-gray-500 font-medium">NIS / Kelas</span>
                <span class="text-gray-800">{{ $user->student_number ? $user->student_number . ' / ' . $user->class_group : '-' }}</span>
            </div>
            <div class="flex justify-between items-center text-sm py-1">
                <span class="text-gray-500 font-medium">Status</span>
                <span>
                    @if($user->is_active)
                        <span class="text-green-600 font-medium text-xs bg-green-50 px-2 py-0.5 rounded border border-green-200">Aktif</span>
                    @else
                        <span class="text-red-600 font-medium text-xs bg-red-50 px-2 py-0.5 rounded border border-red-200">Nonaktif</span>
                    @endif
                </span>
            </div>
        </div>

        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
            @csrf
            @method('DELETE')
            
            <div class="flex justify-end space-x-3 pt-2">
                <a href="{{ route('admin.users') }}" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm font-medium">
                    Batal
                </a>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm">
                    Ya, Hapus User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
