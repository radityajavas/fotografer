@extends('layouts.admin')

@section('title', 'Data Booking')

@section('content')
@php
    $current = $status ?? 'all';
    $badge = [
        'pending'  => ['Menunggu', 'bg-yellow-lt'],
        'diterima' => ['Diterima', 'bg-green-lt'],
        'ditolak'  => ['Ditolak',  'bg-red-lt'],
    ];
    // Form hanya terbuka otomatis kalau pengisian sebelumnya gagal validasi
    $bukaForm = old('user_id') || old('photographer_id') || old('package_id');
@endphp

<style>
  .tri-toggle { display: inline-flex; align-items: center; gap: 2px; padding: 3px; border-radius: 999px;
                background: var(--tblr-bg-surface-secondary, #eef0f3); border: 1px solid var(--tblr-border-color, #dadfe5); }
  .tri-opt { width: 28px; height: 28px; border: 0; border-radius: 50%; background: transparent; padding: 0;
             display: inline-flex; align-items: center; justify-content: center; color: #9aa4b2; cursor: pointer;
             transition: background .15s, color .15s; }
  .tri-opt:hover:not(:disabled) { background: rgba(0,0,0,.07); }
  .tri-opt:disabled { cursor: default; opacity: 1; }
  .tri-toggle.is-locked { cursor: not-allowed; }
  .tri-toggle.is-locked .tri-opt { cursor: not-allowed; }
  .tri-toggle.is-locked .tri-opt:not(.tri-no):not(.tri-yes):not(.tri-mid) { opacity: .5; }
  .tri-toggle.is-locked[data-state="diterima"] .tri-no,
  .tri-toggle.is-locked[data-state="diterima"] .tri-mid,
  .tri-toggle.is-locked[data-state="ditolak"] .tri-yes,
  .tri-toggle.is-locked[data-state="ditolak"] .tri-mid { opacity: .35; }
  .tri-toggle[data-state="ditolak"]  .tri-no  { background: var(--tblr-danger,  #d63939); color: #fff; }
  .tri-toggle[data-state="pending"]  .tri-mid { background: var(--tblr-warning, #f59f00); color: #fff; }
  .tri-toggle[data-state="diterima"] .tri-yes { background: var(--tblr-success, #2fb344); color: #fff; }
</style>

@if (session('error'))
  <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
@endif
@if (session('info'))
  <div class="alert alert-info" role="alert">{{ session('info') }}</div>
@endif
@if (session('success'))
  <div class="alert alert-success" role="alert">{{ session('success') }}</div>
@endif
@if ($errors->any())
  <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
@endif

{{-- Tombol untuk menampilkan / menyembunyikan form booking baru --}}
<div class="d-flex justify-content-end mb-3">
  <button type="button" id="btnKeForm" class="btn btn-primary">
    <i class="ti ti-calendar-plus me-1"></i>Booking Baru
  </button>
</div>

{{-- Form: booking baru (tersembunyi sampai tombol ditekan) --}}
<div class="card mb-3 {{ $bukaForm ? '' : 'd-none' }}" id="formBookingBaru" style="scroll-margin-top: 1rem;">
  <div class="card-header">
    <h3 class="card-title"><i class="ti ti-calendar-plus me-1"></i>Buat Booking Baru</h3>
    <div class="card-actions">
      <button type="button" class="btn-close js-tutup-form" aria-label="Tutup"></button>
    </div>
  </div>
  <form method="POST" action="{{ route('admin.bookings.store') }}" autocomplete="off">
    @csrf
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label required">Pelanggan</label>
          <select name="user_id" required class="form-select @error('user_id') is-invalid @enderror">
            <option value="">Pilih pelanggan</option>
            @foreach ($customers as $c)
              <option value="{{ $c->id }}" @selected(old('user_id') == $c->id)>{{ $c->name }} · {{ $c->email }}</option>
            @endforeach
          </select>
          @error('user_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @else
            <div class="form-hint">Belum punya akun? Buat dulu di menu Pelanggan.</div>
          @enderror
        </div>
        <div class="col-md-4">
          <label class="form-label required">Fotografer</label>
          <select name="photographer_id" required class="form-select @error('photographer_id') is-invalid @enderror">
            <option value="">Pilih fotografer</option>
            @foreach ($photographers as $p)
              <option value="{{ $p->id }}" @selected(old('photographer_id') == $p->id)>{{ $p->name }}</option>
            @endforeach
          </select>
          @error('photographer_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-4">
          <label class="form-label required">Paket</label>
          <select name="package_id" id="nbPackage" required class="form-select @error('package_id') is-invalid @enderror">
            <option value="">Pilih paket</option>
            @foreach ($packages as $pk)
              <option value="{{ $pk->id }}" data-duration="{{ (int) $pk->duration_hours }}" @selected(old('package_id') == $pk->id)>
                {{ $pk->name }} · {{ $pk->duration_hours }} jam · Rp {{ number_format($pk->price, 0, ',', '.') }}
              </option>
            @endforeach
          </select>
          @error('package_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-3">
          <label class="form-label required">Tanggal</label>
          <input type="date" name="booking_date" value="{{ old('booking_date') }}" min="{{ today()->toDateString() }}" required
                 class="form-control @error('booking_date') is-invalid @enderror">
          @error('booking_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-3">
          <label class="form-label required">Jam mulai</label>
          <input type="time" name="start_time" id="nbStart" value="{{ old('start_time') }}" min="08:00" max="20:00" required
                 class="form-control @error('start_time') is-invalid @enderror">
          @error('start_time')
            <div class="invalid-feedback">{{ $message }}</div>
          @else
            <div class="form-hint" id="nbEnd">Jam operasional 08.00–20.00</div>
          @enderror
        </div>
        <div class="col-md-6">
          <label class="form-label required">Lokasi / alamat pemotretan</label>
          <textarea name="lokasi" rows="2" maxlength="400" required placeholder="Jalan, nomor, patokan"
                    class="form-control @error('lokasi') is-invalid @enderror">{{ old('lokasi') }}</textarea>
          @error('lokasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
      </div>
    </div>
    <div class="card-footer d-flex justify-content-between align-items-center">
      <span class="text-secondary small">Booking dibuat dengan status Menunggu, lalu disetujui lewat toggle di tabel bawah.</span>
      <div>
        <button type="button" class="btn me-2 js-tutup-form">Batal</button>
        <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>Simpan booking</button>
      </div>
    </div>
  </form>
</div>

<div class="row row-deck row-cards mb-3">
  <div class="col-sm-6 col-lg-3">
    <div class="card"><div class="card-body">
      <div class="subheader">Total Pemesanan</div>
      <div class="h1 mb-0">{{ $stats['total'] }}</div>
    </div></div>
  </div>
  <div class="col-sm-6 col-lg-3">
    <div class="card"><div class="card-body">
      <div class="subheader">Menunggu Konfirmasi</div>
      <div class="h1 mb-0">{{ $stats['pending'] }}</div>
    </div></div>
  </div>
  <div class="col-sm-6 col-lg-3">
    <div class="card"><div class="card-body">
      <div class="subheader">Diterima</div>
      <div class="h1 mb-0">{{ $stats['diterima'] }}</div>
    </div></div>
  </div>
  <div class="col-sm-6 col-lg-3">
    <div class="card"><div class="card-body">
      <div class="subheader">Total Pendapatan</div>
      <div class="h1 mb-0">Rp {{ number_format($stats['pendapatan'], 0, ',', '.') }}</div>
    </div></div>
  </div>
</div>

<script>
  // Petunjuk jam selesai di form booking
  (function () {
    var pkg = document.getElementById('nbPackage'), start = document.getElementById('nbStart'), hint = document.getElementById('nbEnd');
    if (!pkg || !start || !hint) return;
    var base = hint.textContent;
    function pad(n) { return (n < 10 ? '0' : '') + n; }
    function update() {
      var opt = pkg.options[pkg.selectedIndex], dur = opt ? parseInt(opt.getAttribute('data-duration'), 10) : 0;
      if (!dur || !start.value) { hint.textContent = base; return; }
      var p = start.value.split(':'), mins = parseInt(p[0], 10) * 60 + parseInt(p[1], 10) + dur * 60;
      hint.textContent = mins > 20 * 60 ? 'Melewati jam operasional (maks. selesai 20.00)'
                                       : 'Selesai pukul ' + pad(Math.floor(mins / 60)) + '.' + pad(mins % 60);
    }
    pkg.addEventListener('change', update);
    start.addEventListener('input', update);
    update();
  })();
</script>

<script>
  // Tombol "Booking Baru": tampilkan / sembunyikan form
  (function () {
    var card = document.getElementById('formBookingBaru');
    var btn  = document.getElementById('btnKeForm');
    if (!card || !btn) return;
    var labelAwal = btn.innerHTML;

    function buka() {
      card.classList.remove('d-none');
      btn.innerHTML = '<i class="ti ti-x me-1"></i>Tutup Form';
      card.scrollIntoView({ behavior: 'smooth', block: 'start' });
      setTimeout(function () {
        var f = card.querySelector('select[name="user_id"]');
        if (f) f.focus({ preventScroll: true });
      }, 400);
    }

    function tutup() {
      card.classList.add('d-none');
      btn.innerHTML = labelAwal;
    }

    btn.addEventListener('click', function () {
      if (card.classList.contains('d-none')) { buka(); } else { tutup(); }
    });

    document.querySelectorAll('.js-tutup-form').forEach(function (el) {
      el.addEventListener('click', tutup);
    });

    // Kalau pengisian sebelumnya gagal validasi, form langsung terbuka lagi
    @if ($bukaForm)
      buka();
    @endif
  })();
</script>

<div class="card">
  <div class="card-header">
    <ul class="nav nav-pills card-header-pills">
      @foreach (['all' => 'Semua', 'pending' => 'Menunggu', 'diterima' => 'Diterima', 'ditolak' => 'Ditolak'] as $val => $label)
        <li class="nav-item">
          <a class="nav-link {{ $current === $val ? 'active' : '' }}"
             href="{{ route('admin.bookings.index', ['status' => $val]) }}">{{ $label }}</a>
        </li>
      @endforeach
    </ul>
  </div>

  <div class="table-responsive">
    <table class="table table-vcenter card-table">
      <thead>
        <tr>
          <th>Pemesan</th>
          <th>Fotografer</th>
          <th>Tanggal Foto</th>
          <th>Total Harga</th>
          <th>Status</th>
          <th class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($bookings as $booking)
          @php $s = strtolower($booking->status); @endphp
          <tr>
            <td class="fw-semibold">{{ $booking->user->name ?? '-' }}</td>
            <td>{{ $booking->photographer->name ?? '-' }}</td>
            <td>
              {{ $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') : '-' }}
              @if ($booking->time_range)
                <div class="text-secondary small">{{ str_replace(':', '.', $booking->time_range) }}</div>
              @endif
            </td>
            <td>
              @if ($booking->package)
                Rp {{ number_format($booking->package->price, 0, ',', '.') }}
              @else
                -
              @endif
            </td>
            <td>
              <span class="badge {{ $badge[$s][1] ?? 'bg-secondary-lt' }}">
                {{ $badge[$s][0] ?? $booking->status }}
              </span>
            </td>
            <td class="text-center text-nowrap">
              {{-- Toggle 3 posisi: kiri = Ditolak, tengah = Menunggu, kanan = Diterima --}}
              <form action="{{ route('admin.bookings.updateStatus', $booking->id) }}" method="POST" class="d-inline-block align-middle me-2 js-status-form"
                    data-pemesan="{{ $booking->user->name ?? '-' }}"
                    data-fotografer="{{ $booking->photographer->name ?? '-' }}"
                    data-tanggal="{{ $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') : '-' }}{{ $booking->time_range ? ' · ' . str_replace(':', '.', $booking->time_range) : '' }}">
                @csrf @method('PATCH')
                @php $locked = $s !== 'pending'; @endphp
                <div class="tri-toggle {{ $locked ? 'is-locked' : '' }}" data-state="{{ $s }}"
                     title="{{ $locked ? 'Status sudah final dan tidak bisa diubah' : 'Pilih setujui atau tolak' }}">
                  <button type="submit" name="status" value="ditolak" class="tri-opt tri-no" title="Tolak" @disabled($locked)><i class="ti ti-x"></i></button>
                  <button type="button" class="tri-opt tri-mid" tabindex="-1" disabled><i class="ti ti-minus"></i></button>
                  <button type="submit" name="status" value="diterima" class="tri-opt tri-yes" title="Setujui" @disabled($locked)><i class="ti ti-check"></i></button>
                </div>
              </form>

              <a href="{{ route('admin.chats.show', $booking->id) }}" class="btn btn-sm btn-outline-secondary"><i class="ti ti-message-circle me-1"></i>Chat</a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center text-secondary py-4">Belum ada data booking.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if ($bookings->hasPages())
    <div class="card-footer">{{ $bookings->withQueryString()->links() }}</div>
  @endif
</div>

{{-- Pemicu tersembunyi untuk membuka modal konfirmasi --}}
<button type="button" id="csTrigger" class="d-none" data-bs-toggle="modal" data-bs-target="#confirmStatusModal" tabindex="-1" aria-hidden="true"></button>

{{-- Modal konfirmasi (Bootstrap) --}}
<div class="modal modal-blur fade" id="confirmStatusModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body text-center py-4">
        <div id="csIcon" class="mb-3" style="font-size:3rem;line-height:1"></div>
        <h3 id="csTitle" class="mb-2"></h3>
        <div id="csText" class="text-secondary"></div>
        <div class="alert alert-warning py-2 mt-3 mb-0 small">
          <i class="ti ti-lock me-1"></i>Pilihan ini tidak bisa diubah lagi.
        </div>
      </div>
      <div class="modal-footer">
        <div class="w-100">
          <div class="row">
            <div class="col"><button type="button" class="btn w-100" data-bs-dismiss="modal">Batal</button></div>
            <div class="col"><button type="button" class="btn w-100" id="csOk">Ya</button></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  (function () {
    var pendingForm = null, pendingValue = null;

    function submitWith(form, value) {
      var input = document.createElement('input');
      input.type = 'hidden'; input.name = 'status'; input.value = value;
      form.appendChild(input);
      form.submit(); // submit() tidak memicu event 'submit' lagi
    }

    document.addEventListener('submit', function (e) {
      var form = e.target;
      if (!form.classList || !form.classList.contains('js-status-form')) return;
      e.preventDefault();

      var value = e.submitter ? e.submitter.value : null;
      if (value !== 'diterima' && value !== 'ditolak') return;

      var approve = value === 'diterima';
      var detail = 'Pemesan: ' + form.dataset.pemesan + '\nFotografer: ' + form.dataset.fotografer + '\nTanggal: ' + form.dataset.tanggal;
      pendingForm = form; pendingValue = value;
      document.getElementById('csIcon').innerHTML = approve
        ? '<i class="ti ti-circle-check text-success"></i>'
        : '<i class="ti ti-circle-x text-danger"></i>';
      document.getElementById('csTitle').textContent = approve ? 'Setujui booking ini?' : 'Tolak booking ini?';
      document.getElementById('csText').innerHTML =
        'Pemesan: <strong></strong><br>Fotografer: <strong></strong><br>Tanggal: <strong></strong>';
      var strongs = document.getElementById('csText').querySelectorAll('strong');
      strongs[0].textContent = form.dataset.pemesan;
      strongs[1].textContent = form.dataset.fotografer;
      strongs[2].textContent = form.dataset.tanggal;

      var ok = document.getElementById('csOk');
      ok.className = 'btn w-100 ' + (approve ? 'btn-success' : 'btn-danger');
      ok.textContent = approve ? 'Ya, setujui' : 'Ya, tolak';
      document.getElementById('csTrigger').click(); // buka modal lewat data-API Bootstrap
      setTimeout(function () { // cadangan jika modal tidak terbuka
        if (!document.getElementById('confirmStatusModal').classList.contains('show')) {
          if (confirm((approve ? 'Setujui' : 'Tolak') + ' booking ini?\n\n' + detail + '\n\nPilihan tidak bisa diubah lagi.')) {
            submitWith(form, value);
          }
        }
      }, 300);
    });

    document.getElementById('csOk').addEventListener('click', function () {
      if (!pendingForm) return;
      this.disabled = true; // cegah klik ganda
      submitWith(pendingForm, pendingValue);
    });
  })();
</script>
@endsection