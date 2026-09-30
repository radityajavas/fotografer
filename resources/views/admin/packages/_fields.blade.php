<div class="mb-3">
  <label class="form-label">Nama Paket</label>
  <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
         value="{{ old('name', $package->name ?? '') }}" required>
</div>
<div class="row">
  <div class="col-md-6 mb-3">
    <label class="form-label">Harga (Rp)</label>
    <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
           value="{{ old('price', $package->price ?? '') }}" required>
  </div>
  <div class="col-md-6 mb-3">
    <label class="form-label">Durasi (jam)</label>
    <input type="number" name="duration_hours" class="form-control @error('duration_hours') is-invalid @enderror"
           value="{{ old('duration_hours', $package->duration_hours ?? '') }}" required>
  </div>
</div>
<div class="mb-3">
  <label class="form-label">Deskripsi</label>
  <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $package->description ?? '') }}</textarea>
</div>