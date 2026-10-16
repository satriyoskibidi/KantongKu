<?php

use App\Http\Controllers\PemasukanController;
use Illuminate\Support\Facades\Route;

Route::resource('pemasukan', PemasukanController::class)
    ->parameters(['pemasukan' => 'income'])
    ->names('incomes')
    ->except('show');
