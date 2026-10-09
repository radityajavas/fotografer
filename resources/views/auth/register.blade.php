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

      <div class="mb-3">
        <label for="phone" class="form-label">Nomor telepon</label>
        <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
               class="form-control @error('phone') is-invalid @enderror" required>
        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label for="address" class="form-label">Alamat</label>
        <textarea id="address" name="address" rows="3"
                  class="form-control @error('address') is-invalid @enderror" required>{{ old('address') }}</textarea>
        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Kata sandi</label>
        <input id="password" type="password" name="password"
               class="form-control @error('password') is-invalid @enderror" required>
        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label for="password_confirmation" class="form-label">Ulangi kata sandi</label>
        <input id="password_confirmation" type="password" name="password_confirmation"
               class="form-control" required>
      </div>

      <button type="submit" class="btn btn-brand w-100">Daftar</button>
    </form>

    <p class="mt-3 mb-0">
      Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
    </p>

  </div>
</div>
{{-- JavaScript buat hide and show password --}}
<script>
  const checkbox = document.getElementById('showPassword');
  const passwordInput = document.getElementById('password');
const confirmPasswordInput = document.getElementById('password_confirmation');

  checkbox.addEventListener('change', function () {
      // Tentukan tipe baru berdasarkan status checkbox
      const type = this.checked ? 'text' : 'password';
      
      // Ubah tipe kedua input password
      passwordInput.type = type;
      confirmPasswordInput.type = type;
  });
</script>
@endsection
