@extends('tata-letak.aplikasi')
@section('title', 'Dashboard admin')
@section('content')
<div class="page-heading">
    <div><span class="eyebrow">Administrasi KantongKu</span><h1 class="mt-1">Dashboard admin</h1><p class="text-secondary mb-0">Ringkasan aktivitas keuangan mahasiswa untuk {{ $period->translatedFormat('F Y') }}.</p></div>
    <div class="d-flex flex-wrap gap-2">
        <a class="btn btn-outline-primary" href="{{ route('admin.transactions.index') }}"><i class="bi bi-list-check me-1"></i>Monitoring transaksi</a>
        <a class="btn btn-primary" href="{{ route('users.index') }}"><i class="bi bi-people me-1"></i>Kelola mahasiswa</a>
    </div>
</div>

<form class="surface p-3 mb-4" method="GET" action="{{ route('dashboard') }}">
    <div class="row g-2 align-items-end">
        <div class="col-12 col-sm-6 col-lg-3"><label class="form-label small fw-semibold mb-1" for="month">Periode laporan</label><input class="form-control" type="month" id="month" name="month" value="{{ $month }}"></div>
        <div class="col-auto"><button class="btn btn-primary" type="submit"><i class="bi bi-funnel me-1"></i>Terapkan</button></div>
    </div>
</form>

<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3"><article class="surface stat-card h-100"><div class="d-flex justify-content-between align-items-start"><span class="stat-label">Total mahasiswa</span><span class="stat-icon"><i class="bi bi-people"></i></span></div><div class="stat-value mt-3">{{ number_format($studentCount, 0, ',', '.') }}</div><div class="small text-secondary mt-1">Akun terdaftar</div></article></div>
    <div class="col-6 col-xl-3"><article class="surface stat-card balance h-100"><div class="d-flex justify-content-between align-items-start"><span class="stat-label">Total transaksi</span><span class="stat-icon"><i class="bi bi-journal-text"></i></span></div><div class="stat-value mt-3">{{ number_format($transactionCount, 0, ',', '.') }}</div><div class="small text-secondary mt-1">Pada bulan terpilih</div></article></div>
    <div class="col-6 col-xl-3"><article class="surface stat-card h-100"><div class="d-flex justify-content-between align-items-start"><span class="stat-label">Total pemasukan</span><span class="stat-icon"><i class="bi bi-arrow-down-left"></i></span></div><div class="stat-value mt-3">Rp {{ number_format($incomeTotal, 0, ',', '.') }}</div><div class="small text-secondary mt-1">Semua mahasiswa</div></article></div>
    <div class="col-6 col-xl-3"><article class="surface stat-card expense h-100"><div class="d-flex justify-content-between align-items-start"><span class="stat-label">Total pengeluaran</span><span class="stat-icon"><i class="bi bi-arrow-up-right"></i></span></div><div class="stat-value mt-3">Rp {{ number_format($expenseTotal, 0, ',', '.') }}</div><div class="small text-secondary mt-1">Semua mahasiswa</div></article></div>
</div>

<section class="surface p-3 p-lg-4">
    <div class="d-flex justify-content-between align-items-start mb-3"><div><h2 class="panel-title mb-1">Arus transaksi harian</h2><p class="small text-secondary mb-0">Gabungan semua akun mahasiswa</p></div><span class="badge badge-soft">{{ $period->translatedFormat('F') }}</span></div>
    <div style="height: 320px; position: relative"><canvas id="adminCashflowChart" role="img" aria-label="Grafik pemasukan dan pengeluaran harian semua mahasiswa"></canvas></div>
</section>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
    new Chart(document.getElementById('adminCashflowChart'), {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [
                { label: 'Pemasukan', data: @json($chartIncome), backgroundColor: '#167a5a', borderRadius: 4 },
                { label: 'Pengeluaran', data: @json($chartExpense), backgroundColor: '#de8150', borderRadius: 4 },
            ],
        },
        options: { maintainAspectRatio: false, responsive: true, scales: { y: { beginAtZero: true, ticks: { callback: value => 'Rp ' + new Intl.NumberFormat('id-ID').format(value) } } } },
    });
</script>
@endpush
