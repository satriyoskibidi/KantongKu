<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate(['q' => ['nullable', 'string', 'max:100']]);
        $students = User::query()->where('role', 'mahasiswa')->withCount(['incomes', 'expenses'])
            ->when($filters['q'] ?? null, fn(Builder $query, string $search) => $query->where(function (Builder $query) use ($search) {
                $query->where('name', 'like', "%$search%")->orWhere('email', 'like', "%$search%");
            }))
            ->orderBy('name')->paginate(10)->withQueryString();

        return view('users.index', compact('students', 'filters'));
    }

    public function create(): View
    {
        return view('users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $data['role'] = 'mahasiswa';
        User::create($data);

        return redirect()->route('users.index')->with('success', 'Akun mahasiswa berhasil dibuat.');
    }

    public function edit(User $user): View
    {
        $this->ensureStudent($user);

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->ensureStudent($user);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Akun mahasiswa berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->ensureStudent($user);

        if ($user->incomes()->exists() || $user->expenses()->exists()) {
            return back()->with('error', 'Akun tidak dapat dihapus karena masih memiliki riwayat transaksi.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Akun mahasiswa berhasil dihapus.');
    }

    private function ensureStudent(User $user): void
    {
        abort_unless($user->role === 'mahasiswa', 404);
    }
}