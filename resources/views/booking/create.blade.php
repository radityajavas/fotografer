@extends('layouts.app')

@section('content')

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --teal: #0f766e; --teal-dark: #0b5e57; }
        * { box-sizing: border-box; }
        body {
            margin: 0; min-height: 100vh; 
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif; color: #1f2937;
            background: linear-gradient(rgba(255,255,255,.35), rgba(255,255,255,.35)), url('{{ asset('images/foto.jpg') }}') center / cover fixed;
        }
        
        .card {
            width: 100%; max-width: 546px; margin: 0 auto; padding: 40px 48px; border-radius: 20px;
            background: linear-gradient(135deg, #a7e3cf 0%, #d9eec2 30%, #3f9a85 65%, #2d5a47 100%);
            box-shadow: 0 20px 50px rgba(0,0,0,.25);
        }
        h1 { margin: 0 0 20px; font-size: 26px; }
        label { display: block; margin: 16px 0 6px; font-weight: 700; font-size: 16px; }
        input, select, textarea {
            width: 100%; padding: 11px 14px; font: inherit; font-size: 15px;
            border: 1px solid #d1d5db; border-radius: 8px; background: #fff; color: #111827;
        }
        input[readonly] { background: #f3f4f6; }
        textarea { min-height: 92px; resize: vertical; }
        .hint { margin-top: 6px; font-size: 14px; color: #1f2937; }
        .hint.warn { color: #7f1d1d; font-weight: 600; }
        .alert { background: #fee2e2; color: #991b1b; padding: 10px 14px; border-radius: 8px; margin-bottom: 8px; font-size: 14px; }
        /* Ringkasan paket yang dipilih */
        .pkg-detail {
            margin-top: 10px; padding: 14px 16px; border-radius: 10px;
            background: #f0fdfa; border: 1px solid #99f6e4;
        }
        .pkg-detail-head { display: flex; justify-content: space-between; gap: 10px; flex-wrap: wrap; }
        .pkg-detail-name { font-weight: 700; font-size: 15px; }
        .pkg-detail-price { font-weight: 700; color: var(--teal); }
        .pkg-detail-meta { margin-top: 2px; font-size: 13px; color: #0f766e; font-weight: 600; }
        .pkg-detail-desc { margin: 8px 0 0; font-size: 13px; color: #374151; line-height: 1.5; }
        .pkg-detail-empty { margin: 0; font-size: 13px; color: #6b7280; }
        /* Kartu profil fotografer yang dipilih */
        .photographer-card {
            margin-top: 8px; padding: 14px 16px; border-radius: 12px;
            background: #fff; border: 1px solid #99f6e4;
            box-shadow: 0 4px 12px rgba(0,0,0,.08);
        }
        .photographer-main { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; }
        .photographer-avatar {
            width: 64px; height: 64px; border-radius: 50%; object-fit: cover;
            flex-shrink: 0; border: 2px solid #ccfbf1;
        }
        .photographer-avatar-fallback {
            display: flex; align-items: center; justify-content: center;
            background: #ccfbf1; color: #115e59; font-weight: 700; font-size: 24px;
        }
        .photographer-info { flex: 1; min-width: 150px; }
        .photographer-name { font-weight: 700; font-size: 16px; color: #111827; }
        .photographer-meta {
            display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
            margin-top: 4px; font-size: 13px; color: #374151;
        }
        .photographer-badge {
            display: inline-block; padding: 2px 8px; border-radius: 999px;
            background: #f0fdfa; border: 1px solid #99f6e4;
            color: var(--teal); font-weight: 600;
        }
        .photographer-portfolio { margin-top: 5px; font-size: 13px; color: #4b5563; }
        .btn-profile {
            display: inline-flex; align-items: center; justify-content: center; gap: 6px;
            padding: 8px 14px; border-radius: 8px; border: 1.5px solid var(--teal);
            background: #fff; color: var(--teal); font-weight: 700; font-size: 14px;
            text-decoration: none; white-space: nowrap; transition: background .15s, color .15s;
        }
        .btn-profile:hover { background: var(--teal); color: #fff; }
        .btn-profile:focus-visible { outline: 3px solid rgba(15,118,110,.35); outline-offset: 2px; }
        /* Ringkasan pada dialog konfirmasi */
        .confirm-list { width: 100%; font-size: 14px; border-collapse: collapse; }
        .confirm-list th, .confirm-list td { padding: 7px 0; vertical-align: top; text-align: left; }
        .confirm-list th { color: #6b7280; font-weight: 500; width: 45%; }
        .confirm-list td { font-weight: 600; }
        .confirm-list tr + tr th, .confirm-list tr + tr td { border-top: 1px solid #e5e7eb; }
        .actions { display: flex; gap: 8px; margin-top: 22px; }
        .btn { padding: 12px 16px; border: 0; border-radius: 8px; font: inherit; font-weight: 700; font-size: 16px; cursor: pointer; text-decoration: none; text-align: center; }
        .btn-primary { flex: 1; background: var(--teal); color: #fff; }
        .btn-primary:hover { background: var(--teal-dark); }
        .btn-primary:disabled { opacity: .6; cursor: not-allowed; }
        .btn-cancel { background: #6b7280; color: #fff; }
        @media (max-width: 480px) {
            .card { padding: 28px 22px; }
            .photographer-main { flex-direction: column; align-items: flex-start; }
            .btn-profile { width: 100%; }
        }
    </style>

<div class="card">
    <h1>Pesan fotografer</h1>

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert">{{ $error }}</div>
        @endforeach
    @endif

    @if (session('error'))
        <div class="alert">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ Route::has('booking.store') ? route('booking.store') : url('/booking') }}" id="bookingForm">
        @csrf
        <input type="hidden" name="photographer_id" value="{{ $selectedPhotographer->id }}">

        <label>Fotografer</label>
        @php
            $fotoProfil   = $selectedPhotographer->photo ?: optional($selectedPhotographer->photos->first())->path;
            $jumlahFoto   = $selectedPhotographer->photos->count();
        @endphp
        <div class="photographer-card">
            <div class="photographer-main">
                @if ($fotoProfil)
                    <img src="{{ asset('storage/' . $fotoProfil) }}"
                         alt="Foto profil {{ $selectedPhotographer->name }}"
                         class="photographer-avatar">
                @else
                    <div class="photographer-avatar photographer-avatar-fallback">
                        {{ strtoupper(mb_substr($selectedPhotographer->name, 0, 1)) }}
                    </div>
                @endif

                <div class="photographer-info">
                    <div class="photographer-name">{{ $selectedPhotographer->name }}</div>
                    <div class="photographer-meta">
                        <span class="photographer-badge">{{ $selectedPhotographer->specialization ?: 'Fotografer' }}</span>
                        @if ($selectedPhotographer->city)
                            <span class="photographer-city">&#128205; {{ $selectedPhotographer->city }}</span>
                        @endif
                    </div>
                    @if ($jumlahFoto)
                        <div class="photographer-portfolio">{{ $jumlahFoto }} foto portofolio siap dilihat</div>
                    @endif
                </div>

                <a href="{{ route('photographers.show', array_filter([
                        'id'         => $selectedPhotographer->id,
                        'return_to'  => 'booking',
                        'package_id' => $selectedPackageId,
                    ])) }}" class="btn-profile">
                    Lihat Profil &rarr;
                </a>
            </div>
        </div>
        @if (!empty($areas))
            <div class="hint">Area layanan: {{ implode(', ', $areas) }}</div>
        @endif

        <label for="package_id">Paket</label>
        <select name="package_id" id="package_id" required>
            <option value="">Pilih paket</option>
            @foreach ($packages as $package)
                <option value="{{ $package->id }}"
                        data-duration="{{ $package->duration_hours }}"
                        {{ (string) old('package_id', $selectedPackageId) === (string) $package->id ? 'selected' : '' }}>
                    {{ $package->name }} - Rp{{ number_format($package->price, 0, ',', '.') }} ({{ $package->duration_hours }} jam)
                </option>
            @endforeach
        </select>

        {{-- Detail paket terpilih, diisi otomatis dari data database --}}
        <div class="pkg-detail" id="packageDetail">
            <p class="pkg-detail-empty" id="pkgEmpty">Pilih paket untuk melihat harga, durasi, dan deskripsinya.</p>
            <div id="pkgBody" style="display:none">
                <div class="pkg-detail-head">
                    <span class="pkg-detail-name" id="pkgName"></span>
                    <span class="pkg-detail-price" id="pkgPrice"></span>
                </div>
                <div class="pkg-detail-meta" id="pkgMeta"></div>
                <p class="pkg-detail-desc" id="pkgDesc"></p>
            </div>
        </div>

        <label for="booking_date">Tanggal pelaksanaan</label>
        <input type="date" name="booking_date" id="booking_date"
               min="{{ today()->toDateString() }}" max="{{ today()->addYears(2)->toDateString() }}"
               value="{{ old('booking_date') }}" required>
        @if (count($offDates))
            <div class="hint">Libur seharian: {{ collect($offDates)->map(fn ($d) => \Carbon\Carbon::parse($d)->format('d M Y'))->implode(', ') }}</div>
        @endif
        <div class="hint warn" id="dateWarn" style="display:none"></div>
        <div class="hint" id="blockInfo" style="display:none"></div>

        <label for="start_time">Jam mulai</label>
        <select name="start_time" id="start_time" required>
            <option value="">Pilih paket dulu</option>
        </select>
        <div class="hint" id="endInfo">Jam operasional 08.00–20.00.</div>

        <label for="lokasi">Alamat / lokasi pemotretan</label>
        <textarea name="lokasi" id="lokasi" required minlength="10" maxlength="400">{{ old('lokasi') }}</textarea>

        <div class="actions">
            <button type="submit" class="btn btn-primary" id="submitBtn">Konfirmasi booking</button>
            <a href="{{ route('landing') }}" class="btn btn-cancel">Batal</a>
        </div>
    </form>
</div>

{{-- Dialog konfirmasi: ringkasan data sebelum form dikirim --}}
<div class="modal fade" id="modalKonfirmasi" tabindex="-1" aria-labelledby="modalKonfirmasiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius:14px">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalKonfirmasiLabel">Periksa data pemesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Apakah data pemesanan yang kamu masukkan sudah benar?</p>
                <table class="confirm-list">
                    <tr><th>Fotografer</th><td id="ringkasFotografer">-</td></tr>
                    <tr><th>Paket</th><td id="ringkasPaket">-</td></tr>
                    <tr><th>Harga</th><td id="ringkasHarga">-</td></tr>
                    <tr><th>Durasi</th><td id="ringkasDurasi">-</td></tr>
                    <tr><th>Tanggal</th><td id="ringkasTanggal">-</td></tr>
                    <tr><th>Jam mulai</th><td id="ringkasMulai">-</td></tr>
                    <tr><th>Perkiraan selesai</th><td id="ringkasSelesai">-</td></tr>
                    <tr><th>Lokasi</th><td id="ringkasLokasi">-</td></tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Kembali Periksa</button>
                <button type="button" class="btn btn-brand" id="confirmBtn">Ya, Pesan Sekarang</button>
            </div>
        </div>
    </div>
</div>

<script>
    const OPEN = 8 * 60, CLOSE = 20 * 60, STEP = 30;
    const offDates  = @json($offDates);
    const offRanges = @json($offRanges ?? []);
    const bookedRanges = @json($bookedRanges ?? []);
    const packageData = @json($packageData ?? []);
    const oldStart  = @json(old('start_time'));

    const form = document.getElementById('bookingForm');
    const pkg = document.getElementById('package_id');
    const dateEl = document.getElementById('booking_date');
    const startEl = document.getElementById('start_time');
    const endInfo = document.getElementById('endInfo');
    const dateWarn = document.getElementById('dateWarn');
    const blockInfo = document.getElementById('blockInfo');
    const submitBtn = document.getElementById('submitBtn');
    const pkgEmpty = document.getElementById('pkgEmpty');
    const pkgBody = document.getElementById('pkgBody');

    const fmt = m => String(Math.floor(m / 60)).padStart(2, '0') + ':' + String(m % 60).padStart(2, '0');
    const toMin = t => { const [h, m] = t.split(':').map(Number); return h * 60 + m; };
    const dot = t => t.replace(':', '.');
    const duration = () => parseInt(pkg.selectedOptions[0]?.dataset.duration || 0, 10);

    function selectedPackage() {
        return packageData[pkg.value] || null;
    }

    // Tampilkan ringkasan paket terpilih
    function updatePackageDetail() {
        const data = selectedPackage();
        if (!data) {
            pkgEmpty.style.display = 'block';
            pkgBody.style.display = 'none';
            return;
        }
        pkgEmpty.style.display = 'none';
        pkgBody.style.display = 'block';
        document.getElementById('pkgName').textContent = data.name;
        document.getElementById('pkgPrice').textContent = data.price_label;
        document.getElementById('pkgMeta').textContent = `Durasi ${data.duration} jam`;
        document.getElementById('pkgDesc').textContent = data.description || 'Tidak ada deskripsi paket.';
    }

    // Ambil semua jam yang tidak tersedia pada tanggal terpilih (libur + sudah dibooking)
    function blockedRanges(date) {
        return (offRanges[date] || []).concat(bookedRanges[date] || []);
    }

    function isBlocked(startMin, endMin, ranges) {
        return ranges.some(r => startMin < toMin(r.end) && endMin > toMin(r.start));
    }

    function buildStartOptions(keep) {
        const d = duration();
        const date = dateEl.value;
        const blocked = date ? blockedRanges(date) : [];
        startEl.innerHTML = '';
        if (!d) { startEl.innerHTML = '<option value="">Pilih paket dulu</option>'; return; }

        let jumlah = 0;
        startEl.insertAdjacentHTML('beforeend', '<option value="">Pilih jam mulai</option>');
        for (let m = OPEN; m + d * 60 <= CLOSE; m += STEP) {
            // Lewati jam yang bertabrakan dengan jadwal libur atau booking diterima
            if (isBlocked(m, m + d * 60, blocked)) continue;
            jumlah++;
            const sel = keep && keep === fmt(m) ? ' selected' : '';
            startEl.insertAdjacentHTML('beforeend', `<option value="${fmt(m)}"${sel}>${dot(fmt(m))}</option>`);
        }

        if (jumlah === 0) {
            const alasan = date
                ? 'Tidak ada jam yang muat pada tanggal ini (durasi atau jadwal terpakai). Pilih tanggal lain.'
                : 'Tidak ada jam yang muat (maks. selesai 20.00).';
            startEl.innerHTML = `<option value="">${alasan}</option>`;
        }
    }

    function updateEnd() {
        const d = duration();
        if (startEl.value && d) {
            const e = toMin(startEl.value) + d * 60;
            endInfo.textContent = `Perkiraan selesai pukul ${dot(fmt(e))} (durasi ${d} jam).`;
        } else {
            endInfo.textContent = 'Jam operasional 08.00–20.00.';
        }
    }

    function checkDate() {
        const v = dateEl.value;
        dateWarn.style.display = 'none';
        blockInfo.style.display = 'none';
        submitBtn.disabled = false;
        if (!v) return;
        if (offDates.includes(v)) {
            dateWarn.textContent = 'Fotografer libur seharian pada tanggal ini. Pilih tanggal lain.';
            dateWarn.style.display = 'block';
            submitBtn.disabled = true;
            return;
        }
        const ranges = blockedRanges(v);
        if (ranges.length) {
            blockInfo.textContent = 'Jam yang tidak tersedia: ' +
                ranges.map(r => dot(r.start) + '–' + dot(r.end)).join(', ') + '.';
            blockInfo.style.display = 'block';
        }
    }

    function refreshSchedule() {
        checkDate();
        buildStartOptions(startEl.value);
        updateEnd();
    }

    // Isi dialog konfirmasi dengan data yang benar-benar akan dikirim
    function fillSummary() {
        const data = selectedPackage();
        const d = duration();
        const mulai = startEl.value;
        const selesai = mulai && d ? fmt(toMin(mulai) + d * 60) : '';
        const tanggal = dateEl.value
            ? new Date(dateEl.value + 'T00:00:00').toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })
            : '-';

        document.getElementById('ringkasFotografer').textContent = @json($selectedPhotographer->name);
        document.getElementById('ringkasPaket').textContent = data ? data.name : '-';
        document.getElementById('ringkasHarga').textContent = data ? data.price_label : '-';
        document.getElementById('ringkasDurasi').textContent = d ? d + ' jam' : '-';
        document.getElementById('ringkasTanggal').textContent = tanggal;
        document.getElementById('ringkasMulai').textContent = mulai ? dot(mulai) : '-';
        document.getElementById('ringkasSelesai').textContent = selesai ? dot(selesai) : '-';
        document.getElementById('ringkasLokasi').textContent = document.getElementById('lokasi').value || '-';
    }

    pkg.addEventListener('change', () => { updatePackageDetail(); refreshSchedule(); });
    startEl.addEventListener('change', updateEnd);
    dateEl.addEventListener('change', refreshSchedule);

    // Tampilkan ringkasan sebelum form dikirim; validasi server tetap berjalan
    form.addEventListener('submit', function (e) {
        if (submitBtn.disabled) {
            e.preventDefault(); // tanggal libur seharian: form tidak boleh dikirim
            return;
        }
        if (!form.checkValidity()) {
            return; // biarkan browser menampilkan pesan validasi
        }
        e.preventDefault();
        fillSummary();
        bootstrap.Modal.getOrCreateInstance(document.getElementById('modalKonfirmasi')).show();
    });

    let sending = false;
    document.getElementById('confirmBtn').addEventListener('click', function () {
        if (sending) return; // cegah form terkirim dua kali
        sending = true;
        this.disabled = true;
        this.textContent = 'Mengirim...';
        form.submit();
    });

    updatePackageDetail();
    buildStartOptions(oldStart);
    updateEnd();
    checkDate();
</script>
@endsection