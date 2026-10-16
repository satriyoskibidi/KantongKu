@extends('tata-letak.aplikasi')
@section('title', 'Tambah mahasiswa')
@section('content')
<div class="page-heading"><div><span class="eyebrow">Administrasi akun</span><h1 class="mt-1">Tambah mahasiswa</h1></div><a class="btn btn-outline-secondary" href="{{ route('users.index') }}"><i class="bi bi-arrow-left me-1"></i>Kembali</a></div>
<form method="POST" action="{{ route('users.store') }}" class="surface p-3 p-lg-4">
    @csrf
    @include('mahasiswa.isian', ['user' => null, 'creating' => true])
    <div class="d-flex justify-content-end gap-2 mt-4"><a class="btn btn-light" href="{{ route('users.index') }}">Batal</a><button class="btn btn-primary" type="submit"><i class="bi bi-person-plus me-1"></i>Buat akun</button></div>
</form>
@endsection