@extends('layouts.app')

@section('content')
@php $offDates = $offDates ?? collect(); @endphp

<div class="row justify-content-center">
  <div class="col-md-8 col-lg-6">

    <h2 class="h4 mb-3">Pesan fotografer</h2>

    <form action="{{ route('booking.store') }}" method="POST">
      @csrf

      <div class="mb-3">
        <label class="form-label">Fotografer</label>
        <input type="hidden" name="photographer_id" value="{{ $selectedPhotographer->id ?? '' }}">
        <input type="text" class="form-control" readonly
               value="{{ $selectedPhotographer->name ?? 'Fotografer tidak ditemukan' }}">
      </div>

      <div class="mb-3">
        <label for="package_id" class="form-label">Paket</label>
        <select name="package_id" id="package_id"
                class="form-select @error('package_id') is-invalid @enderror" required>
          <option value="">Pilih paket</option>
          @foreach ($packages as $pkg)
            <option value="{{ $pkg->id }}" @selected(old('package_id') == $pkg->id)>
              {{ $pkg->name }} - Rp{{ number_format($pkg->price ?? 0, 0, ',', '.') }}
            </option>
          @endforeach
        </select>
        @error('package_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
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

      <div class="mb-3">
        <label for="lokasi" class="form-label">Alamat / lokasi pemotretan</label>
        <textarea name="lokasi" id="lokasi" rows="3"
                  class="form-control @error('lokasi') is-invalid @enderror"
                  required>{{ old('lokasi') }}</textarea>
        @error('lokasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <button type="submit" class="btn btn-brand">Konfirmasi booking</button>
      <a href="{{ route('landing') }}" class="ms-2">Batal</a>
    </form>

  </div>
</div>

<script>
  var offDates = @json($offDates);
  var dateInput = document.getElementById('booking_date');
  var warning = document.getElementById('date-warning');

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