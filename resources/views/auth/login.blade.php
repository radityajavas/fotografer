@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-6 col-lg-5">

    <h2 class="h4 mb-3">Masuk</h2>

    <form method="POST" action="{{ route('login') }}">
      @csrf

      <div class="mb-3">
        <label for="email" class="form-label">Email atau username</label>
        <input id="email" type="text" name="email" value="{{ old('email') }}"
               class="form-control @error('email') is-invalid @enderror" required autofocus>
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Kata sandi</label>
        <input id="password" type="password" name="password"
               class="form-control @error('password') is-invalid @enderror" required>
        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" name="remember" id="remember"
               {{ old('remember') ? 'checked' : '' }}>
        <label class="form-check-label" for="remember">Ingat saya</label>
      </div>

      <button type="submit" class="btn btn-brand">Masuk</button>

      @if (Route::has('password.request'))
        <a href="{{ route('password.request') }}" class="ms-3">Lupa kata sandi?</a>
      @endif
    </form>

    <p class="mt-3 mb-0">
      Belum punya akun? <a href="{{ route('register') }}">Daftar</a>
    </p>

  </div>
</div>
@endsection