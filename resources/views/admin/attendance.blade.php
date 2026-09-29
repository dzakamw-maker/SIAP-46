@extends('layouts.admin')

@section('page_title', 'Rekap Presensi')

@section('admin_content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50">
                <tr>
                    <th class="px-4 py-3 rounded-l-lg">Tanggal</th>
                    <th class="px-4 py-3">Nama Petugas / Kasir</th>
                    <th class="px-4 py-3">Kelas</th>
                    <th class="px-4 py-3">Catatan Siswa</th>
                    <th class="px-4 py-3">Catatan Guru</th>
                    <th class="px-4 py-3 rounded-r-lg">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $att)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">{{ \Carbon\Carbon::parse($att->attendance_date)->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">{{ $att->user->full_name }}</td>
                    <td class="px-4 py-3">{{ $att->user->class_group }}</td>
                    <td class="px-4 py-3">{{ $att->student_note ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $att->teacher_note ?? '-' }}</td>
                    <td class="px-4 py-3">
                        <button class="text-teal-600 hover:underline">Edit Catatan</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">Belum ada data presensi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="mt-4">
            {{ $attendances->links() }}
        </div>
    </div>
</div>
@endsection
