@extends('tata-letak.tamu')
@section('title', 'Masuk')
@section('content')
<main class="auth-wrap">
    <div class="auth-panel row g-0">
        <section class="col-md-5 auth-aside d-flex align-items-center">
            <div class="auth-aside-content">
                <a class="text-white text-decoration-none fw-bold fs-5" href="{{ route('login') }}"><i class="bi bi-wallet2 me-2"></i>KantongKu</a>
                <div class="mt-5 pt-3">
                    <span class="eyebrow text-white-50">Keuangan pribadi, lebih terarah</span>
                    <h1 class="mt-3">Uang saku punya tujuan.</h1>
                    <p class="text-white-50 mt-3 mb-0">Catat pemasukan dan pengeluaran, lalu lihat kebiasaan finansialmu tumbuh lebih sehat.</p>
                </div>
            </div>
        </section>
        <section class="col-md-7 auth-main">
            <div class="mb-4">
                <span class="eyebrow">Selamat datang kembali</span>
                <h2 class="mt-2">Masuk ke KantongKu</h2>
                <p class="text-secondary mb-0">Gunakan akun yang sudah diberikan oleh admin.</p>
            </div>
            @if (session('success'))
                <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">Email atau kata sandi tidak sesuai. Periksa kembali lalu coba lagi.</div>
            @endif
            <form method="POST" action="{{ route('login.store') }}" class="mt-4">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold" for="email">Email</label>
                    <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold" for="password">Kata sandi</label>
                    <input class="form-control @error('password') is-invalid @enderror" id="password" name="password" type="password" autocomplete="current-password" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-check mb-4">
                    <input class="form-check-input" id="remember" name="remember" type="checkbox" value="1">
                    <label class="form-check-label text-secondary" for="remember">Ingat saya di perangkat ini</label>
                </div>
                <button class="btn btn-primary w-100 py-2 fw-semibold" type="submit">Masuk <i class="bi bi-arrow-right ms-1"></i></button>
            </form>
            <p class="text-center small mt-4 mb-0">Belum punya akun? <a class="fw-semibold" href="{{ route('register') }}">Daftar sebagai mahasiswa</a></p>
        </section>
    </div>
</main>
@endsection