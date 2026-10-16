@extends('tata-letak.aplikasi')
@section('title', 'Catat pengeluaran')
@section('content')
<div class="page-heading"><div><span class="eyebrow">Arus keluar</span><h1 class="mt-1">Catat pengeluaran</h1></div><a class="btn btn-outline-secondary" href="{{ route('expenses.index') }}"><i class="bi bi-arrow-left me-1"></i>Kembali</a></div>
<form method="POST" action="{{ route('expenses.store') }}" class="surface p-3 p-lg-4">
    @csrf
    @include('pengeluaran.isian', ['expense' => null])
    <div class="d-flex justify-content-end gap-2 mt-4"><a class="btn btn-light" href="{{ route('expenses.index') }}">Batal</a><button class="btn btn-primary" type="submit"><i class="bi bi-check2 me-1"></i>Simpan pengeluaran</button></div>
</form>
@endsection