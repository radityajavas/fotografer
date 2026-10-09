@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-6 col-lg-5">

    <h2 class="h4 mb-1">Daftar</h2>
    <p class="text-secondary small mb-3">Cukup isi tiga data di bawah untuk mulai memesan.</p>

    <form method="POST" action="{{ route('register') }}" novalidate>
      @csrf

      <div class="mb-3">
        <label for="name" class="form-label">Nama lengkap</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}"
               class="form-control @error('name') is-invalid @enderror"
               required minlength="3" maxlength="100" autocomplete="name" autofocus>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}"
               class="form-control @error('email') is-invalid @enderror"
               required maxlength="255" autocomplete="email">
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      @include('auth.partials.password', [
        'id' => 'password', 'name' => 'password', 'label' => 'Kata sandi',
        'minlength' => 8, 'autocomplete' => 'new-password',
      ])
      <div class="form-text mb-3" style="margin-top:-0.5rem">Minimal 8 karakter, kombinasi huruf dan angka.</div>

      <div class="mb-3">
        <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
        <input id="password_confirmation" type="password" name="password_confirmation" 
               class="form-control" required autocomplete="new-password" placeholder="Ulangi kata sandi">
      </div>

      <button type="submit" class="btn btn-brand w-100">Daftar</button>
    </form>

    <p class="mt-3 mb-0">
      Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
    </p>

  </div>
</div>
@endsection
