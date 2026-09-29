@extends('layouts.admin')

@section('page_title', 'Kelola User')

@section('admin_content')
<div class="mb-4 flex justify-end">
    <button class="bg-teal-700 hover:bg-teal-800 text-white px-4 py-2 rounded-lg text-sm font-medium">
        + Tambah User Baru
    </button>
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
                    <td class="px-4 py-3 font-medium">{{ $user->full_name }}</td>
                    <td class="px-4 py-3">{{ $user->username }}</td>
                    <td class="px-4 py-3">
                        <span class="{{ $user->role->name == 'Admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }} text-xs px-2.5 py-0.5 rounded">
                            {{ $user->role->name }}
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
                    <td class="px-4 py-3 flex space-x-2">
                        <button class="text-blue-600 hover:underline">Edit</button>
                        <button class="text-red-600 hover:underline">Hapus</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
