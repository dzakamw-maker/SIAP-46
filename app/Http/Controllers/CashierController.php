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
        $stampsStock = StampDutyRecord::latest('id')->value('remaining_stock') ?? 0;
        return view('cashier.dashboard', compact('types', 'stampsStock'));
    }

    public function stamps()
    {
        $stampsStock = StampDutyRecord::latest('id')->value('remaining_stock') ?? 0;
        return view('cashier.stamps', compact('stampsStock'));
    }

    public function eod()
    {
        return view('cashier.eod');
    }
}
