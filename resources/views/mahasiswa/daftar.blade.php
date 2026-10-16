@extends('tata-letak.aplikasi')
@section('title', 'Kelola mahasiswa')
@section('content')
<div class="page-heading">
    <div><span class="eyebrow">Administrasi</span><h1 class="mt-1">Akun mahasiswa</h1><p class="text-secondary mb-0">Kelola akses dan lihat aktivitas pencatatan mahasiswa.</p></div>
    <a class="btn btn-primary" href="{{ route('users.create') }}"><i class="bi bi-person-plus me-1"></i>Tambah mahasiswa</a>
</div>
<section class="surface p-3 mb-3">
    <form method="GET" action="{{ route('users.index') }}" class="row g-2 align-items-end">
        <div class="col-12 col-md-6"><label class="form-label small fw-semibold mb-1" for="q">Cari mahasiswa</label><input class="form-control" id="q" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Nama atau email"></div>
        <div class="col-auto"><button class="btn btn-outline-primary" type="submit"><i class="bi bi-search me-1"></i>Cari</button></div>
        @if (! empty($filters['q']))<div class="col-auto"><a class="btn btn-link text-secondary text-decoration-none" href="{{ route('users.index') }}">Reset</a></div>@endif
    </form>
</section>
<section class="surface overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Mahasiswa</th><th>Status</th><th>Transaksi tercatat</th><th>Bergabung</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
            @forelse ($students as $student)
                <tr>
                    <td><div class="fw-semibold">{{ $student->name }}</div><div class="small text-secondary">{{ $student->email }}</div></td>
                    <td><span class="badge {{ $student->is_active ? 'badge-soft' : 'bg-secondary-subtle text-secondary-emphasis' }}">{{ $student->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                    <td><span class="badge badge-soft me-1">{{ $student->pemasukan_count }} masuk</span><span class="badge bg-danger-subtle text-danger-emphasis">{{ $student->pengeluaran_count }} keluar</span></td>
                    <td>{{ $student->created_at->translatedFormat('d M Y') }}</td>
                    <td><div class="d-flex justify-content-end gap-1"><form method="POST" action="{{ route('users.status', $student) }}" data-confirm="{{ $student->is_active ? 'Nonaktifkan' : 'Aktifkan' }} akun mahasiswa ini?">@csrf @method('PATCH')<button class="btn btn-sm btn-outline-{{ $student->is_active ? 'warning' : 'success' }}" type="submit" title="{{ $student->is_active ? 'Nonaktifkan akun' : 'Aktifkan akun' }}" aria-label="{{ $student->is_active ? 'Nonaktifkan akun' : 'Aktifkan akun' }}"><i class="bi bi-power"></i></button></form><a class="btn btn-sm btn-outline-secondary" href="{{ route('users.edit', $student) }}" title="Ubah data atau reset kata sandi" aria-label="Ubah data atau reset kata sandi"><i class="bi bi-pencil"></i></a><form method="POST" action="{{ route('users.destroy', $student) }}" data-confirm="Hapus akun mahasiswa ini? Akun dengan transaksi tidak dapat dihapus.">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" type="submit" title="Hapus akun" aria-label="Hapus akun"><i class="bi bi-trash"></i></button></form></div></td>
                </tr>
            @empty
                <tr><td colspan="5"><div class="empty-state"><i class="bi bi-people"></i><p class="mt-2 mb-0">Tidak ada akun mahasiswa yang cocok.</p></div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if ($students->hasPages())<div class="p-3 border-top">{{ $students->links() }}</div>@endif
</section>
@endsection
