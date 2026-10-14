<?php

use App\Http\Controllers\ExpenseController;
use Illuminate\Support\Facades\Route;

Route::resource('pengeluaran', ExpenseController::class)
    ->parameters(['pengeluaran' => 'expense'])
    ->names('expenses')
    ->except('show');