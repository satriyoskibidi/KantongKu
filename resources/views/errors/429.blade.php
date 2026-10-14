@extends('layouts.guest')
@section('title', 'Terlalu banyak permintaan')
@section('content')
<main class="auth-wrap"><section class="surface text-center p-4 p-md-5 mx-auto" style="max-width: 520px"><span class="stat-icon mx-auto mb-3"><i class="bi bi-hourglass-split"></i></span><div class="eyebrow">429 · Coba lagi sebentar</div><h1 class="h3 fw-bold mt-2">Terlalu banyak permintaan.</h1><p class="text-secondary">Tunggu sebentar sebelum mencoba kembali.</p><a class="btn btn-primary mt-2" href="{{ auth()->check() ? route('dashboard') : route('login') }}"><i class="bi bi-arrow-left me-1"></i>Kembali</a></section></main>
@endsection