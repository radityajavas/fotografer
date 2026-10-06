@extends('layouts.app')

@section('content')

{{-- background foto wedding --}}
<style>
    body {
        background:
            linear-gradient(rgba(255, 255, 255, 0.65), rgba(255, 255, 255, 0.25)),
            url('{{ asset('images/foto.jpg') }}') center/cover fixed no-repeat;
    }
</style>

@php $offDates = $offDates ?? collect(); @endphp

<div class="row justify-content-center">
  <div class="col-md-8 col-lg-6">

    <!-- Card form dengan background gradasi halus -->
    <div class="card border-0 shadow-sm rounded-lg p-4 p-md-5" style="background: linear-gradient(135deg, #A8E6CF 0%, #DCEDC1 35%, #56AB91 70%, #234E39 100%);">

      <h2 class="h4 mb-3 text-dark font-weight-bold">Pesan fotografer</h2>

      <form action="{{ route('booking.store') }}" method="POST">
        @csrf

        <div class="mb-3">
          <label class="form-label font-weight-bold text-dark">Fotografer</label>
          <input type="hidden" name="photographer_id" value="{{ $selectedPhotographer->id ?? '' }}">
          <!-- Kolom input tetap putih bersih agar tidak tertutup gradasi -->
          <input type="text" class="form-control bg-white text-dark" readonly
                 value="{{ $selectedPhotographer->name ?? 'Fotografer tidak ditemukan' }}">
        </div>

        <div class="mb-3">
          <label for="package_id" class="form-label font-weight-bold text-dark">Paket</label>
          <select name="package_id" id="package_id"
                  class="form-select bg-white text-dark @error('package_id') is-invalid @enderror" required>
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
          <label for="booking_date" class="form-label font-weight-bold text-dark">Tanggal pelaksanaan</label>
          <input type="date" name="booking_date" id="booking_date"
                 value="{{ old('booking_date') }}" min="{{ date('Y-m-d') }}"
                 class="form-control bg-white text-dark @error('booking_date') is-invalid @enderror" required>
          <div class="invalid-feedback" id="date-warning">
            @error('booking_date') {{ $message }} @enderror
          </div>

          @if ($offDates->isNotEmpty())
            <div class="form-text text-dark mt-1">
              Tanggal libur fotografer: 
              {{ $offDates->map(fn ($d) => \Carbon\Carbon::parse($d)->format('d M Y'))->implode(', ') }}
            </div>
          @endif
        </div>

        <div class="mb-3">
          <label for="lokasi" class="form-label font-weight-bold text-dark">Alamat / lokasi pemotretan</label>
          <textarea name="lokasi" id="lokasi" rows="3"
                    class="form-control bg-white text-dark @error('lokasi') is-invalid @enderror"
                    required>{{ old('lokasi') }}</textarea>
          @error('lokasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <!-- Tombol sejajar -->
        <div class="d-flex align-items-center mt-4">
          <button type="submit" class="btn btn-brand flex-grow-1 mr-2">Konfirmasi booking</button>
          <a href="{{ route('landing') }}" class="btn btn-secondary">Batal</a>
        </div>
      </form>

    </div>

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