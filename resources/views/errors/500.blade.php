@extends('layouts.guest')
@section('title', 'Gangguan sementara')
@section('content')
<main class="auth-wrap"><section class="surface text-center p-4 p-md-5 mx-auto" style="max-width: 520px"><span class="stat-icon mx-auto mb-3"><i class="bi bi-tools"></i></span><div class="eyebrow">500 · Gangguan sementara</div><h1 class="h3 fw-bold mt-2">Ada yang tidak berjalan semestinya.</h1><p class="text-secondary">Data kamu tetap aman. Coba muat ulang halaman beberapa saat lagi.</p><a class="btn btn-primary mt-2" href="{{ auth()->check() ? route('dashboard') : route('login') }}"><i class="bi bi-arrow-clockwise me-1"></i>Coba lagi</a></section></main>
@endsection