@extends('layouts.app')

@section('content')

<h1 class="fw-bold mb-1" style="letter-spacing:-0.02em;">Temukan fotografer untuk momenmu</h1>
<p class="text-secondary mb-4">Pilih kota dan kategori, lihat fotografer yang tersedia, lalu buat pesanan.</p>

<form action="{{ route('fotografer.cari') }}" method="GET" class="search-bar mb-4">
    <input type="text" name="lokasi" value="{{ request('lokasi') }}" placeholder="Cari lokasi, mis. Malang">
    <select name="kategori">
        <option value="">Semua kategori</option>
        @foreach ($categories as $cat)
            <option value="{{ $cat }}" @selected(request('kategori') == $cat)>
                {{ \Illuminate\Support\Str::ucfirst($cat) }}
            </option>
        @endforeach
    </select>
    <button type="submit" class="btn btn-brand">Cari</button>
</form>

<div class="table-wrap">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Fotografer</th>
                    <th>Spesialisasi</th>
                    <th>Hasil Foto</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($photographers as $item)
                    @php
                        $chips = array_filter(array_map('trim', explode(',', $item->specialization ?? '')));
                        $foto  = $item->photo ?? null;
                        $hasil = $item->photos->take(3);
                        $info  = trim(($item->specialization ?? '') . ($item->city ? ' • ' . $item->city : ''));
                    @endphp
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @if ($foto)
                                    <img src="{{ asset('storage/' . $foto) }}" alt="{{ $item->name }}" class="avatar">
                                @else
                                    <div class="avatar avatar-fallback">{{ substr($item->name, 0, 2) }}</div>
                                @endif
                                <div>
                                    <div class="fw-semibold">{{ $item->name }}</div>
                                    <div class="small text-secondary">
                                        {{ $item->phone }}@if ($item->city) &bull; {{ $item->city }}@endif
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td>
                            @forelse ($chips as $c)
                                <span class="chip">{{ $c }}</span>
                            @empty
                                <span class="text-secondary small">-</span>
                            @endforelse
                        </td>

                        <td>
                            @if ($hasil->isEmpty())
                                <span class="text-secondary small">Belum ada foto</span>
                            @else
                                <button type="button" class="border-0 bg-transparent p-0 d-flex align-items-center"
                                        data-galeri="{{ $item->id }}" data-nama="{{ $item->name }}" data-info="{{ $info }}">
                                    @foreach ($hasil as $p)
                                        <img src="{{ $p->url }}" alt="Hasil foto {{ $item->name }}"
                                             style="width:52px;height:52px;object-fit:cover;border-radius:6px;margin-right:6px">
                                    @endforeach
                                    @if ($item->photos->count() > 3)
                                        <span class="small text-secondary">+{{ $item->photos->count() - 3 }}</span>
                                    @endif
                                </button>
                            @endif
                        </td>

                        <td>
                            @if ($item->status == 'AVAILABLE')
                                <span class="badge rounded-pill text-bg-success fw-medium">Tersedia</span>
                            @else
                                <span class="badge rounded-pill text-bg-secondary fw-medium">Tidak tersedia</span>
                            @endif
                        </td>

                        <td class="text-end" style="white-space:nowrap">
                            @if ($item->photos->isNotEmpty())
                                <button type="button" class="btn btn-outline-secondary btn-sm px-3"
                                        data-galeri="{{ $item->id }}" data-nama="{{ $item->name }}" data-info="{{ $info }}">Lihat foto</button>
                            @endif
                            @if ($item->status == 'AVAILABLE')
                                <a href="{{ route('booking.create', ['photographer_id' => $item->id]) }}"
                                   class="btn btn-brand btn-sm px-3">Pesan</a>
                            @else
                                <button class="btn btn-outline-secondary btn-sm px-3" disabled>Pesan</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-secondary py-5">Belum ada fotografer.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="row g-3 mt-3">
    @foreach ($packages as $package)
        <div class="col-md-4">
            <div class="card p-3 h-100">
                <h5 class="fw-bold">{{ $package->name }}</h5>
                <p class="text-success fw-bold mb-1">
                    Rp {{ number_format($package->price, 0, ',', '.') }} 
                    <span class="text-muted fw-normal">({{ $package->duration_hours }} jam)</span>
                </p>
                <p class="card-text text-muted mb-0">
                    <strong>DESKRIPSI:</strong> {{ $package->description }}
                </p>
            </div>
        </div>
        @endforeach
</div>

{{-- isi galeri tiap fotografer, dipakai oleh popup --}}
@foreach ($photographers as $item)
    @if ($item->photos->isNotEmpty())
        <template id="galeri-{{ $item->id }}">
            <div class="row g-2">
                @foreach ($item->photos as $p)
                    <div class="col-6 col-md-4">
                        <a href="{{ $p->url }}" target="_blank">
                            <img src="{{ $p->url }}" alt="Hasil foto {{ $item->name }}"
                                 style="width:100%;height:180px;object-fit:cover;border-radius:6px">
                        </a>
                        @if ($p->kategori)
                            <div class="small text-secondary mt-1">{{ $p->kategori }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        </template>
    @endif
@endforeach

<div class="modal fade" id="modalGaleri" tabindex="-1" role="dialog" aria-hidden="true" style="background: rgba(0,0,0,.4)">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="galeriJudul"></h5>
                    <div class="small text-secondary" id="galeriInfo"></div>
                </div>
                <button type="button" class="btn-close" data-tutup aria-label="Tutup"></button>
            </div>
            <div class="modal-body" id="galeriIsi"></div>
        </div>
    </div>
</div>

<script>
    (function () {
        var modal = document.getElementById('modalGaleri');
        var isi = document.getElementById('galeriIsi');
        var backdrop = null;

        function buka(btn) {
            var tpl = document.getElementById('galeri-' + btn.dataset.galeri);
            if (!tpl) return;
            document.getElementById('galeriJudul').textContent = btn.dataset.nama;
            document.getElementById('galeriInfo').textContent = btn.dataset.info || '';
            isi.innerHTML = '';
            isi.appendChild(tpl.content.cloneNode(true));

            document.body.appendChild(modal);
            backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            backdrop.style.background = 'transparent';
            document.body.appendChild(backdrop);
            modal.style.display = 'block';
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function tutup() {
            modal.classList.remove('show');
            modal.style.display = 'none';
            if (backdrop) { backdrop.remove(); backdrop = null; }
            document.body.style.overflow = '';
        }

        document.querySelectorAll('[data-galeri]').forEach(function (b) {
            b.addEventListener('click', function () { buka(b); });
        });
        modal.addEventListener('click', function (e) {
            if (e.target === modal || e.target.hasAttribute('data-tutup')) tutup();
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && backdrop) tutup();
        });
    })();
</script>

@endsection