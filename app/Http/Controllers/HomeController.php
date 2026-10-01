<?php

namespace App\Http\Controllers;

use App\Models\TransactionType;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman beranda (homepage) publik SIAP46.
     */
    public function index(): View
    {
        $services = collect();

        if (Schema::hasTable('transaction_types')) {
            $services = TransactionType::query()
                ->where('is_active', true)
                ->get();
        }

        return view('home', [
            'services' => $services,
        ]);
    }
}
