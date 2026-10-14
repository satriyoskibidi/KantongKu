<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Income;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'month' => ['nullable', 'date_format:Y-m'],
            'user_id' => ['nullable', 'integer', Rule::exists('users', 'id')->where('role', 'mahasiswa')],
        ]);
        $user = $request->user();
        $month = $validated['month'] ?? now()->format('Y-m');
        $period = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $previousPeriod = $period->copy()->subMonth();
        $selectedUserId = $user->isAdmin()
            ? ($validated['user_id'] ?? null)
            : $user->id;

        $incomeQuery = fn() => Income::query()->when($selectedUserId, fn(Builder $query) => $query->where('user_id', $selectedUserId));
        $expenseQuery = fn() => Expense::query()->when($selectedUserId, fn(Builder $query) => $query->where('user_id', $selectedUserId));
        $periodTotals = function (callable $query, Carbon $start) {
            return $query()->whereBetween('transaction_date', [
                $start->copy()->startOfMonth()->toDateString(),
                $start->copy()->endOfMonth()->toDateString(),
            ])->sum('amount');
        };

        $incomeTotal = (float) $periodTotals($incomeQuery, $period);
        $expenseTotal = (float) $periodTotals($expenseQuery, $period);
        $previousBalance = (float) $periodTotals($incomeQuery, $previousPeriod)
            - (float) $periodTotals($expenseQuery, $previousPeriod);
        $balance = $incomeTotal - $expenseTotal;
        $savingsRate = $incomeTotal > 0 ? max(0, round(($balance / $incomeTotal) * 100)) : 0;
        $days = range(1, $period->daysInMonth);
        $dailyIncome = $incomeQuery()->whereBetween('transaction_date', [
            $period->toDateString(),
            $period->copy()->endOfMonth()->toDateString(),
        ])->get(['transaction_date', 'amount'])->groupBy(fn(Income $income) => $income->transaction_date->day);
        $dailyExpense = $expenseQuery()->whereBetween('transaction_date', [
            $period->toDateString(),
            $period->copy()->endOfMonth()->toDateString(),
        ])->get(['transaction_date', 'amount'])->groupBy(fn(Expense $expense) => $expense->transaction_date->day);

        $recent = $incomeQuery()->with('user')->whereBetween('transaction_date', [
            $period->toDateString(),
            $period->copy()->endOfMonth()->toDateString(),
        ])->get()->map(fn(Income $item) => [
                'type' => 'income',
                'title' => $item->title,
                'amount' => (float) $item->amount,
                'date' => $item->transaction_date,
                'user' => $item->user->name,
            ])->concat($expenseQuery()->with('user')->whereBetween('transaction_date', [
                $period->toDateString(),
                $period->copy()->endOfMonth()->toDateString(),
            ])->get()->map(fn(Expense $item) => [
                    'type' => 'expense',
                    'title' => $item->title,
                    'amount' => (float) $item->amount,
                    'date' => $item->transaction_date,
                    'user' => $item->user->name,
                ]))->sortByDesc('date')->take(8)->values();

        $insight = $incomeTotal === 0 && $expenseTotal === 0
            ? 'Belum ada transaksi bulan ini. Catat transaksi pertamamu untuk mulai melihat pola keuangan.'
            : ($balance < 0
                ? 'Pengeluaran melampaui pemasukan sebesar Rp ' . number_format(abs($balance), 0, ',', '.') . '. Tinjau kembali pos belanja bulan ini.'
                : ($savingsRate >= 20
                    ? 'Bagus, ' . number_format($savingsRate, 0, ',', '.') . '% pemasukan berhasil disisihkan bulan ini.'
                    : 'Sisa uang bulan ini Rp ' . number_format($balance, 0, ',', '.') . '. Coba sisihkan minimal 20% pemasukan.'));

        return view('dashboard', [
            'month' => $month,
            'period' => $period,
            'incomeTotal' => $incomeTotal,
            'expenseTotal' => $expenseTotal,
            'balance' => $balance,
            'previousBalance' => $previousBalance,
            'balanceChange' => $balance - $previousBalance,
            'savingsRate' => $savingsRate,
            'insight' => $insight,
            'recentTransactions' => $recent,
            'chartLabels' => $days,
            'chartIncome' => array_map(fn(int $day) => (float) ($dailyIncome->get($day)?->sum('amount') ?? 0), $days),
            'chartExpense' => array_map(fn(int $day) => (float) ($dailyExpense->get($day)?->sum('amount') ?? 0), $days),
            'students' => $user->isAdmin() ? User::where('role', 'mahasiswa')->orderBy('name')->get() : collect(),
            'selectedUserId' => $selectedUserId,
        ]);
    }
}