@extends('tata-letak.aplikasi')
@section('title', 'Profil admin')
@section('content')
<div class="page-heading"><div><span class="eyebrow">Pengaturan akun</span><h1 class="mt-1">Profil admin</h1><p class="text-secondary mb-0">Perbarui nama atau kata sandi akun admin.</p></div></div>
<form method="POST" action="{{ route('admin.profile.update') }}" class="surface p-3 p-lg-4">
    @csrf
    @method('PUT')
    <div class="row g-3">
        <div class="col-12 col-md-6"><label class="form-label fw-semibold" for="name">Nama admin</label><input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" maxlength="255" required>@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        <div class="col-12 col-md-6"><label class="form-label fw-semibold" for="email">Email</label><input class="form-control" id="email" value="{{ $user->email }}" readonly></div>
        <div class="col-12"><hr class="my-1"><h2 class="panel-title mb-0">Ganti kata sandi</h2></div>
        <div class="col-12 col-md-4"><label class="form-label fw-semibold" for="current_password">Kata sandi saat ini</label><input class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" type="password" autocomplete="current-password">@error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        <div class="col-12 col-md-4"><label class="form-label fw-semibold" for="password">Kata sandi baru</label><input class="form-control @error('password') is-invalid @enderror" id="password" name="password" type="password" autocomplete="new-password" minlength="8">@error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        <div class="col-12 col-md-4"><label class="form-label fw-semibold" for="password_confirmation">Konfirmasi kata sandi baru</label><input class="form-control" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" minlength="8"></div>
    </div>
    <div class="d-flex justify-content-end mt-4"><button class="btn btn-primary" type="submit"><i class="bi bi-check2 me-1"></i>Simpan profil</button></div>
</form>
@endsection
