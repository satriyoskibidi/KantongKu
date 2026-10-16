<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\Pemasukan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminLaporanController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate(['month' => ['nullable', 'date_format:Y-m']]);
        $month = $validated['month'] ?? now()->format('Y-m');
        $period = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $start = $period->toDateString();
        $end = $period->copy()->endOfMonth()->toDateString();
        $incomes = Pemasukan::whereBetween('transaction_date', [$start, $end]);
        $expenses = Pengeluaran::whereBetween('transaction_date', [$start, $end]);

        return view('admin.laporan.daftar', [
            'month' => $month,
            'period' => $period,
            'incomeTotal' => (float) $incomes->sum('amount'),
            'expenseTotal' => (float) $expenses->sum('amount'),
            'incomeCount' => $incomes->count(),
            'expenseCount' => $expenses->count(),
            'incomeCategories' => Pemasukan::whereBetween('transaction_date', [$start, $end])
                ->selectRaw('category, SUM(amount) as total')->groupBy('category')->orderByDesc('total')->get(),
            'expenseCategories' => Pengeluaran::whereBetween('transaction_date', [$start, $end])
                ->selectRaw('category, SUM(amount) as total')->groupBy('category')->orderByDesc('total')->get(),
        ]);
    }
}
