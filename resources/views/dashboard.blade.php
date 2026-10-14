@extends('layouts.app')
@section('title', 'Ringkasan')
@section('content')
<div class="page-heading">
    <div>
        <span class="eyebrow">Ruang kendali keuangan</span>
        <h1 class="mt-1">Halo, {{ auth()->user()->name }}</h1>
        <p class="text-secondary mb-0">Pantau arus uangmu untuk bulan {{ $period->translatedFormat('F Y') }}.</p>
    </div>
    <div class="d-flex gap-2">
        <a class="btn btn-outline-primary" href="{{ route('incomes.create') }}"><i class="bi bi-plus-lg me-1"></i>Pemasukan</a>
        <a class="btn btn-primary" href="{{ route('expenses.create') }}"><i class="bi bi-plus-lg me-1"></i>Pengeluaran</a>
    </div>
</div>

<form class="surface p-3 mb-4" method="GET" action="{{ route('dashboard') }}">
    <div class="row g-2 align-items-end">
        <div class="col-12 col-sm-5 col-lg-3">
            <label class="form-label small fw-semibold mb-1" for="month">Periode</label>
            <input class="form-control" type="month" id="month" name="month" value="{{ $month }}">
        </div>
        @if (auth()->user()->isAdmin())
            <div class="col-12 col-sm-5 col-lg-4">
                <label class="form-label small fw-semibold mb-1" for="user_id">Pemilik transaksi</label>
                <select class="form-select" id="user_id" name="user_id">
                    <option value="">Semua mahasiswa</option>
                    @foreach ($students as $student)
                        <option value="{{ $student->id }}" @selected((string) $selectedUserId === (string) $student->id)>{{ $student->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif
        <div class="col-auto"><button class="btn btn-primary" type="submit"><i class="bi bi-funnel me-1"></i>Terapkan</button></div>
    </div>
</form>

<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3"><article class="surface stat-card h-100"><div class="d-flex justify-content-between align-items-start"><span class="stat-label">Total pemasukan</span><span class="stat-icon"><i class="bi bi-arrow-down-left"></i></span></div><div class="stat-value mt-3">Rp {{ number_format($incomeTotal, 0, ',', '.') }}</div><div class="small text-secondary mt-1">Bulan terpilih</div></article></div>
    <div class="col-6 col-xl-3"><article class="surface stat-card expense h-100"><div class="d-flex justify-content-between align-items-start"><span class="stat-label">Total pengeluaran</span><span class="stat-icon"><i class="bi bi-arrow-up-right"></i></span></div><div class="stat-value mt-3">Rp {{ number_format($expenseTotal, 0, ',', '.') }}</div><div class="small text-secondary mt-1">Bulan terpilih</div></article></div>
    <div class="col-6 col-xl-3"><article class="surface stat-card balance h-100"><div class="d-flex justify-content-between align-items-start"><span class="stat-label">Sisa bersih</span><span class="stat-icon"><i class="bi bi-wallet2"></i></span></div><div class="stat-value mt-3">Rp {{ number_format($balance, 0, ',', '.') }}</div><div class="small {{ $balanceChange >= 0 ? 'text-success' : 'text-danger' }} mt-1"><i class="bi {{ $balanceChange >= 0 ? 'bi-arrow-up-right' : 'bi-arrow-down-right' }}"></i> Rp {{ number_format(abs($balanceChange), 0, ',', '.') }} vs bulan lalu</div></article></div>
    <div class="col-6 col-xl-3"><article class="surface stat-card rate h-100"><div class="d-flex justify-content-between align-items-start"><span class="stat-label">Rasio tabungan</span><span class="stat-icon"><i class="bi bi-piggy-bank"></i></span></div><div class="stat-value mt-3">{{ $savingsRate }}%</div><div class="small text-secondary mt-1">Dari total pemasukan</div></article></div>
</div>

<section class="insight-band p-3 p-lg-4 mb-4 d-flex gap-3 align-items-center">
    <span class="insight-symbol"><i class="bi bi-lightbulb fs-5"></i></span>
    <div><div class="small text-white-50 fw-semibold">INSIGHT BULAN INI</div><p class="mb-0 mt-1">{{ $insight }}</p></div>
</section>

<div class="row g-3">
    <div class="col-12 col-xl-7">
        <section class="surface p-3 p-lg-4 h-100">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div><h2 class="panel-title mb-1">Arus uang harian</h2><p class="small text-secondary mb-0">Perbandingan pemasukan dan pengeluaran per hari</p></div>
                <span class="badge badge-soft">{{ $period->translatedFormat('F') }}</span>
            </div>
            <div style="height: 280px; position: relative"><canvas id="cashflowChart" role="img" aria-label="Grafik arus uang harian"></canvas></div>
        </section>
    </div>
    <div class="col-12 col-xl-5">
        <section class="surface h-100">
            <div class="d-flex justify-content-between align-items-center p-3 p-lg-4 pb-2">
                <div><h2 class="panel-title mb-1">Transaksi terbaru</h2><p class="small text-secondary mb-0">Aktivitas bulan ini</p></div>
                <a class="small text-decoration-none" href="{{ route('incomes.index') }}">Lihat semua <i class="bi bi-arrow-right"></i></a>
            </div>
            @forelse ($recentTransactions as $transaction)
                <div class="d-flex align-items-center gap-3 px-3 px-lg-4 py-3 border-top">
                    <span class="stat-icon {{ $transaction['type'] === 'expense' ? 'text-danger bg-danger-subtle' : '' }}"><i class="bi {{ $transaction['type'] === 'income' ? 'bi-arrow-down-left' : 'bi-arrow-up-right' }}"></i></span>
                    <div class="flex-grow-1 min-w-0"><div class="fw-semibold text-truncate">{{ $transaction['title'] }}</div><div class="small text-secondary">{{ $transaction['date']->translatedFormat('d M') }} @if (auth()->user()->isAdmin() && ! $selectedUserId) · {{ $transaction['user'] }} @endif</div></div>
                    <div class="small fw-bold text-nowrap {{ $transaction['type'] === 'income' ? 'text-success' : 'text-danger' }}">{{ $transaction['type'] === 'income' ? '+' : '−' }}Rp {{ number_format($transaction['amount'], 0, ',', '.') }}</div>
                </div>
            @empty
                <div class="empty-state"><i class="bi bi-journal-plus"></i><p class="mt-2 mb-0">Belum ada transaksi pada periode ini.</p></div>
            @endforelse
        </section>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
    const chartElement = document.getElementById('cashflowChart');
    if (typeof Chart !== 'undefined') new Chart(chartElement, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [
                { label: 'Pemasukan', data: @json($chartIncome), borderColor: '#167a5a', backgroundColor: 'rgba(22,122,90,.1)', fill: true, tension: .32, pointRadius: 2, borderWidth: 2 },
                { label: 'Pengeluaran', data: @json($chartExpense), borderColor: '#de8150', backgroundColor: 'rgba(222,129,80,.06)', fill: true, tension: .32, pointRadius: 2, borderWidth: 2 }
            ]
        },
        options: {
            maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8, padding: 18 } },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return context.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                        }
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { maxTicksLimit: 10 } },
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function (value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID', { notation: 'compact' }).format(value);
                        }
                    }
                }
            }
        }
    });
</script>
@endpush