@extends('tata-letak.tamu')
@section('title', 'Daftar mahasiswa')
@section('content')
<main class="auth-wrap">
    <div class="auth-panel row g-0">
        <section class="col-md-5 auth-aside d-flex align-items-center">
            <div class="auth-aside-content">
                <a class="text-white text-decoration-none fw-bold fs-5" href="{{ route('login') }}"><i class="bi bi-wallet2 me-2"></i>KantongKu</a>
                <div class="mt-5 pt-3">
                    <span class="eyebrow text-white-50">Mulai langkah kecil</span>
                    <h1 class="mt-3">Kenali kebiasaan uangmu.</h1>
                    <p class="text-white-50 mt-3 mb-0">Buat akun mahasiswa untuk mulai mencatat pemasukan, pengeluaran, dan target menabungmu.</p>
                </div>
            </div>
        </section>
        <section class="col-md-7 auth-main">
            <div class="mb-4">
                <span class="eyebrow">Pendaftaran mahasiswa</span>
                <h2 class="mt-2">Buat akun baru</h2>
                <p class="text-secondary mb-0">Isi data berikut untuk mulai mengelola keuangan.</p>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <strong>Periksa kembali data pendaftaran:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('register.store') }}" class="mt-4">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold" for="name">Nama lengkap</label>
                    <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" maxlength="255" required autofocus>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold" for="email">Email</label>
                    <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" maxlength="255" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-12 col-sm-6">
                        <label class="form-label fw-semibold" for="password">Kata sandi</label>
                        <input class="form-control @error('password') is-invalid @enderror" id="password" name="password" type="password" autocomplete="new-password" minlength="8" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label fw-semibold" for="password_confirmation">Ulangi kata sandi</label>
                        <input class="form-control" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" minlength="8" required>
                    </div>
                </div>
                <button class="btn btn-primary w-100 py-2 fw-semibold" type="submit">Buat akun <i class="bi bi-arrow-right ms-1"></i></button>
            </form>
            <p class="text-center small mt-4 mb-0">Sudah punya akun? <a class="fw-semibold" href="{{ route('login') }}">Masuk</a></p>
        </section>
    </div>
</main>
@endsection