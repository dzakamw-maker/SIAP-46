<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home')->middleware('guest');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CashierController;

Route::middleware('auth')->group(function () {
    // Admin Routes
    Route::prefix('admin')->middleware('role:Admin')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/transactions', [AdminController::class, 'transactions'])->name('admin.transactions');
        Route::get('/attendance', [AdminController::class, 'attendance'])->name('admin.attendance');
        Route::get('/eod', [AdminController::class, 'eod'])->name('admin.eod');
        Route::post('/eod/{dailyRecap}/verify', [AdminController::class, 'verifyEod'])->name('admin.eod.verify');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::match(['put', 'patch'], '/users/{user}/edit', [AdminController::class, 'updateUser']);
        Route::get('/users/{user}/delete', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    });

    // Cashier Routes
    Route::prefix('kasir')->middleware('role:Kasir')->group(function () {
        Route::get('/', [CashierController::class, 'dashboard'])->name('kasir.dashboard');
        Route::get('/stamps', [CashierController::class, 'stamps'])->name('kasir.stamps');
        Route::get('/eod', [CashierController::class, 'eod'])->name('kasir.eod');
    });
});
