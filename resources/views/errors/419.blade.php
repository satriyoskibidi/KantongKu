@extends('layouts.guest')
@section('title', 'Sesi berakhir')
@section('content')
<main class="auth-wrap"><section class="surface text-center p-4 p-md-5 mx-auto" style="max-width: 520px"><span class="stat-icon mx-auto mb-3"><i class="bi bi-clock-history"></i></span><div class="eyebrow">419 · Sesi berakhir</div><h1 class="h3 fw-bold mt-2">Sesi kamu sudah kedaluwarsa.</h1><p class="text-secondary">Muat ulang halaman lalu kirim kembali formulir.</p><a class="btn btn-primary mt-2" href="{{ route('login') }}"><i class="bi bi-arrow-clockwise me-1"></i>Masuk kembali</a></section></main>
@endsection