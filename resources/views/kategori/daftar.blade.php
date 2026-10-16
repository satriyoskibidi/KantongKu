@extends('tata-letak.aplikasi')
@section('title', 'Kelola kategori')
@section('content')
<div class="page-heading">
    <div><span class="eyebrow">Administrasi</span><h1 class="mt-1">Kategori transaksi</h1><p class="text-secondary mb-0">Kelola pilihan kategori pemasukan dan pengeluaran mahasiswa.</p></div>
    <a class="btn btn-primary" href="{{ route('categories.create') }}"><i class="bi bi-plus-lg me-1"></i>Tambah kategori</a>
</div>
<section class="surface overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Nama kategori</th><th>Jenis transaksi</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
            @forelse ($categories as $category)
                <tr>
                    <td class="fw-semibold">{{ $category->name }}</td>
                    <td><span class="badge {{ $category->type === 'income' ? 'badge-soft' : 'bg-warning-subtle text-warning-emphasis' }}">{{ $category->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}</span></td>
                    <td><div class="d-flex justify-content-end gap-1"><a class="btn btn-sm btn-outline-secondary" href="{{ route('categories.edit', $category) }}" title="Ubah kategori" aria-label="Ubah kategori"><i class="bi bi-pencil"></i></a><form method="POST" action="{{ route('categories.destroy', $category) }}" data-confirm="Hapus kategori ini? Riwayat transaksi yang sudah memakai nama ini tidak akan terhapus.">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" type="submit" title="Hapus kategori" aria-label="Hapus kategori"><i class="bi bi-trash"></i></button></form></div></td>
                </tr>
            @empty
                <tr><td colspan="3"><div class="empty-state"><i class="bi bi-tags"></i><p class="mt-2 mb-0">Belum ada kategori.</p></div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if ($categories->hasPages())<div class="p-3 border-top">{{ $categories->links() }}</div>@endif
</section>
@endsection
