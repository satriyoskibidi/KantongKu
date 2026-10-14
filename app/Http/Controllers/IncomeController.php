<?php

namespace App\Http\Controllers;

use App\Http\Requests\IncomeRequest;
use App\Models\Income;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IncomeController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);
        $query = Income::query()->with('user');

        if (!$request->user()->isAdmin()) {
            $query->where('user_id', $request->user()->id);
        } elseif (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        $incomes = $query
            ->when($filters['q'] ?? null, fn(Builder $query, string $search) => $query->where('title', 'like', "%$search%"))
            ->when($filters['from'] ?? null, fn(Builder $query, string $date) => $query->whereDate('transaction_date', '>=', $date))
            ->when($filters['to'] ?? null, fn(Builder $query, string $date) => $query->whereDate('transaction_date', '<=', $date))
            ->orderByDesc('transaction_date')->orderByDesc('id')->paginate(10)->withQueryString();

        return view('incomes.index', [
            'incomes' => $incomes,
            'filters' => $filters,
            'students' => $request->user()->isAdmin()
                ? User::where('role', 'mahasiswa')->orderBy('name')->get()
                : collect(),
        ]);
    }

    public function create(Request $request): View
    {
        return view('incomes.create', ['students' => $this->studentsFor($request)]);
    }

    public function store(IncomeRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->isAdmin() ? $data['user_id'] : $request->user()->id;
        Income::create($data);

        return redirect()->route('incomes.index')->with('success', 'Pemasukan berhasil disimpan.');
    }

    public function edit(Request $request, Income $income): View
    {
        $this->ensureVisible($request, $income);

        return view('incomes.edit', [
            'income' => $income,
            'students' => $this->studentsFor($request),
        ]);
    }

    public function update(IncomeRequest $request, Income $income): RedirectResponse
    {
        $this->ensureVisible($request, $income);
        $data = $request->validated();
        $data['user_id'] = $request->user()->isAdmin() ? $data['user_id'] : $income->user_id;
        $income->update($data);

        return redirect()->route('incomes.index')->with('success', 'Pemasukan berhasil diperbarui.');
    }

    public function destroy(Request $request, Income $income): RedirectResponse
    {
        $this->ensureVisible($request, $income);
        $income->delete();

        return redirect()->route('incomes.index')->with('success', 'Pemasukan berhasil dihapus.');
    }

    private function ensureVisible(Request $request, Income $income): void
    {
        abort_unless($request->user()->isAdmin() || $income->user_id === $request->user()->id, 404);
    }

    private function studentsFor(Request $request)
    {
        return $request->user()->isAdmin()
            ? User::where('role', 'mahasiswa')->orderBy('name')->get()
            : collect();
    }
}