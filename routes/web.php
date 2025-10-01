<?php

use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\StokGudangController;
use App\Http\Controllers\StokTokoController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;


Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])
    ->name('transactions.show');
// 
Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');
// 
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
// Stok Gudang

// 

Route::get('/stok-gudang', [StokGudangController::class,'index'])->name('stok-gudang.index');
Route::post('/stok-gudang', [StokGudangController::class,'store'])->name('stok-gudang.store');
Route::post('/stok-gudang/update/{id}', [StokGudangController::class,'update'])->name('stok-gudang.update');
Route::delete('/stok-gudang/delete/{id}', [StokGudangController::class,'destroy'])->name('stok-gudang.destroy');

Route::put('/shifts/{shift}/close', [ShiftController::class, 'close'])->name('shifts.close');
Route::resource('shifts', ShiftController::class)->middleware('auth');
Route::resource('expenses', ExpenseController::class);

// penjualan
// 🔹 Route untuk create harus di atas route yang pakai {id}
Route::get('kasir/create', [TransactionController::class, 'create'])->name('kasir.create');

Route::post('transactions', [TransactionController::class, 'store'])->name('transactions.store');
Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
Route::get('transactions/{id}/receipt', [TransactionController::class, 'receipt'])->name('transactions.receipt');

// Stok Toko
Route::get('/stok-toko', [StokTokoController::class,'index'])->name('stok-toko.index');
Route::post('/stok-toko', [StokTokoController::class,'store'])->name('stok-toko.store');
Route::post('/stok-toko/update/{id}', [StokTokoController::class,'update'])->name('stok-toko.update');

Route::resource('products', ProductController::class);