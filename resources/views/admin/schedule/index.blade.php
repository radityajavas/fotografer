@extends('layouts.admin')
@section('title', 'Manajemen Jadwal')

@section('content')
@if (session('error'))
  <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
@endif
@if ($errors->any())
  <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
@endif

<div class="card mb-3">
  <div class="card-header"><h3 class="card-title">Tandai Tanggal Tidak Tersedia</h3></div>
  <form method="POST" action="{{ route('admin.schedule.store') }}">
    @csrf
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-3">
          <label class="form-label">Fotografer</label>
          <select name="photographer_id" class="form-select" required>
            <option value="">Pilih fotografer</option>
            @foreach ($photographers as $p)
              <option value="{{ $p->id }}" @selected(old('photographer_id') == $p->id)>{{ $p->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label">Tanggal</label>
          <input type="date" name="date" class="form-control" min="{{ today()->toDateString() }}"
                 value="{{ old('date') }}" required>
        </div>
        <div class="col-md-2">
          <label class="form-label">Jam mulai</label>
          <input type="time" name="start_time" class="form-control" value="{{ old('start_time') }}">
        </div>
        <div class="col-md-2">
          <label class="form-label">Jam selesai</label>
          <input type="time" name="end_time" class="form-control" value="{{ old('end_time') }}">
        </div>
        <div class="col-md-3">
          <label class="form-label">Keterangan (opsional)</label>
          <input type="text" name="note" class="form-control" placeholder="Contoh: Libur, makan di mcd, farming piala"
                 value="{{ old('note') }}">
        </div>
      </div>
      <div class="form-hint mt-2 text-secondary small">Kosongkan jam mulai dan jam selesai untuk menandai libur seharian.</div>
    </div>
    <div class="card-footer text-end">
      <button class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>Simpan</button>
    </div>
  </form>
</div>

<div class="row row-cards">
  @foreach ($photographers as $p)
    @php
      $perTanggal = $p->bookings->groupBy(fn ($b) => \Carbon\Carbon::parse($b->booking_date)->toDateString());
    @endphp
    <div class="col-md-6 col-lg-4">
      <div class="card">
        <div class="card-header">
          <div>
            <h3 class="card-title">{{ $p->name }}</h3>
            <div class="text-secondary small">{{ $p->specialization ?? 'Fotografer' }}</div>
          </div>
        </div>

        <div class="list-group list-group-flush">
          {{-- Tanggal tidak tersedia (Libur): Ubah & Hapus --}}
          @foreach ($p->schedules as $s)
            <div class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <div class="fw-bold">{{ $s->date->translatedFormat('d F Y') }}</div>
                <div class="small">{{ $s->time_range ? str_replace(':', '.', $s->time_range) : 'Seharian' }}</div>
                <div class="text-secondary small">{{ $s->note ?: 'Tidak tersedia' }}</div>
              </div>
              <div class="d-flex align-items-center gap-2">
                <span class="badge bg-secondary-lt">Libur</span>

                <button type="button" class="btn btn-sm btn-outline-secondary js-edit"
                        data-bs-toggle="modal" data-bs-target="#editScheduleModal"
                        data-id="{{ $s->id }}"
                        data-date="{{ $s->date->toDateString() }}"
                        data-note="{{ $s->note }}"
                        data-start="{{ $s->start_time ? substr($s->start_time, 0, 5) : '' }}"
                        data-end="{{ $s->end_time ? substr($s->end_time, 0, 5) : '' }}"
                        data-photographer="{{ $p->name }}">
                  <i class="ti ti-edit me-1"></i>Ubah
                </button>

                <form action="{{ route('admin.schedule.destroy', $s->id) }}" method="POST"
                      class="js-confirm-form"
                      data-variant="danger"
                      data-title="Hapus tanggal ini?"
                      data-text="{{ $p->name }} · {{ $s->date->translatedFormat('d F Y') }}"
                      data-ok="Ya, hapus">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger"><i class="ti ti-trash me-1"></i>Hapus</button>
                </form>
              </div>
            </div>
          @endforeach

          {{-- Booking (konfirmasi dilakukan di halaman Booking) --}}
          @foreach ($p->bookings as $b)
            @php
              $bentrok = $p->bookings->contains(fn ($o) => $o->id !== $b->id && $b->overlapsWith($o));
              $diterima = strtolower($b->status) === 'diterima';
            @endphp
            <div class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <div class="fw-bold">{{ \Carbon\Carbon::parse($b->booking_date)->translatedFormat('d F Y') }}</div>
                <div class="small">{{ $b->time_range ? str_replace(':', '.', $b->time_range) : 'Seharian' }}</div>
                <div class="text-secondary small">
                  {{ $b->user->name ?? 'Pelanggan' }} · {{ $b->package->name ?? '-' }}
                </div>
                @if ($bentrok)
                  <div class="text-danger small">Bentrok: ada booking lain di tanggal &amp; jam yang sama</div>
                @endif
              </div>
              <div class="d-flex align-items-center gap-2">
                <span class="badge {{ $diterima ? 'bg-green-lt' : 'bg-yellow-lt' }}">
                  {{ $diterima ? 'Diterima' : 'Menunggu' }}
                </span>
              </div>
            </div>
          @endforeach

          @if ($p->schedules->isEmpty() && $p->bookings->isEmpty())
            <div class="list-group-item text-success">Jadwal kosong (tersedia)</div>
          @endif
        </div>
      </div>
    </div>
  @endforeach
</div>

{{-- Pemicu tersembunyi untuk membuka modal konfirmasi --}}
<button type="button" id="caTrigger" class="d-none" data-bs-toggle="modal" data-bs-target="#confirmActionModal" tabindex="-1" aria-hidden="true"></button>

{{-- Modal konfirmasi (Hapus & Konfirmasi) --}}
<div class="modal modal-blur fade" id="confirmActionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body text-center py-4">
        <div id="caIcon" class="mb-3" style="font-size:3rem;line-height:1"></div>
        <h3 id="caTitle" class="mb-2"></h3>
        <div id="caText" class="text-secondary"></div>
      </div>
      <div class="modal-footer">
        <div class="w-100">
          <div class="row">
            <div class="col"><button type="button" class="btn w-100" data-bs-dismiss="modal">Batal</button></div>
            <div class="col"><button type="button" class="btn w-100" id="caOk">Ya</button></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Modal Ubah --}}
