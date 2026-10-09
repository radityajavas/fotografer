@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-6 col-lg-5">

    <h2 class="h4 mb-3">Masuk</h2>

    <form method="POST" action="{{ route('login') }}" novalidate>
      @csrf

      <div class="mb-3">
        <label for="email" class="form-label">Email atau username</label>
        <input id="email" type="text" name="email" value="{{ old('email') }}"
               class="form-control @error('email') is-invalid @enderror"
               required maxlength="255" autocomplete="username" autofocus>
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      @include('auth.partials.password', ['id' => 'password', 'name' => 'password', 'label' => 'Kata sandi'])

      <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" name="remember" id="remember"
               {{ old('remember') ? 'checked' : '' }}>
        <label class="form-check-label" for="remember">Ingat saya</label>
      </div>

      <button type="submit" class="btn btn-brand w-100">Masuk</button>

      @if (Route::has('password.request'))
        <div class="mt-3"><a href="{{ route('password.request') }}">Lupa kata sandi?</a></div>
      @endif
    </form>

    <p class="mt-3 mb-0">
      Belum punya akun? <a href="{{ route('register') }}">Daftar</a>
    </p>

  </div>
</div>
@endsection
