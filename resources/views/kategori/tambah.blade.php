@extends('tata-letak.aplikasi')
@section('title', 'Tambah kategori')
@section('content')
<div class="page-heading"><div><span class="eyebrow">Administrasi</span><h1 class="mt-1">Tambah kategori</h1></div><a class="btn btn-outline-secondary" href="{{ route('categories.index') }}"><i class="bi bi-arrow-left me-1"></i>Kembali</a></div>
<form method="POST" action="{{ route('categories.store') }}" class="surface p-3 p-lg-4">
    @csrf
    @include('kategori.isian', ['category' => null])
    <div class="d-flex justify-content-end gap-2 mt-4"><a class="btn btn-light" href="{{ route('categories.index') }}">Batal</a><button class="btn btn-primary" type="submit"><i class="bi bi-check2 me-1"></i>Simpan kategori</button></div>
</form>
@endsection
