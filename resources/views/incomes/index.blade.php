@extends('layouts.app')
@section('title', 'Pemasukan')
@section('content')
<div class="page-heading">
    <div><span class="eyebrow">Arus masuk</span><h1 class="mt-1">Pemasukan</h1><p class="text-secondary mb-0">Semua uang yang kamu terima, tersusun rapi.</p></div>
    <a class="btn btn-primary" href="{{ route('incomes.create') }}"><i class="bi bi-plus-lg me-1"></i>Catat pemasukan</a>
</div>
<section class="surface p-3 mb-3">
    <form method="GET" action="{{ route('incomes.index') }}" class="row g-2 align-items-end">
        <div class="col-12 col-md-4"><label class="form-label small fw-semibold mb-1" for="q">Cari transaksi</label><input class="form-control" id="q" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Nama transaksi"></div>
        <div class="col-6 col-md-2"><label class="form-label small fw-semibold mb-1" for="from">Dari tanggal</label><input class="form-control" type="date" id="from" name="from" value="{{ $filters['from'] ?? '' }}"></div>
        <div class="col-6 col-md-2"><label class="form-label small fw-semibold mb-1" for="to">Sampai</label><input class="form-control" type="date" id="to" name="to" value="{{ $filters['to'] ?? '' }}"></div>
        @if (auth()->user()->isAdmin())
            <div class="col-12 col-md-2"><label class="form-label small fw-semibold mb-1" for="user_id">Mahasiswa</label><select class="form-select" id="user_id" name="user_id"><option value="">Semua</option>@foreach ($students as $student)<option value="{{ $student->id }}" @selected(($filters['user_id'] ?? '') == $student->id)>{{ $student->name }}</option>@endforeach</select></div>
        @endif
        <div class="col-auto"><button class="btn btn-outline-primary" type="submit"><i class="bi bi-funnel me-1"></i>Filter</button></div>
        @if (count(array_filter($filters)))<div class="col-auto"><a class="btn btn-link text-secondary text-decoration-none" href="{{ route('incomes.index') }}">Reset</a></div>@endif
    </form>
</section>
<section class="surface overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Transaksi</th><th>Kategori</th>@if (auth()->user()->isAdmin())<th>Pemilik</th>@endif<th>Tanggal</th><th class="text-end">Jumlah</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
            @forelse ($incomes as $income)
                <tr>
                    <td><div class="fw-semibold">{{ $income->title }}</div>@if ($income->description)<div class="small text-secondary text-truncate" style="max-width: 260px">{{ $income->description }}</div>@endif</td>
                    <td><span class="badge badge-soft">{{ $income->category }}</span></td>
                    @if (auth()->user()->isAdmin())<td>{{ $income->user->name }}</td>@endif
                    <td class="text-nowrap">{{ $income->transaction_date->translatedFormat('d M Y') }}</td>
                    <td class="text-end fw-semibold text-success text-nowrap">Rp {{ number_format((float) $income->amount, 0, ',', '.') }}</td>
                    <td><div class="d-flex justify-content-end gap-1"><a class="btn btn-sm btn-outline-secondary" href="{{ route('incomes.edit', $income) }}" title="Ubah pemasukan" aria-label="Ubah pemasukan"><i class="bi bi-pencil"></i></a><form method="POST" action="{{ route('incomes.destroy', $income) }}" data-confirm="Hapus pemasukan ini?">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" type="submit" title="Hapus pemasukan" aria-label="Hapus pemasukan"><i class="bi bi-trash"></i></button></form></div></td>
                </tr>
            @empty
                <tr><td colspan="{{ auth()->user()->isAdmin() ? 6 : 5 }}"><div class="empty-state"><i class="bi bi-arrow-down-left-circle"></i><p class="mt-2 mb-0">Belum ada pemasukan yang cocok.</p></div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if ($incomes->hasPages())<div class="p-3 border-top">{{ $incomes->links() }}</div>@endif
</section>
@endsection