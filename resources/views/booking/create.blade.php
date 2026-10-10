<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesan Fotografer</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --teal: #0f766e; --teal-dark: #0b5e57; }
        * { box-sizing: border-box; }
        body {
            margin: 0; min-height: 100vh; padding: 32px 16px;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif; color: #1f2937;
            /* Ganti dengan gambar latar Anda, mis: url('{{ asset('images/bg.jpg') }}') center/cover fixed */
            background: linear-gradient(135deg, #134e4a, #1f2937) fixed;
            display: flex; justify-content: center; align-items: flex-start;
        }
        .card {
            width: 100%; max-width: 546px; padding: 40px 48px; border-radius: 20px;
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
        .actions { display: flex; gap: 8px; margin-top: 22px; }
        .btn { padding: 12px 16px; border: 0; border-radius: 8px; font: inherit; font-weight: 700; font-size: 16px; cursor: pointer; text-decoration: none; text-align: center; }
        .btn-primary { flex: 1; background: var(--teal); color: #fff; }
        .btn-primary:hover { background: var(--teal-dark); }
        .btn-primary:disabled { opacity: .6; cursor: not-allowed; }
        .btn-cancel { background: #6b7280; color: #fff; }
        @media (max-width: 480px) { .card { padding: 28px 22px; } }
    </style>
</head>
<body>
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
        <input type="text" value="{{ $selectedPhotographer->name }}" readonly>
        @if (!empty($areas))
            <div class="hint">Area layanan: {{ implode(', ', $areas) }}</div>
        @endif

        <label for="package_id">Paket</label>
        <select name="package_id" id="package_id" required>
            <option value="">Pilih paket</option>
            @foreach ($packages as $package)
                <option value="{{ $package->id }}"
                        data-duration="{{ $package->duration_hours }}"
                        {{ old('package_id') == $package->id ? 'selected' : '' }}>
                    {{ $package->name }} - Rp{{ number_format($package->price, 0, ',', '.') }} ({{ $package->duration_hours }} jam)
                </option>
            @endforeach
        </select>

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
            <a href="{{ url()->previous() }}" class="btn btn-cancel">Batal</a>
        </div>
    </form>
</div>

<script>
    const OPEN = 8 * 60, CLOSE = 20 * 60, STEP = 30;
    const offDates  = @json($offDates);
    const offBlocks = @json($offBlocks ?? []);
    const oldStart  = @json(old('start_time'));

    const pkg = document.getElementById('package_id');
    const dateEl = document.getElementById('booking_date');
    const startEl = document.getElementById('start_time');
    const endInfo = document.getElementById('endInfo');
    const dateWarn = document.getElementById('dateWarn');
    const blockInfo = document.getElementById('blockInfo');
    const submitBtn = document.getElementById('submitBtn');

    const fmt = m => String(Math.floor(m / 60)).padStart(2, '0') + ':' + String(m % 60).padStart(2, '0');
    const toMin = t => { const [h, m] = t.split(':').map(Number); return h * 60 + m; };
    const duration = () => parseInt(pkg.selectedOptions[0]?.dataset.duration || 0, 10);

    function buildStartOptions(keep) {
        const d = duration();
        startEl.innerHTML = '';
        if (!d) { startEl.innerHTML = '<option value="">Pilih paket dulu</option>'; return; }
        startEl.insertAdjacentHTML('beforeend', '<option value="">Pilih jam mulai</option>');
        for (let m = OPEN; m + d * 60 <= CLOSE; m += STEP) {
            const sel = keep && keep === fmt(m) ? ' selected' : '';
            startEl.insertAdjacentHTML('beforeend', `<option value="${fmt(m)}"${sel}>${fmt(m).replace(':', '.')}</option>`);
        }
        if (startEl.options.length === 1) {
            startEl.innerHTML = '<option value="">Tidak ada jam yang muat (maks. selesai 20.00)</option>';
        }
    }

    function updateEnd() {
        const d = duration();
        if (startEl.value && d) {
            const e = toMin(startEl.value) + d * 60;
            endInfo.textContent = `Selesai pukul ${fmt(e).replace(':', '.')} (durasi ${d} jam).`;
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
        if (offBlocks[v] && offBlocks[v].length) {
            blockInfo.textContent = 'Tidak tersedia pada jam: ' + offBlocks[v].join(', ').replaceAll(':', '.');
            blockInfo.style.display = 'block';
        }
    }

    pkg.addEventListener('change', () => { buildStartOptions(); updateEnd(); });
    startEl.addEventListener('change', updateEnd);
    dateEl.addEventListener('change', checkDate);

    buildStartOptions(oldStart);
    updateEnd();
    checkDate();
</script>
</body>
</html>