@extends('layouts.admin')

@section('page_title', 'Kelola User')

@section('admin_content')
@if(session('error'))
<div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm flex items-center justify-between">
    <div class="flex items-center">
        <svg class="w-5 h-5 mr-3 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <span>{{ session('error') }}</span>
    </div>
</div>
@endif

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

<div class="mb-4 flex justify-end">
    <a href="{{ route('admin.users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-block">
        + Tambah User Baru
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50">
                <tr>
                    <th class="px-4 py-3 rounded-l-lg">Nama Lengkap</th>
                    <th class="px-4 py-3">Username</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3">NIS / Kelas</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 rounded-r-lg">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">
                        {{ $user->full_name }}
                        @if($user->id === auth()->id())
                            <span class="ml-1 text-xs text-teal-700 bg-teal-50 px-2 py-0.5 rounded font-normal border border-teal-200">Anda</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">{{ $user->username }}</td>
                    <td class="px-4 py-3">
                        <span class="{{ ($user->role?->name ?? '') == 'Admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }} text-xs px-2.5 py-0.5 rounded">
                            {{ $user->role?->name ?? '-' }}
                        </span>
                    </td>
                    <td class="px-4 py-3">{{ $user->student_number ? $user->student_number . ' / ' . $user->class_group : '-' }}</td>
                    <td class="px-4 py-3">
                        @if($user->is_active)
                            <span class="text-green-600 font-medium">Aktif</span>
                        @else
                            <span class="text-red-600 font-medium">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 flex items-center space-x-3">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-600 hover:underline font-medium">Edit</a>
                        @if($user->id !== auth()->id())
                            <a href="{{ route('admin.users.delete', $user->id) }}" class="text-red-600 hover:underline font-medium">Hapus</a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
