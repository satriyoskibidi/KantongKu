@extends('layouts.guest')
@section('title', 'Akses ditolak')
@section('content')
<main class="auth-wrap"><section class="surface text-center p-4 p-md-5 mx-auto" style="max-width: 520px"><span class="stat-icon mx-auto mb-3"><i class="bi bi-shield-lock"></i></span><div class="eyebrow">403 · Akses dibatasi</div><h1 class="h3 fw-bold mt-2">Halaman ini bukan untuk akunmu.</h1><p class="text-secondary">Kamu tidak memiliki izin untuk membuka halaman tersebut.</p><a class="btn btn-primary mt-2" href="{{ auth()->check() ? route('dashboard') : route('login') }}"><i class="bi bi-arrow-left me-1"></i>Kembali</a></section></main>
@endsection