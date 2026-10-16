<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MahasiswaController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate(['q' => ['nullable', 'string', 'max:100']]);
        $students = Pengguna::query()->where('role', 'mahasiswa')->withCount(['pemasukan', 'pengeluaran'])
            ->when($filters['q'] ?? null, fn(Builder $query, string $search) => $query->where(function (Builder $query) use ($search) {
                $query->where('name', 'like', "%$search%")->orWhere('email', 'like', "%$search%");
            }))
            ->orderBy('name')->paginate(10)->withQueryString();

        return view('mahasiswa.daftar', compact('students', 'filters'));
    }

    public function create(): View
    {
        return view('mahasiswa.tambah');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $data['role'] = 'mahasiswa';
        Pengguna::create($data);

        return redirect()->route('users.index')->with('success', 'Akun mahasiswa berhasil dibuat.');
    }

    public function edit(Pengguna $user): View
    {
        $this->ensureStudent($user);

        return view('mahasiswa.ubah', compact('user'));
    }

    public function update(Request $request, Pengguna $user): RedirectResponse
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

    public function destroy(Pengguna $user): RedirectResponse
    {
        $this->ensureStudent($user);

        if ($user->pemasukan()->exists() || $user->pengeluaran()->exists()) {
            return back()->with('error', 'Akun tidak dapat dihapus karena masih memiliki riwayat transaksi.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Akun mahasiswa berhasil dihapus.');
    }

    public function toggleStatus(Pengguna $user): RedirectResponse
    {
        $this->ensureStudent($user);
        $user->update(['is_active' => !$user->is_active]);

        return redirect()->route('users.index')->with(
            'success',
            $user->is_active ? 'Akun mahasiswa berhasil diaktifkan.' : 'Akun mahasiswa berhasil dinonaktifkan.'
        );
    }

    private function ensureStudent(Pengguna $user): void
    {
        abort_unless($user->role === 'mahasiswa', 404);
    }
}
