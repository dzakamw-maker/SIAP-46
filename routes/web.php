<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

Route::get('/', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CashierController;

Route::middleware('auth')->group(function () {
    // Admin Routes
    Route::prefix('admin')->middleware('role:Admin')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/transactions', [AdminController::class, 'transactions'])->name('admin.transactions');
        Route::get('/attendance', [AdminController::class, 'attendance'])->name('admin.attendance');
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    });

    // Cashier Routes
    Route::prefix('kasir')->middleware('role:Kasir')->group(function () {
        Route::get('/', [CashierController::class, 'dashboard'])->name('kasir.dashboard');
        Route::get('/stamps', [CashierController::class, 'stamps'])->name('kasir.stamps');
        Route::get('/eod', [CashierController::class, 'eod'])->name('kasir.eod');
    });
});
