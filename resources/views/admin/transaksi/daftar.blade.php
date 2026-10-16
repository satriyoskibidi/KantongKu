@extends('tata-letak.aplikasi')
@section('title', 'Monitoring transaksi')
@section('content')
<div class="page-heading"><div><span class="eyebrow">Pengawasan</span><h1 class="mt-1">Monitoring transaksi</h1><p class="text-secondary mb-0">Seluruh catatan mahasiswa, hanya-baca.</p></div><a class="btn btn-outline-secondary" href="{{ route('dashboard') }}"><i class="bi bi-grid-1x2 me-1"></i>Dashboard</a></div>
<form class="surface p-3 mb-3" method="GET" action="{{ route('admin.transactions.index') }}">
    <div class="row g-2 align-items-end">
        <div class="col-12 col-sm-6 col-lg-3"><label class="form-label small fw-semibold mb-1" for="user_id">Mahasiswa</label><select class="form-select" id="user_id" name="user_id"><option value="">Semua mahasiswa</option>@foreach ($students as $student)<option value="{{ $student->id }}" @selected((string) ($filters['user_id'] ?? '') === (string) $student->id)>{{ $student->name }}</option>@endforeach</select></div>
        <div class="col-6 col-sm-3 col-lg-2"><label class="form-label small fw-semibold mb-1" for="from">Dari tanggal</label><input class="form-control" type="date" id="from" name="from" value="{{ $filters['from'] ?? '' }}"></div>
        <div class="col-6 col-sm-3 col-lg-2"><label class="form-label small fw-semibold mb-1" for="to">Sampai tanggal</label><input class="form-control" type="date" id="to" name="to" value="{{ $filters['to'] ?? '' }}"></div>
        <div class="col-6 col-sm-4 col-lg-2"><label class="form-label small fw-semibold mb-1" for="category">Kategori</label><select class="form-select" id="category" name="category"><option value="">Semua kategori</option>@foreach ($categories as $category)<option value="{{ $category }}" @selected(($filters['category'] ?? '') === $category)>{{ $category }}</option>@endforeach</select></div>
        <div class="col-6 col-sm-4 col-lg-2"><label class="form-label small fw-semibold mb-1" for="type">Jenis</label><select class="form-select" id="type" name="type"><option value="">Semua jenis</option><option value="income" @selected(($filters['type'] ?? '') === 'income')>Pemasukan</option><option value="expense" @selected(($filters['type'] ?? '') === 'expense')>Pengeluaran</option></select></div>
        <div class="col-auto"><button class="btn btn-primary" type="submit"><i class="bi bi-funnel me-1"></i>Filter</button></div>
        <div class="col-auto"><a class="btn btn-light" href="{{ route('admin.transactions.index') }}">Reset</a></div>
    </div>
</form>
<section class="surface overflow-hidden">
    <div class="table-responsive"><table class="table align-middle">
        <thead><tr><th>Tanggal</th><th>Mahasiswa</th><th>Transaksi</th><th>Kategori</th><th>Jenis</th><th class="text-end">Jumlah</th></tr></thead>
        <tbody>
        @forelse ($transactions as $transaction)
            <tr><td class="text-nowrap">{{ \Illuminate\Support\Carbon::parse($transaction->transaction_date)->translatedFormat('d M Y') }}</td><td>{{ $transaction->user_name }}</td><td class="fw-semibold">{{ $transaction->title }}</td><td>{{ $transaction->category }}</td><td><span class="badge {{ $transaction->transaction_type === 'Pemasukan' ? 'badge-soft' : 'bg-danger-subtle text-danger-emphasis' }}">{{ $transaction->transaction_type }}</span></td><td class="text-end fw-semibold text-nowrap">Rp {{ number_format((float) $transaction->amount, 0, ',', '.') }}</td></tr>
        @empty
            <tr><td colspan="6"><div class="empty-state"><i class="bi bi-search"></i><p class="mt-2 mb-0">Tidak ada transaksi untuk filter ini.</p></div></td></tr>
        @endforelse
        </tbody>
    </table></div>
    @if ($transactions->hasPages())<div class="p-3 border-top">{{ $transactions->links() }}</div>@endif
</section>
@endsection
