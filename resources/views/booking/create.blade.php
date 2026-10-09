@extends('layouts.app')

@section('content')
@php
    $offDates = $offDates ?? collect();
    $areas    = $areas ?? [];
    $noArea   = count($areas) === 0;
@endphp

<div class="row justify-content-center">
  <div class="col-md-8 col-lg-6">

    <h2 class="h4 mb-3">Pesan fotografer</h2>

    @if ($errors->has('photographer_id'))
      <div class="alert alert-danger">{{ $errors->first('photographer_id') }}</div>
    @endif

    <form action="{{ route('booking.store') }}" method="POST" novalidate>
      @csrf

      <div class="mb-3">
        <label class="form-label">Fotografer</label>
        <input type="hidden" name="photographer_id" value="{{ $selectedPhotographer->id }}">
        <input type="text" class="form-control" readonly value="{{ $selectedPhotographer->name }}">
        <div class="form-text">
          Wilayah layanan: {{ $noArea ? 'belum diatur' : implode(', ', $areas) }}
        </div>
      </div>

      {{-- Paket + detailnya --}}
      <div class="mb-3">
        <label for="package_id" class="form-label">Paket</label>
        <select name="package_id" id="package_id"
                class="form-select @error('package_id') is-invalid @enderror" required>
          <option value="">Pilih paket</option>
          @foreach ($packages as $pkg)
            <option value="{{ $pkg->id }}"
                    data-name="{{ $pkg->name }}"
                    data-price="Rp {{ number_format($pkg->price ?? 0, 0, ',', '.') }}"
                    data-duration="{{ $pkg->duration_hours }}"
                    data-desc="{{ $pkg->description }}"
                    @selected(old('package_id') == $pkg->id)>
              {{ $pkg->name }} - Rp {{ number_format($pkg->price ?? 0, 0, ',', '.') }}
            </option>
          @endforeach
        </select>
        @error('package_id') <div class="invalid-feedback">{{ $message }}</div> @enderror

        <div id="package-detail" class="card mt-2 d-none">
          <div class="card-body py-3">
            <div class="d-flex justify-content-between align-items-start gap-2">
              <div class="fw-semibold" id="pd-name"></div>
              <div class="fw-semibold text-nowrap" id="pd-price"></div>
            </div>
            <div class="small text-secondary mb-2">Durasi sesi: <span id="pd-duration"></span> jam</div>
            <div class="small" id="pd-desc" style="white-space: pre-line;"></div>
          </div>
        </div>
      </div>

      <div class="mb-3">
        <label for="booking_date" class="form-label">Tanggal pelaksanaan</label>
        <input type="date" name="booking_date" id="booking_date"
               value="{{ old('booking_date') }}" min="{{ date('Y-m-d') }}"
               class="form-control @error('booking_date') is-invalid @enderror" required>
        <div class="invalid-feedback" id="date-warning">
          @error('booking_date') {{ $message }} @enderror
        </div>

        @if ($offDates->isNotEmpty())
          <div class="form-text">
            Tanggal libur fotografer:
            {{ $offDates->map(fn ($d) => \Carbon\Carbon::parse($d)->format('d M Y'))->implode(', ') }}
          </div>
        @endif
      </div>

      {{-- Lokasi: kota dibatasi sesuai wilayah fotografer --}}
      <div class="mb-3">
        <label for="kota" class="form-label">Kota pemotretan</label>
        @if ($noArea)
          <div class="alert alert-warning mb-0">
            Fotografer ini belum memiliki wilayah layanan, jadi belum bisa dipesan.
          </div>
        @else
          <select name="kota" id="kota" class="form-select @error('kota') is-invalid @enderror" required>
            @if (count($areas) > 1)
              <option value="">Pilih kota</option>
            @endif
            @foreach ($areas as $area)
              <option value="{{ $area }}" @selected(old('kota') == $area)>{{ $area }}</option>
            @endforeach
          </select>
          @error('kota') <div class="invalid-feedback">{{ $message }}</div> @enderror
        @endif
      </div>

      <div class="mb-4">
        <label for="lokasi" class="form-label">Alamat lengkap lokasi</label>
        <textarea name="lokasi" id="lokasi" rows="3" minlength="10" maxlength="400"
                  placeholder="Nama jalan, nomor, patokan"
                  class="form-control @error('lokasi') is-invalid @enderror"
                  required @disabled($noArea)>{{ old('lokasi') }}</textarea>
        @error('lokasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-brand px-4" @disabled($noArea)>Konfirmasi booking</button>
        <a href="{{ route('landing') }}" class="btn btn-brand-outline px-4">Batal</a>
      </div>
    </form>

  </div>
</div>

<script>
  // --- Detail paket ---
  var pkgSelect = document.getElementById('package_id');
  var pkgBox    = document.getElementById('package-detail');

  function showPackage() {
    var opt = pkgSelect.options[pkgSelect.selectedIndex];
    if (!opt || !opt.value) { pkgBox.classList.add('d-none'); return; }
    document.getElementById('pd-name').textContent     = opt.dataset.name;
    document.getElementById('pd-price').textContent    = opt.dataset.price;
    document.getElementById('pd-duration').textContent = opt.dataset.duration;
    document.getElementById('pd-desc').textContent     = opt.dataset.desc;
    pkgBox.classList.remove('d-none');
  }
  pkgSelect.addEventListener('change', showPackage);
  showPackage();

  // --- Tanggal libur fotografer ---
  var offDates  = @json($offDates);
  var dateInput = document.getElementById('booking_date');
  var warning   = document.getElementById('date-warning');

  dateInput.addEventListener('input', function () {
    if (offDates.indexOf(dateInput.value) !== -1) {
      dateInput.classList.add('is-invalid');
      warning.textContent = 'Fotografer tidak tersedia pada tanggal ini.';
      dateInput.setCustomValidity('Tanggal libur');
    } else {
      dateInput.classList.remove('is-invalid');
      dateInput.setCustomValidity('');
    }
  });
</script>
@endsection
