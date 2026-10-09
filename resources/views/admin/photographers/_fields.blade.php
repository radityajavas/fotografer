<div class="mb-3">
  <label class="form-label">Nama</label>
  <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
         value="{{ old('name', $photographer->name ?? '') }}" required>
</div>

<div class="mb-3">
  <label class="form-label">Telepon</label>
  <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
         value="{{ old('phone', $photographer->phone ?? '') }}" required maxlength="20" inputmode="tel">
</div>

<div class="mb-3">
    <label class="form-label">Kota</label>
    <input type="text" name="city" class="form-control @error('city') is-invalid @enderror"
         value="{{ old('city', $photographer->city ?? '') }}" required maxlength="100"
         placeholder="Malang, Batu">
    <div class="form-text">Wilayah yang dijangkau fotografer. Pisahkan dengan koma jika lebih dari satu kota.</div>
</div>

<div class="mb-3">
  <label class="form-label">Spesialisasi</label>
  <input type="text" name="specialization" class="form-control @error('specialization') is-invalid @enderror"
         value="{{ old('specialization', $photographer->specialization ?? '') }}" required>
</div>

<div class="mb-3">
  <label class="form-label">Status</label>
  <select name="status" class="form-select">
    @foreach (['AVAILABLE', 'UNAVAILABLE'] as $s)
      <option value="{{ $s }}" @selected(old('status', $photographer->status ?? 'AVAILABLE') === $s)>{{ $s }}</option>
    @endforeach
  </select>
</div>