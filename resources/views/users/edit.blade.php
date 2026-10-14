@extends('layouts.app')
@section('title', 'Ubah akun mahasiswa')
@section('content')
<div class="page-heading"><div><span class="eyebrow">Administrasi akun</span><h1 class="mt-1">Ubah akun mahasiswa</h1></div><a class="btn btn-outline-secondary" href="{{ route('users.index') }}"><i class="bi bi-arrow-left me-1"></i>Kembali</a></div>
<form method="POST" action="{{ route('users.update', $user) }}" class="surface p-3 p-lg-4">
    @csrf
    @method('PUT')
    @include('users.fields', ['user' => $user, 'creating' => false])
    <div class="d-flex justify-content-end gap-2 mt-4"><a class="btn btn-light" href="{{ route('users.index') }}">Batal</a><button class="btn btn-primary" type="submit"><i class="bi bi-check2 me-1"></i>Simpan perubahan</button></div>
</form>
@endsection