<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ProdukController;
use App\Models\Member;
use App\Models\produk;
use App\Models\User;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;

Route::get('/', [AuthController::class, 'showLoginForm'])->name('home');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [ProdukController::class, 'index'])->name('index.admin');

    Route::get('/products/create', [ProdukController::class, 'create'])->name('products.create');
    Route::post('/products', [ProdukController::class, 'store'])->name('products.store');
    Route::get('/products/{id}', [ProdukController::class, 'show'])->name('products.show');
    Route::get('/filter-products', [ProdukController::class, 'filter'])->name('products.filter');
    Route::get('/search-products', [ProdukController::class, 'search'])->name('products.search');
    Route::get('/products/{id}/edit', [ProdukController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [ProdukController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [ProdukController::class, 'destroy'])->name('products.destroy');
});

Route::middleware(['auth', 'role:kasir'])->group(function () {
    Route::get('/kasir', [KasirController::class, 'index'])->name('index.kasir');
    Route::post('/kasir/members', [KasirController::class, 'store'])->name('members.store');
    Route::put('/kasir/members/{id}', [KasirController::class, 'update'])->name('members.update');
    Route::delete('/kasir/members/{id}', [KasirController::class, 'destroy'])->name('members.destroy');

    Route::get('/penjualan-kasir', [PenjualanController::class, 'index'])->name('penjualans.index');
    Route::post('/penjualans', [PenjualanController::class, 'store'])->name('penjualans.store');

    Route::get('/penjualanKasir', [KasirController::class, 'penjualan'])->name('penjualanKasir');
    Route::get('/kasirM', function () {
        $members = Member::with('user')->get();
    return view('kasir.memberKasir', compact('members'));
    })->name('kasirM');
});

Route::middleware(['auth', 'role:member'])->group(function () {
    Route::get('/indexMember', [MemberController::class, 'index'])->name('index.member');
    Route::get('/transMember', [MemberController::class, 'transaksi'])->name('trans.member');
});

Route::middleware(['auth', 'role:pimpinan'])->group(function () {
    Route::get('/pimpinan', [LaporanController::class, 'index'])->name('index.pimpinan');
    Route::get('/laporan', [LaporanController::class, 'laporan'])->name('laporan.index');
    Route::get('/laporan-penjualan', [LaporanController::class, 'laporanPenjualan']);
    Route::get('/laporan/download-pdf', [LaporanController::class, 'downloadPDF'])->name('laporan.download');
});

Route::get('/check-email', function (Request $request) {
    $email = $request->query('email');
    $exists = User::where('email', $email)->exists();

    return response()->json(['exists' => $exists]);
})->name('checkEmail');
