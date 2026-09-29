<?php

namespace App\Http\Controllers;

use App\Models\DailyRecap;
use App\Models\Role;
use App\Models\StaffAttendance;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

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

    public function createUser()
    {
        // For now, pass roles if needed, or just return the view.
        // I will pass the roles so the form can use them.
        $roles = Role::all();

        return view('admin.users-create', compact('roles'));
    }

    public function editUser(User $user)
    {
        $roles = Role::all();

        return view('admin.users-edit', compact('user', 'roles'));
    }

    public function deleteUser(User $user)
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
