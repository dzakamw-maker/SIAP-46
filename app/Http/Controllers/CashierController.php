<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransactionType;
use App\Models\Transaction;
use App\Models\StampDutyRecord;

class CashierController extends Controller
{
    public function dashboard()
    {
        $types = TransactionType::where('is_active', true)->get();
        return view('cashier.dashboard', compact('types'));
    }

    public function stamps()
    {
        return view('cashier.stamps');
    }

    public function eod()
    {
        return view('cashier.eod');
    }
}
