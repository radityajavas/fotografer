{{--
  Input password + tombol lihat/sembunyikan.
  Pemakaian: @include('auth.partials.password', ['id' => 'password', 'name' => 'password', 'label' => 'Kata sandi'])
--}}
<div class="mb-3">
  <label for="{{ $id }}" class="form-label">{{ $label }}</label>
  <div class="input-group has-validation">
    <input id="{{ $id }}" type="password" name="{{ $name }}"
           class="form-control @error($name) is-invalid @enderror"
           required minlength="{{ $minlength ?? 1 }}" autocomplete="{{ $autocomplete ?? 'current-password' }}">
    <button type="button" class="btn btn-outline-secondary" data-toggle-password="{{ $id }}"
            aria-label="Tampilkan kata sandi" title="Tampilkan / sembunyikan kata sandi">
      <svg class="icon-show" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
           stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/>
      </svg>
      <svg class="icon-hide d-none" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
           stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M17.94 17.94A10.94 10.94 0 0 1 12 19c-6.5 0-10-7-10-7a18.5 18.5 0 0 1 5.06-5.94M9.9 4.24A9.1 9.1 0 0 1 12 4c6.5 0 10 7 10 7a18.5 18.5 0 0 1-2.16 3.19M1 1l22 22"/>
      </svg>
    </button>
    @error($name) <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
</div>

@once
<script>
  document.addEventListener('click', function (e) {
    var btn = e.target.closest('[data-toggle-password]');
    if (!btn) return;
    var input = document.getElementById(btn.getAttribute('data-toggle-password'));
    var show  = input.type === 'password';
    input.type = show ? 'text' : 'password';
    btn.querySelector('.icon-show').classList.toggle('d-none', show);
    btn.querySelector('.icon-hide').classList.toggle('d-none', !show);
    btn.setAttribute('aria-label', show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
  });
</script>
@endonce
