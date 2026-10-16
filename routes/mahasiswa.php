<?php

use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\AdminProfilController;
use App\Http\Controllers\AdminTransaksiController;
use App\Http\Controllers\AdminLaporanController;
use Illuminate\Support\Facades\Route;

Route::middleware('role:admin')->group(function () {
    Route::resource('mahasiswa', MahasiswaController::class)
        ->parameters(['mahasiswa' => 'user'])
        ->names('users')
        ->except('show');

    Route::resource('kategori', KategoriController::class)
        ->parameters(['kategori' => 'category'])
        ->names('categories')
        ->except('show');

    Route::patch('mahasiswa/{user}/status', [MahasiswaController::class, 'toggleStatus'])->name('users.status');
    Route::get('admin/profil', [AdminProfilController::class, 'edit'])->name('admin.profile.edit');
    Route::put('admin/profil', [AdminProfilController::class, 'update'])->name('admin.profile.update');
    Route::get('admin/transaksi', [AdminTransaksiController::class, 'index'])->name('admin.transactions.index');
    Route::get('admin/laporan', [AdminLaporanController::class, 'index'])->name('admin.reports.index');
});
