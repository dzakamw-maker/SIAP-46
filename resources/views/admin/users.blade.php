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

<div class="mb-4 flex flex-wrap items-center justify-between gap-3">
    <!-- Form Pencarian & Dropdown Filter (Kompak & Pendek) -->
    <form method="GET" action="{{ route('admin.users') }}" id="userFilterForm" class="flex flex-wrap items-center gap-2">
        <!-- Kolom Ketik Search (Pendek / Kompak) -->
        <div class="relative flex items-center" style="width: 210px;">
            <span class="pointer-events-none text-gray-400 flex items-center justify-center" style="position: absolute; left: 0.65rem; top: 50%; transform: translateY(-50%); width: 1rem; height: 1rem; z-index: 10;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 1rem; height: 1rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </span>
            <input 
                type="text" 
                id="searchUserInput"
                name="search" 
                value="{{ $search ?? '' }}" 
                placeholder="Cari user / NIS..." 
                class="w-full text-sm bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors shadow-sm"
                style="padding-left: 2.25rem !important; padding-right: 2rem !important; padding-top: 0.5rem; padding-bottom: 0.5rem;"
                autocomplete="off"
            >
            @if(!empty($search))
                <a href="{{ route('admin.users', array_filter(['role' => $selectedRole ?? null, 'status' => $selectedStatus ?? null])) }}" class="text-gray-400 hover:text-gray-600 flex items-center justify-center" style="position: absolute; right: 0.65rem; top: 50%; transform: translateY(-50%); width: 1rem; height: 1rem; z-index: 10;" title="Hapus teks pencarian">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 0.875rem; height: 0.875rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            @endif
        </div>

        <!-- Dropdown Filter Role (Kompak) -->
        <select 
            name="role" 
            id="roleFilterSelect"
            onchange="this.form.submit()" 
            class="w-auto py-2 pl-3 pr-8 text-sm bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors shadow-sm text-gray-700 cursor-pointer"
        >
            <option value="">Semua Role</option>
            @foreach($roles as $role)
                <option value="{{ $role->name }}" {{ ($selectedRole ?? '') === $role->name ? 'selected' : '' }}>
                    {{ $role->name }}
                </option>
            @endforeach
        </select>

        <!-- Dropdown Filter Status (Kompak) -->
        <select 
            name="status" 
            id="statusFilterSelect"
            onchange="this.form.submit()" 
            class="w-auto py-2 pl-3 pr-8 text-sm bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors shadow-sm text-gray-700 cursor-pointer"
        >
            <option value="">Semua Status</option>
            <option value="1" {{ ($selectedStatus ?? '') === '1' ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ ($selectedStatus ?? '') === '0' ? 'selected' : '' }}>Nonaktif</option>
        </select>

        @if(!empty($search) || !empty($selectedRole) || ($selectedStatus !== null && $selectedStatus !== ''))
            <a href="{{ route('admin.users') }}" class="text-xs text-red-600 hover:text-red-700 hover:underline font-medium inline-flex items-center gap-1 px-2 py-1" title="Reset filter">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Reset
            </a>
        @endif
    </form>

    <!-- Tombol Tambah User (Kompak / W-Auto) -->
    <a href="{{ route('admin.users.create') }}" class="w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center shadow-sm shrink-0 whitespace-nowrap">
        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Tambah User Baru
    </a>
</div>

@php
    $hasActiveFilter = !empty($search) || !empty($selectedRole) || ($selectedStatus !== null && $selectedStatus !== '');
@endphp

@if($hasActiveFilter)
<div class="mb-3 text-xs text-gray-500 flex flex-wrap items-center justify-between gap-2">
    <span>
        Menampilkan <strong>{{ $users->count() }}</strong> user
        @if(!empty($search)) dengan kata kunci "<strong class="text-gray-800">{{ $search }}</strong>"@endif
        @if(!empty($selectedRole)) [Role: <strong class="text-gray-800">{{ $selectedRole }}</strong>]@endif
        @if($selectedStatus !== null && $selectedStatus !== '') [Status: <strong class="text-gray-800">{{ $selectedStatus === '1' ? 'Aktif' : 'Nonaktif' }}</strong>]@endif
    </span>
    <a href="{{ route('admin.users') }}" class="text-blue-600 hover:underline font-medium">Tampilkan Semua</a>
</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6">
        <div class="overflow-x-auto">
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
                <tbody id="userTableBody">
                    @forelse($users as $user)
                    <tr class="user-row border-b hover:bg-gray-50" data-role="{{ $user->role?->name ?? '' }}" data-status="{{ $user->is_active ? '1' : '0' }}">
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
                    @empty
                    <tr id="serverEmptyRow">
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            @if($hasActiveFilter)
                                <p class="font-medium text-gray-700">Tidak ada user yang cocok dengan filter yang dipilih.</p>
                                <a href="{{ route('admin.users') }}" class="mt-2 inline-block text-xs text-blue-600 hover:underline font-medium">Reset Filter</a>
                            @else
                                <p class="font-medium text-gray-700">Belum ada data user.</p>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                    <tr id="clientEmptyRow" style="display: none;">
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            <p class="font-medium text-gray-700">Tidak ada user yang cocok pada tampilan ini.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchUserInput');
        const roleSelect = document.getElementById('roleFilterSelect');
        const statusSelect = document.getElementById('statusFilterSelect');
        const rows = document.querySelectorAll('.user-row');
        const clientEmpty = document.getElementById('clientEmptyRow');

        function filterRows() {
            const query = (searchInput ? searchInput.value : '').toLowerCase().trim();
            const selectedRole = (roleSelect ? roleSelect.value : '').toLowerCase();
            const selectedStatus = statusSelect ? statusSelect.value : '';
            let visibleCount = 0;

            rows.forEach(function (row) {
                const text = row.textContent.toLowerCase();
                const rowRole = (row.getAttribute('data-role') || '').toLowerCase();
                const rowStatus = row.getAttribute('data-status') || '';

                const matchesQuery = (query === '' || text.includes(query));
                const matchesRole = (selectedRole === '' || rowRole === selectedRole);
                const matchesStatus = (selectedStatus === '' || rowStatus === selectedStatus);

                if (matchesQuery && matchesRole && matchesStatus) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (clientEmpty) {
                clientEmpty.style.display = (visibleCount === 0 && (query !== '' || selectedRole !== '' || selectedStatus !== '')) ? '' : 'none';
            }
        }

        if (searchInput && rows.length > 0) {
            searchInput.addEventListener('input', filterRows);
        }
    });
</script>
@endsection
