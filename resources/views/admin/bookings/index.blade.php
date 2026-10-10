@extends('layouts.admin')

@section('title', 'Data Booking')

@section('content')
<style>
    .bk-stat { background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px 18px; height: 100%; }
    .bk-stat .lbl { font-size: 11px; font-weight: 600; letter-spacing: .04em; text-transform: uppercase; color: #6b7280; }
    .bk-stat .num { margin-top: 6px; font-size: 22px; font-weight: 700; color: #111827; }

    .bk-box { background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; }
    .bk-tabs { padding: 12px 14px; border-bottom: 1px solid #f0f1f3; }
    .bk-tabs a { display: inline-block; margin-right: 4px; padding: 6px 12px; border-radius: 6px; font-size: 13px; color: #6b7280; text-decoration: none; }
    .bk-tabs a.on { background: #eaf2fd; color: #2563eb; }

    .bk-table { width: 100%; border-collapse: collapse; font-size: 13px; color: #374151; }
    .bk-table th { padding: 10px 16px; font-size: 11px; font-weight: 600; letter-spacing: .04em; text-transform: uppercase; color: #6b7280; background: #fafafa; border-bottom: 1px solid #f0f1f3; text-align: left; white-space: nowrap; }
    .bk-table td { padding: 14px 16px; border-bottom: 1px solid #f3f4f6; vertical-align: middle; }
    .bk-table tr:last-child td { border-bottom: 0; }
    .bk-table .center { text-align: center; }

    .bd { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; }
    .bd.m { background: #fef3c7; color: #b45309; }
    .bd.d { background: #dcfce7; color: #15803d; }
    .bd.t { background: #fee2e2; color: #b91c1c; }

    /* toggle: kiri = tolak, tengah = menunggu, kanan = terima */
    .tg { position: relative; display: inline-block; width: 76px; height: 32px; box-sizing: border-box; border: 1px solid #e5e7eb; border-radius: 16px; background: #f3f4f6; vertical-align: middle; }
    .tg .s { position: absolute; top: 0; width: 24px; line-height: 30px; text-align: center; font-size: 12px; color: #9ca3af; }
    .tg .s1 { left: 3px; } .tg .s2 { left: 25px; } .tg .s3 { left: 47px; }
    .tg .k { position: absolute; top: 3px; left: 25px; width: 24px; height: 24px; border-radius: 50%; color: #fff; font-size: 13px; line-height: 24px; text-align: center; background: #f59e0b; transition: left .2s; pointer-events: none; }
    .tg.kiri .k { left: 3px; background: #dc2626; }
    .tg.kanan .k { left: 47px; background: #22c55e; }
    .tg.kunci { opacity: .75; }
    .tg button { position: absolute; top: 0; bottom: 0; width: 50%; padding: 0; border: 0; background: transparent; cursor: pointer; }
    .tg .zk { left: 0; border-radius: 16px 0 0 16px; } .tg .zn { right: 0; border-radius: 0 16px 16px 0; }
    .tg .zk:hover { background: rgba(220,38,38,.12); } .tg .zn:hover { background: rgba(34,197,94,.15); }

    .bk-chat { display: inline-block; margin-left: 8px; padding: 5px 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 12px; color: #374151; text-decoration: none; vertical-align: middle; }
    .bk-chat:hover { background: #f3f4f6; color: #111827; text-decoration: none; }
    .bk-chat svg { width: 12px; height: 12px; margin-right: 4px; vertical-align: -1px; }
</style>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="row" style="margin-bottom: 16px">
    <div class="col-6 col-md-3" style="margin-bottom: 12px"><div class="bk-stat"><div class="lbl">Total Pemesanan</div><div class="num">{{ $stats['total'] }}</div></div></div>
    <div class="col-6 col-md-3" style="margin-bottom: 12px"><div class="bk-stat"><div class="lbl">Menunggu Konfirmasi</div><div class="num">{{ $stats['pending'] }}</div></div></div>
    <div class="col-6 col-md-3" style="margin-bottom: 12px"><div class="bk-stat"><div class="lbl">Diterima</div><div class="num">{{ $stats['diterima'] }}</div></div></div>
    <div class="col-6 col-md-3" style="margin-bottom: 12px"><div class="bk-stat"><div class="lbl">Total Pendapatan</div><div class="num">Rp {{ number_format($stats['pendapatan'], 0, ',', '.') }}</div></div></div>
</div>

<div class="bk-box">
    <div class="bk-tabs">
        @foreach (['all' => 'Semua', 'menunggu' => 'Menunggu', 'diterima' => 'Diterima', 'ditolak' => 'Ditolak'] as $key => $label)
            <a href="{{ route('admin.bookings.index', $key === 'all' ? [] : ['status' => $key]) }}"
               class="{{ ($status ?: 'all') === $key ? 'on' : '' }}">{{ $label }}</a>
        @endforeach
    </div>

    <div class="table-responsive">
        <table class="bk-table">
            <thead>
                <tr>
                    <th>Pemesan</th>
                    <th>Fotografer</th>
                    <th>Tanggal Foto</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                    <th class="center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($bookings as $b)
                @php
                    $tgl  = $b->booking_date ? \Carbon\Carbon::parse($b->booking_date) : null;
                    $lewat = $tgl && $tgl->isPast() && ! $tgl->isToday();
                    $st   = strtolower($b->status);
                    $chatUrl = \Illuminate\Support\Facades\Route::has('admin.chats.show')
                        ? route('admin.chats.show', $b->id)
                        : url('/admin/chats/' . $b->id);
                @endphp
                <tr>
                    <td><strong>{{ $b->user->name ?? '-' }}</strong></td>
                    <td>{{ $b->photographer->name ?? '-' }}</td>
                    <td style="white-space: nowrap">
                        {{ $tgl ? $tgl->format('d M Y') : '-' }}
                        @if ($b->time_range)
                            <div style="font-size: 11px; color: #9ca3af">{{ str_replace(':', '.', $b->time_range) }}</div>
                        @endif
                    </td>
                    <td style="white-space: nowrap">Rp {{ number_format($b->package->price ?? $b->total_harga ?? 0, 0, ',', '.') }}</td>
                    <td>
                        @if ($st === 'diterima')
                            <span class="bd d">Diterima</span>
                        @elseif ($st === 'ditolak')
                            <span class="bd t">Ditolak</span>
                        @else
                            <span class="bd m">Menunggu</span>
                        @endif
                    </td>
                    <td class="center" style="white-space: nowrap">
                        @if ($st === 'diterima')
                            <span class="tg kanan kunci"><span class="s s1">&#10005;</span><span class="s s2">&ndash;</span><span class="s s3">&#10003;</span><span class="k">&#10003;</span></span>
                        @elseif ($st === 'ditolak')
                            <span class="tg kiri kunci"><span class="s s1">&#10005;</span><span class="s s2">&ndash;</span><span class="s s3">&#10003;</span><span class="k">&#10005;</span></span>
                        @elseif ($lewat)
                            <span class="tg kunci" title="Tanggal foto sudah lewat"><span class="s s1">&#10005;</span><span class="s s2">&ndash;</span><span class="s s3">&#10003;</span><span class="k">&ndash;</span></span>
                        @else
                            <form method="POST" action="{{ route('admin.bookings.updateStatus', $b->id) }}" class="form-status" style="display: inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="">
                                <span class="tg">
                                    <span class="s s1">&#10005;</span><span class="s s2">&ndash;</span><span class="s s3">&#10003;</span>
                                    <span class="k">&ndash;</span>
                                    <button type="button" class="zk" title="Tolak" data-status="ditolak" data-nama="{{ $b->user->name ?? 'pelanggan' }}"></button>
                                    <button type="button" class="zn" title="Terima" data-status="diterima" data-nama="{{ $b->user->name ?? 'pelanggan' }}"></button>
                                </span>
                            </form>
                        @endif
                        <a href="{{ $chatUrl }}" class="bk-chat">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a8 8 0 0 1-11.6 7.1L3 21l1.9-6.4A8 8 0 1 1 21 12z"/></svg>Chat
                        </a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="center" style="color: #9ca3af">Belum ada booking.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 12px 16px">
        {{ $bookings->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>
</div>

{{-- Modal konfirmasi (gaya Tabler) --}}
<style>
    #modalKonfirmasi { background: rgba(24, 36, 51, .35); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px); }
    #modalKonfirmasi .modal-content { position: relative; border: 0; border-radius: 6px; overflow: hidden; box-shadow: 0 8px 30px rgba(24, 36, 51, .2); }
    #modalKonfirmasi .modal-status { position: absolute; top: 0; left: 0; right: 0; height: 2px; }
    #modalKonfirmasi .st-merah { background: #d63939; }
    #modalKonfirmasi .st-hijau { background: #2fb344; }
    #modalKonfirmasi .tutup { position: absolute; top: 8px; right: 12px; border: 0; background: transparent; font-size: 22px; line-height: 1; color: #9ca3af; cursor: pointer; }
    #modalKonfirmasi .tutup:hover { color: #374151; }
    #modalKonfirmasi .ikon { width: 48px; height: 48px; margin-bottom: 10px; }
    #modalKonfirmasi .judul { margin: 0 0 6px; font-size: 18px; font-weight: 600; color: #182433; }
    #modalKonfirmasi .isi { color: #667382; font-size: 14px; }
    #modalKonfirmasi .modal-footer { border-top: 1px solid #e6e7e9; padding: 12px 16px; }
    #modalKonfirmasi .modal-footer .tombol { display: block; width: 100%; padding: 7px 12px; border-radius: 4px; font-size: 14px; cursor: pointer; text-align: center; }
    #modalKonfirmasi .t-batal { border: 1px solid #dadfe5; background: #fff; color: #182433; }
    #modalKonfirmasi .t-batal:hover { background: #f6f8fb; }
    #modalKonfirmasi .t-ya { border: 1px solid transparent; color: #fff; }
    #modalKonfirmasi .t-ya.merah { background: #d63939; }
    #modalKonfirmasi .t-ya.hijau { background: #2fb344; }
</style>

<div class="modal fade" id="modalKonfirmasi" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="tutup" data-tutup aria-label="Tutup">&times;</button>
            <div class="modal-status st-merah" id="mkBar"></div>
            <div class="modal-body text-center" style="padding: 28px 24px 20px">
                <svg id="mkIkonTolak" class="ikon" viewBox="0 0 24 24" fill="none" stroke="#d63939" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4"/><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z"/><path d="M12 16h.01"/></svg>
                <svg id="mkIkonTerima" class="ikon" style="display:none" viewBox="0 0 24 24" fill="none" stroke="#2fb344" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"/><path d="M9 12l2 2l4 -4"/></svg>
                <h3 class="judul" id="mkJudul"></h3>
                <div class="isi" id="mkIsi"></div>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <div class="row">
                        <div class="col"><button type="button" class="tombol t-batal" data-tutup>Batal</button></div>
                        <div class="col"><button type="button" class="tombol t-ya merah" id="mkYa">Ya</button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        var modal = document.getElementById('modalKonfirmasi');
        var formAktif = null, statusAktif = null, backdrop = null;

        function buka(form, status, nama) {
            formAktif = form; statusAktif = status;
            var tolak = status === 'ditolak';
            document.getElementById('mkBar').className = 'modal-status ' + (tolak ? 'st-merah' : 'st-hijau');
            document.getElementById('mkIkonTolak').style.display = tolak ? '' : 'none';
            document.getElementById('mkIkonTerima').style.display = tolak ? 'none' : '';
            document.getElementById('mkJudul').textContent = tolak ? 'Tolak booking ini?' : 'Terima booking ini?';
            document.getElementById('mkIsi').textContent = 'Booking dari ' + nama + (tolak ? ' akan ditolak.' : ' akan diterima.') + ' Pilihan tidak bisa diubah lagi.';
            var ya = document.getElementById('mkYa');
            ya.textContent = tolak ? 'Ya, tolak' : 'Ya, terima';
            ya.className = 'tombol t-ya ' + (tolak ? 'merah' : 'hijau');

            document.body.appendChild(modal);
            backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            backdrop.style.background = 'transparent';
            document.body.appendChild(backdrop);
            modal.style.display = 'block';
            modal.classList.add('show');
            document.body.classList.add('modal-open');
            document.body.style.overflow = 'hidden';
        }

        function tutup() {
            modal.classList.remove('show');
            modal.style.display = 'none';
            if (backdrop) { backdrop.remove(); backdrop = null; }
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            formAktif = null; statusAktif = null;
        }

        document.querySelectorAll('.form-status .tg button').forEach(function (btn) {
            btn.addEventListener('click', function () {
                buka(btn.closest('form'), btn.dataset.status, btn.dataset.nama);
            });
        });

        modal.addEventListener('click', function (e) {
            if (e.target === modal || e.target.hasAttribute('data-tutup')) tutup();
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && formAktif) tutup();
        });

        document.getElementById('mkYa').addEventListener('click', function () {
            if (!formAktif) return;
            formAktif.querySelector('input[name="status"]').value = statusAktif;
            formAktif.submit();
        });
    })();
</script>
@endsection