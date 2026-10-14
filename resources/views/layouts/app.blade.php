<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'KantongKu') | KantongKu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @include('partials.styles')
    @stack('styles')
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark navbar-kk sticky-top">
    <div class="container page-shell">
        <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}"><i class="bi bi-wallet2 me-2"></i>KantongKu</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Buka navigasi">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto ms-lg-3">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="bi bi-grid-1x2 me-1"></i>Ringkasan</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('incomes.*') ? 'active' : '' }}" href="{{ route('incomes.index') }}"><i class="bi bi-arrow-down-left me-1"></i>Pemasukan</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}" href="{{ route('expenses.index') }}"><i class="bi bi-arrow-up-right me-1"></i>Pengeluaran</a></li>
                @if (auth()->user()->isAdmin())
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}"><i class="bi bi-people me-1"></i>Mahasiswa</a></li>
                @endif
            </ul>
            <div class="d-flex align-items-center gap-3 py-2 py-lg-0">
                <span class="small text-white"><i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }} <span class="badge bg-light text-dark ms-1">{{ auth()->user()->isAdmin() ? 'Admin' : 'Mahasiswa' }}</span></span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-sm btn-outline-light" type="submit" aria-label="Keluar"><i class="bi bi-box-arrow-right me-1"></i>Keluar</button>
                </form>
            </div>
        </div>
    </div>
</nav>
<main class="container page-shell py-4 py-lg-5">
    @include('partials.alerts')
    @yield('content')
</main>
<footer class="container page-shell footer-kk text-center py-4">KantongKu <span class="mx-1">·</span> Kelola uang saku dengan lebih bijak <span class="mx-1">·</span> {{ now()->year }}</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('submit', function (event) {
        const message = event.target.dataset.confirm;
        if (message && !window.confirm(message)) event.preventDefault();
    });
</script>
@stack('scripts')
</body>
</html>