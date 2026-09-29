<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\StaffAttendance;
use App\Models\User;

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
}
