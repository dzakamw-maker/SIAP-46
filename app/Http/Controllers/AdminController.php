<?php

namespace App\Http\Controllers;

use App\Models\DailyRecap;
use App\Models\Role;
use App\Models\StaffAttendance;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function transactions()
    {
        $transactions = Transaction::with(['customer', 'cashier', 'transactionType'])->latest()->paginate(15);

        return view('admin.transactions', compact('transactions'));
    }

    public function attendance()
    {
        $attendances = StaffAttendance::with('user')->latest('attendance_date')->paginate(15);

        return view('admin.attendance', compact('attendances'));
    }

    public function users()
    {
        $users = User::with('role')->get();

        return view('admin.users', compact('users'));
    }

    public function createUser(): View
    {
        $roles = Role::all();

        return view('admin.users-create', compact('roles'));
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'role_id' => ['required', 'exists:roles,id'],
            'student_number' => ['nullable', 'string', 'max:50'],
            'class_group' => ['nullable', 'string', 'max:50'],
            'password' => ['required', 'string', 'min:6'],
            'is_active' => ['required', 'boolean'],
        ], [
            'full_name.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan, silakan pilih username lain.',
            'role_id.required' => 'Role wajib dipilih.',
            'role_id.exists' => 'Role yang dipilih tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus 6 karakter.',
        ]);

        User::create($validated);

        return redirect()->route('admin.users')->with('success', 'User '.$validated['full_name'].' berhasil ditambahkan!');
    }

    public function editUser(User $user): View
    {
        $roles = Role::all();

        return view('admin.users-edit', compact('user', 'roles'));
    }

    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,'.$user->id],
            'role_id' => ['required', 'exists:roles,id'],
            'student_number' => ['nullable', 'string', 'max:50'],
            'class_group' => ['nullable', 'string', 'max:50'],
            'password' => ['nullable', 'string', 'min:6'],
            'is_active' => ['required', 'boolean'],
        ], [
            'full_name.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan, silakan pilih username lain.',
            'role_id.required' => 'Role wajib dipilih.',
            'role_id.exists' => 'Role yang dipilih tidak valid.',
            'password.min' => 'Password minimal harus 6 karakter.',
        ]);

        // Cegah perubahan yang membahayakan jika user ini satu-satunya Admin tersisa
        $user->loadMissing('role');
        if ($user->role && $user->role->name === 'Admin') {
            $totalAdmin = User::whereRelation('role', 'name', 'Admin')->count();
            if ($totalAdmin <= 1) {
                $adminRole = Role::where('name', 'Admin')->first();
                if ($adminRole && (int) $request->role_id !== (int) $adminRole->id) {
                    return back()->withErrors(['role_id' => 'Tidak dapat mengubah role karena user ini adalah satu-satunya Admin yang tersisa di sistem.'])->withInput();
                }

                if (! $request->boolean('is_active')) {
                    return back()->withErrors(['is_active' => 'Tidak dapat menonaktifkan satu-satunya Admin yang tersisa di sistem.'])->withInput();
                }
            }
        }

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users')->with('success', 'Data user '.$user->full_name.' berhasil diperbarui!');
    }

    public function deleteUser(User $user): View|RedirectResponse
    {
        // 1. Cegah menghapus akun yang sedang digunakan (diri sendiri)
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri demi keamanan sistem.');
        }

        // 2. Cegah menghapus jika merupakan satu-satunya Admin
        $user->loadMissing('role');
        if ($user->role && $user->role->name === 'Admin') {
            $totalAdmin = User::whereRelation('role', 'name', 'Admin')->count();
            if ($totalAdmin <= 1) {
                return redirect()->route('admin.users')->with('error', 'Tidak dapat menghapus user ini karena merupakan satu-satunya Admin yang tersisa di sistem.');
            }
        }

        return view('admin.users-delete', compact('user'));
    }

    public function destroyUser(User $user): RedirectResponse
    {
        // 1. Cegah menghapus akun yang sedang digunakan (diri sendiri)
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri demi keamanan sistem.');
        }

        // 2. Cegah menghapus jika merupakan satu-satunya Admin
        $user->loadMissing('role');
        if ($user->role && $user->role->name === 'Admin') {
            $totalAdmin = User::whereRelation('role', 'name', 'Admin')->count();
            if ($totalAdmin <= 1) {
                return redirect()->route('admin.users')->with('error', 'Tidak dapat menghapus user ini karena merupakan satu-satunya Admin yang tersisa di sistem.');
            }
        }

        $fullName = $user->full_name;
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User '.$fullName.' berhasil dihapus secara permanen.');
    }

    public function eod()
    {
        $recaps = DailyRecap::with(['recordedBy', 'verifiedBy'])->latest('recap_date')->paginate(15);

        return view('admin.eod', compact('recaps'));
    }

    public function verifyEod(Request $request, DailyRecap $dailyRecap)
    {
        $dailyRecap->update([
            'verified_by' => auth()->id(),
            'notes' => $request->filled('teacher_note')
                ? ($dailyRecap->notes ? $dailyRecap->notes.' | Catatan Guru: '.$request->teacher_note : 'Catatan Guru: '.$request->teacher_note)
                : $dailyRecap->notes,
        ]);

        return back()->with('success', 'Laporan Rekap Harian tanggal '.$dailyRecap->recap_date->format('d/m/Y').' berhasil diverifikasi!');
    }
}
