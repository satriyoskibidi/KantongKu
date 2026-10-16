<?php

use App\Http\Controllers\PengeluaranController;
use Illuminate\Support\Facades\Route;

Route::resource('pengeluaran', PengeluaranController::class)
    ->parameters(['pengeluaran' => 'expense'])
    ->names('expenses')
    ->except('show');