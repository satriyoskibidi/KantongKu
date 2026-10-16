<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class KategoriController extends Controller
{
    public function index(): View
    {
        $categories = Kategori::orderBy('type')->orderBy('name')->paginate(15);

        return view('kategori.daftar', compact('categories'));
    }

    public function create(): View
    {
        return view('kategori.tambah', ['category' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        Kategori::create($this->validatedData($request));

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Kategori $category): View
    {
        return view('kategori.ubah', compact('category'));
    }

    public function update(Request $request, Kategori $category): RedirectResponse
    {
        $category->update($this->validatedData($request, $category));

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus. Riwayat transaksi tetap tersimpan.');
    }

    private function validatedData(Request $request, ?Kategori $category = null): array
    {
        return $request->validate([
            'name' => [
                'required',
                'string',
                'max:60',
                Rule::unique('categories', 'name')->where(fn($query) => $query->where('type', $request->input('type')))->ignore($category?->id),
            ],
            'type' => ['required', Rule::in(['income', 'expense'])],
        ]);
    }
}
