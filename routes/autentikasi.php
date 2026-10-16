<?php

use App\Http\Controllers\AutentikasiController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/register', [AutentikasiController::class, 'createAccount'])->name('register');
    Route::post('/register', [AutentikasiController::class, 'storeAccount'])->name('register.store');
    Route::get('/login', [AutentikasiController::class, 'create'])->name('login');
    Route::post('/login', [AutentikasiController::class, 'store'])->name('login.store');
});

Route::post('/logout', [AutentikasiController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');