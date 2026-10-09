@php $isEdit = isset($customer) && $customer; @endphp

<div class="mb-3">
  <label class="form-label required">Nama lengkap</label>
  <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
         value="{{ old('name', $customer->name ?? '') }}" required minlength="3" maxlength="100">
</div>

<div class="mb-3">
  <label class="form-label required">Email</label>
  <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
         value="{{ old('email', $customer->email ?? '') }}" required maxlength="255">
</div>

<div class="row">
  <div class="col-md-6 mb-3">
    <label class="form-label">No HP</label>
    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
           value="{{ old('phone', $customer->phone ?? '') }}" maxlength="20" placeholder="081234567890" inputmode="tel">
  </div>
  <div class="col-md-6 mb-3">
    <label class="form-label {{ $isEdit ? '' : 'required' }}">Kata sandi</label>
    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
           {{ $isEdit ? '' : 'required' }} minlength="8" autocomplete="new-password"
           placeholder="{{ $isEdit ? 'Kosongkan jika tidak diubah' : 'Minimal 8 karakter' }}">
  </div>
</div>

<div class="mb-3">
  <label class="form-label">Alamat</label>
  <textarea name="address" rows="3" maxlength="500"
            class="form-control @error('address') is-invalid @enderror">{{ old('address', $customer->address ?? '') }}</textarea>
</div>
