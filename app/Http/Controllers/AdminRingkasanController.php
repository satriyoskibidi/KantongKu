<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\Pemasukan;
use App\Models\Pengguna;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminRingkasanController extends Controller
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
        $dailyIncome = Pemasukan::whereBetween('transaction_date', [$start, $end])
            ->get(['transaction_date', 'amount'])->groupBy(fn(Pemasukan $income) => $income->transaction_date->day);
        $dailyExpense = Pengeluaran::whereBetween('transaction_date', [$start, $end])
            ->get(['transaction_date', 'amount'])->groupBy(fn(Pengeluaran $expense) => $expense->transaction_date->day);
        $days = range(1, $period->daysInMonth);

        return view('admin.ringkasan', [
            'month' => $month,
            'period' => $period,
            'studentCount' => Pengguna::where('role', 'mahasiswa')->count(),
            'transactionCount' => $incomes->count() + $expenses->count(),
            'incomeTotal' => (float) $incomes->sum('amount'),
            'expenseTotal' => (float) $expenses->sum('amount'),
            'chartLabels' => $days,
            'chartIncome' => array_map(fn(int $day) => (float) ($dailyIncome->get($day)?->sum('amount') ?? 0), $days),
            'chartExpense' => array_map(fn(int $day) => (float) ($dailyExpense->get($day)?->sum('amount') ?? 0), $days),
        ]);
    }
}
