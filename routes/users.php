<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('role:admin')->group(function () {
    Route::resource('mahasiswa', UserController::class)
        ->parameters(['mahasiswa' => 'user'])
        ->names('users')
        ->except('show');
});