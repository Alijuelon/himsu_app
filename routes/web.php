<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
// Controller di bawah ini akan kita buat nanti, saya deklarasikan agar route-nya siap
use App\Http\Controllers\Admin\AnggotaController;
use App\Http\Controllers\Admin\PembayaranKasController;
use App\Http\Controllers\Admin\BukuKasController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Anggota\BayarKasController;
use App\Http\Controllers\Anggota\RiwayatController;
use App\Http\Controllers\Admin\PeriodeKasController;
use App\Http\Controllers\Admin\WaNotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ROUTE DASHBOARD (Akan diarahkan sesuai role oleh Controller)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// ROUTE PROFIL (Bawaan Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==========================================
// ROUTE KHUSUS ADMIN / BENDAHARA
// ==========================================
// Nanti Anda bisa membuat middleware 'role:admin' khusus, sementara ini kita pakai 'auth'
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // Kelola Data Anggota
    Route::resource('anggota', AnggotaController::class);
    
    // Kelola Master Periode Kas (TAMBAHKAN INI)
    Route::resource('periode', PeriodeKasController::class);
    Route::post('periode/{id}/tagihan', [PeriodeKasController::class, 'sendTagihan'])->name('periode.tagihan');
    
    // Kelola Pembayaran (Verifikasi dll)
    Route::get('pembayaran', [PembayaranKasController::class, 'index'])->name('pembayaran.index');
    Route::put('pembayaran/{id}/verifikasi', [PembayaranKasController::class, 'verifikasi'])->name('pembayaran.verifikasi');
    
   // Buku Kas (Pemasukan & Pengeluaran)
    Route::prefix('buku-kas')->name('bukukas.')->group(function () {
        Route::get('pemasukan', [BukuKasController::class, 'pemasukan'])->name('pemasukan');
        Route::get('pengeluaran', [BukuKasController::class, 'pengeluaran'])->name('pengeluaran');
        
        // Route CRUD untuk Buku Kas
        Route::post('store', [BukuKasController::class, 'store'])->name('store');
        Route::put('{id}/update', [BukuKasController::class, 'update'])->name('update');
        Route::delete('{id}/destroy', [BukuKasController::class, 'destroy'])->name('destroy');
    });
    // Laporan Keuangan
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');

    // Notifikasi WA
    Route::prefix('wa-notification')->name('wa.')->group(function () {
        Route::get('settings', [WaNotificationController::class, 'settings'])->name('settings');
        Route::post('settings', [WaNotificationController::class, 'updateSettings'])->name('settings.update');
        Route::get('members', [WaNotificationController::class, 'members'])->name('members');
        Route::put('members/{id}', [WaNotificationController::class, 'updateMemberWa'])->name('members.update');
    });

});

// ==========================================
// ROUTE KHUSUS ANGGOTA
// ==========================================
Route::middleware(['auth'])->prefix('anggota')->name('anggota.')->group(function () {
    
    // Bayar Kas
    Route::get('bayar', [BayarKasController::class, 'create'])->name('bayar.create');
    Route::post('bayar', [BayarKasController::class, 'store'])->name('bayar.store');
    
    // Riwayat Pribadi
    Route::get('riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
    
    // Info Saldo (Opsional, bisa ditaruh di Dashboard Anggota saja)
    Route::get('info-saldo', [RiwayatController::class, 'saldo'])->name('saldo.index');

});

require __DIR__.'/auth.php';