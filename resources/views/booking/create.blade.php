
@extends('layouts.app')

@section('content')

<style>
    body {
        background:
            linear-gradient(
                rgba(255, 255, 255, 0.65),
                rgba(255, 255, 255, 0.65)
            ),
            url('{{ asset('images/foto.jpg') }}') center/cover fixed no-repeat;
    }
</style>

@php
    $offDates = $offDates ?? collect();

    // Format tanggal libur menjadi YYYY-MM-DD
    $offDateList = collect($offDates)
        ->map(fn ($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))
        ->values()
        ->all();
@endphp

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">

        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5"
             style="background: linear-gradient(135deg, #A8E6CF 0%, #DCEDC1 35%, #56AB91 70%, #234E39 100%);">

            <h2 class="h4 mb-4 text-dark fw-bold">
                Pesan fotografer
            </h2>

            <form action="{{ route('booking.store') }}"
                  method="POST"
                  id="bookingForm">
                @csrf

                {{-- FOTOGRAFER --}}
                <div class="mb-3">
                    <label class="form-label fw-bold text-dark">
                        Fotografer
                    </label>

                    <input type="hidden"
                           name="photographer_id"
                           value="{{ old('photographer_id', $selectedPhotographer->id ?? '') }}">

                    <input type="text"
                           class="form-control bg-white text-dark"
                           value="{{ $selectedPhotographer->name ?? 'Fotografer tidak ditemukan' }}"
                           readonly>

                    @error('photographer_id')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- PAKET --}}
                <div class="mb-3">
                    <label for="package_id" class="form-label fw-bold text-dark">
                        Paket
                    </label>

                    <select name="package_id"
                            id="package_id"
                            class="form-select bg-white text-dark @error('package_id') is-invalid @enderror"
                            required>
                        <option value="">Pilih paket</option>

                        @foreach ($packages as $pkg)
                            <option value="{{ $pkg->id }}"
                                @selected(old('package_id') == $pkg->id)>
                                {{ $pkg->name }} -
                                Rp{{ number_format($pkg->price ?? 0, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>

                    @error('package_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- TANGGAL --}}
                <div class="mb-3">
                    <label for="booking_date" class="form-label fw-bold text-dark">
                        Tanggal pelaksanaan
                    </label>

                    <input type="date"
                           name="booking_date"
                           id="booking_date"
                           value="{{ old('booking_date') }}"
                           min="{{ date('Y-m-d') }}"
                           class="form-control bg-white text-dark @error('booking_date') is-invalid @enderror"
                           required>

                    <div class="invalid-feedback" id="date-warning">
                        @error('booking_date')
                            {{ $message }}
                        @enderror
                    </div>

                    @if (count($offDateList) > 0)
                        <div class="form-text text-dark mt-1">
                            Tanggal libur fotografer:
                            {{ collect($offDateList)->map(fn ($d) => \Carbon\Carbon::parse($d)->format('d M Y'))->implode(', ') }}
                        </div>
                    @endif
                </div>

                

                {{-- LOKASI --}}
                <div class="mb-3">
                    <label for="lokasi" class="form-label fw-bold text-dark">
                        Alamat / lokasi pemotretan
                    </label>

                    <textarea name="lokasi"
                              id="lokasi"
                              rows="3"
                              class="form-control bg-white text-dark @error('lokasi') is-invalid @enderror"
                              required>{{ old('lokasi') }}</textarea>

                    @error('lokasi')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- TOMBOL --}}
                <div class="d-flex align-items-center gap-2 mt-4">
                    <button type="submit"
                            class="btn btn-brand flex-grow-1">
                        Konfirmasi booking
                    </button>

                    <a href="{{ route('landing') }}"
                       class="btn btn-secondary">
                        Batal
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const offDates = @json($offDateList);
        const dateInput = document.getElementById('booking_date');
        const warning = document.getElementById('date-warning');
        const form = document.getElementById('bookingForm');

        function validasiTanggal() {
            const tanggalDipilih = dateInput.value;
            const tanggalLibur = offDates.includes(tanggalDipilih);

            if (tanggalLibur) {
                dateInput.classList.add('is-invalid');
                dateInput.setCustomValidity(
                    'Fotografer tidak tersedia pada tanggal ini.'
                );
                warning.textContent =
                    'Fotografer tidak tersedia pada tanggal ini.';
                return false;
            }

            dateInput.classList.remove('is-invalid');
            dateInput.setCustomValidity('');
            warning.textContent = '';
            return true;
        }

        dateInput.addEventListener('change', validasiTanggal);
        dateInput.addEventListener('input', validasiTanggal);

        form.addEventListener('submit', function (event) {
            if (!validasiTanggal()) {
                event.preventDefault();
                dateInput.reportValidity();
            }
        });

        // Periksa kembali tanggal jika form dikembalikan dengan error.
        if (dateInput.value) {
            validasiTanggal();
        }
    });
</script>

@endsection