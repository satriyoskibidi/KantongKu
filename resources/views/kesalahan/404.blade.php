@extends('tata-letak.tamu')
@section('title', 'Halaman tidak ditemukan')
@section('content')
<main class="auth-wrap"><section class="surface text-center p-4 p-md-5 mx-auto" style="max-width: 520px"><span class="stat-icon mx-auto mb-3"><i class="bi bi-search"></i></span><div class="eyebrow">404 · Tidak ditemukan</div><h1 class="h3 fw-bold mt-2">Halaman ini tidak ada.</h1><p class="text-secondary">Tautan mungkin sudah berubah atau data yang dicari tidak tersedia.</p><a class="btn btn-primary mt-2" href="{{ auth()->check() ? route('dashboard') : route('login') }}"><i class="bi bi-arrow-left me-1"></i>Kembali</a></section></main>
@endsection