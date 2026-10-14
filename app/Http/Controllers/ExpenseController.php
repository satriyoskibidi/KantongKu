<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseRequest;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);
        $query = Expense::query()->with('user');

        if (!$request->user()->isAdmin()) {
            $query->where('user_id', $request->user()->id);
        } elseif (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        $expenses = $query
            ->when($filters['q'] ?? null, fn(Builder $query, string $search) => $query->where('title', 'like', "%$search%"))
            ->when($filters['from'] ?? null, fn(Builder $query, string $date) => $query->whereDate('transaction_date', '>=', $date))
            ->when($filters['to'] ?? null, fn(Builder $query, string $date) => $query->whereDate('transaction_date', '<=', $date))
            ->orderByDesc('transaction_date')->orderByDesc('id')->paginate(10)->withQueryString();

        return view('expenses.index', [
            'expenses' => $expenses,
            'filters' => $filters,
            'students' => $request->user()->isAdmin()
                ? User::where('role', 'mahasiswa')->orderBy('name')->get()
                : collect(),
        ]);
    }

    public function create(Request $request): View
    {
        return view('expenses.create', ['students' => $this->studentsFor($request)]);
    }

    public function store(ExpenseRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->isAdmin() ? $data['user_id'] : $request->user()->id;
        Expense::create($data);

        return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil disimpan.');
    }

    public function edit(Request $request, Expense $expense): View
    {
        $this->ensureVisible($request, $expense);

        return view('expenses.edit', [
            'expense' => $expense,
            'students' => $this->studentsFor($request),
        ]);
    }

    public function update(ExpenseRequest $request, Expense $expense): RedirectResponse
    {
        $this->ensureVisible($request, $expense);
        $data = $request->validated();
        $data['user_id'] = $request->user()->isAdmin() ? $data['user_id'] : $expense->user_id;
        $expense->update($data);

        return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    public function destroy(Request $request, Expense $expense): RedirectResponse
    {
        $this->ensureVisible($request, $expense);
        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil dihapus.');
    }

    private function ensureVisible(Request $request, Expense $expense): void
    {
        abort_unless($request->user()->isAdmin() || $expense->user_id === $request->user()->id, 404);
    }

    private function studentsFor(Request $request)
    {
        return $request->user()->isAdmin()
            ? User::where('role', 'mahasiswa')->orderBy('name')->get()
            : collect();
    }
}