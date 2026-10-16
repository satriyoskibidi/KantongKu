<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\Pemasukan;
use App\Models\Pengguna;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminTransaksiController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'user_id' => ['nullable', 'integer', Rule::exists('users', 'id')->where('role', 'mahasiswa')],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
            'category' => ['nullable', 'string', 'max:60'],
            'type' => ['nullable', Rule::in(['income', 'expense'])],
        ]);

        $income = DB::table('incomes')->join('users', 'users.id', '=', 'incomes.user_id')
            ->select('incomes.id', 'users.name as user_name', 'incomes.title', 'incomes.category', 'incomes.amount', 'incomes.transaction_date', DB::raw("'Pemasukan' as transaction_type"));
        $expense = DB::table('expenses')->join('users', 'users.id', '=', 'expenses.user_id')
            ->select('expenses.id', 'users.name as user_name', 'expenses.title', 'expenses.category', 'expenses.amount', 'expenses.transaction_date', DB::raw("'Pengeluaran' as transaction_type"));

        foreach ([$income, $expense] as $query) {
            $prefix = $query === $income ? 'incomes' : 'expenses';
            $query->when($filters['user_id'] ?? null, fn(Builder $builder, int $userId) => $builder->where("$prefix.user_id", $userId))
                ->when($filters['from'] ?? null, fn(Builder $builder, string $date) => $builder->whereDate("$prefix.transaction_date", '>=', $date))
                ->when($filters['to'] ?? null, fn(Builder $builder, string $date) => $builder->whereDate("$prefix.transaction_date", '<=', $date))
                ->when($filters['category'] ?? null, fn(Builder $builder, string $category) => $builder->where("$prefix.category", $category));
        }

        $query = ($filters['type'] ?? null) === 'income'
            ? $income
            : (($filters['type'] ?? null) === 'expense' ? $expense : $income->unionAll($expense));
        $transactions = DB::query()->fromSub($query, 'transactions')
            ->orderByDesc('transaction_date')->orderByDesc('id')->paginate(20)->withQueryString();
        $categories = Pemasukan::query()->distinct()->pluck('category')
            ->merge(Pengeluaran::query()->distinct()->pluck('category'))->unique()->sort()->values();

        return view('admin.transaksi.daftar', [
            'transactions' => $transactions,
            'filters' => $filters,
            'students' => Pengguna::where('role', 'mahasiswa')->orderBy('name')->get(),
            'categories' => $categories,
        ]);
    }
}
