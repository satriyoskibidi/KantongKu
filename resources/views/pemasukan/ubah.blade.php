@extends('tata-letak.aplikasi')
@section('title', 'Ubah pemasukan')
@section('content')
<div class="page-heading"><div><span class="eyebrow">Arus masuk</span><h1 class="mt-1">Ubah pemasukan</h1></div><a class="btn btn-outline-secondary" href="{{ route('incomes.index') }}"><i class="bi bi-arrow-left me-1"></i>Kembali</a></div>
<form method="POST" action="{{ route('incomes.update', $income) }}" class="surface p-3 p-lg-4">
    @csrf
    @method('PUT')
    @include('pemasukan.isian', ['income' => $income])
    <div class="d-flex justify-content-end gap-2 mt-4"><a class="btn btn-light" href="{{ route('incomes.index') }}">Batal</a><button class="btn btn-primary" type="submit"><i class="bi bi-check2 me-1"></i>Simpan perubahan</button></div>
</form>
@endsection