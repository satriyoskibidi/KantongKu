<?php

use App\Http\Controllers\IncomeController;
use Illuminate\Support\Facades\Route;

Route::resource('pemasukan', IncomeController::class)
    ->parameters(['pemasukan' => 'income'])
    ->names('incomes')
    ->except('show');