<div class="modal modal-blur fade" id="editScheduleModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form method="POST" id="editScheduleForm" action="">
        @csrf @method('PATCH')
        <div class="modal-header">
          <h5 class="modal-title">Ubah Tanggal Tidak Tersedia</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Fotografer</label>
            <input type="text" class="form-control" id="editPhotographer" disabled>
          </div>
          <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date" name="date" id="editDate" class="form-control" min="{{ today()->toDateString() }}" required>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-6">
              <label class="form-label">Jam mulai</label>
              <input type="time" name="start_time" id="editStart" class="form-control">
            </div>
            <div class="col-6">
              <label class="form-label">Jam selesai</label>
              <input type="time" name="end_time" id="editEnd" class="form-control">
            </div>
            <div class="col-12 text-secondary small">Kosongkan kedua jam untuk libur seharian.</div>
          </div>
          <div class="mb-0">
            <label class="form-label">Keterangan (opsional)</label>
            <input type="text" name="note" id="editNote" class="form-control" maxlength="255">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>Simpan perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  (function () {
    var updateUrlTemplate = @json(route('admin.schedule.update', 0));
    var pendingForm = null;

    function modalFor(id) {
      var el = document.getElementById(id);
      return (window.bootstrap && window.bootstrap.Modal)
        ? window.bootstrap.Modal.getOrCreateInstance(el) : null;
    }

    // Ubah
    document.addEventListener('click', function (e) {
      var btn = e.target.closest('.js-edit');
      if (!btn) return;
      // Modal dibuka lewat data-bs-toggle pada tombol; di sini hanya mengisi form
      document.getElementById('editScheduleForm').action = updateUrlTemplate.replace(/0$/, btn.dataset.id);
      document.getElementById('editPhotographer').value = btn.dataset.photographer;
      document.getElementById('editDate').value = btn.dataset.date;
      document.getElementById('editStart').value = btn.dataset.start || '';
      document.getElementById('editEnd').value = btn.dataset.end || '';
      document.getElementById('editNote').value = btn.dataset.note || '';
    });

    // Hapus & Konfirmasi (modal konfirmasi)
    document.addEventListener('submit', function (e) {
      var form = e.target;
      if (!form.classList || !form.classList.contains('js-confirm-form')) return;
      e.preventDefault();

      pendingForm = form;
      var success = form.dataset.variant === 'success';
      document.getElementById('caIcon').innerHTML = success
        ? '<i class="ti ti-circle-check text-success"></i>'
        : '<i class="ti ti-trash text-danger"></i>';
      document.getElementById('caTitle').textContent = form.dataset.title;
      document.getElementById('caText').textContent = form.dataset.text;
      var ok = document.getElementById('caOk');
      ok.className = 'btn w-100 ' + (success ? 'btn-success' : 'btn-danger');
      ok.textContent = form.dataset.ok || 'Ya';
      ok.disabled = false;
      document.getElementById('caTrigger').click(); // buka modal lewat data-API Bootstrap
      setTimeout(function () { // cadangan jika modal tidak terbuka
        if (!document.getElementById('confirmActionModal').classList.contains('show')) {
          if (confirm(form.dataset.title + '\n\n' + form.dataset.text)) form.submit();
        }
      }, 300);
    });

    document.getElementById('caOk').addEventListener('click', function () {
      if (!pendingForm) return;
      this.disabled = true; // cegah klik ganda
      pendingForm.submit(); // submit() tidak memicu event 'submit' lagi
    });
  })();
</script>
@endsection
