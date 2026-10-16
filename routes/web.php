<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

require __DIR__ . '/autentikasi.php';

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\RingkasanController::class, 'index'])
        ->name('dashboard');

    require __DIR__ . '/pemasukan.php';
    require __DIR__ . '/pengeluaran.php';
    require __DIR__ . '/mahasiswa.php';
});
