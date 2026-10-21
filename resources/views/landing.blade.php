@extends('layouts.app')

@section('content')
<style>
    body {
        margin: 0; 
        min-height: 100vh;
        font-family: 'Plus Jakarta Sans', system-ui, sans-serif; 
        color: #1f2937;
        /* Background dibuat lebih gelap/redup agar tidak terlalu silau */
        background: linear-gradient(rgba(15, 23, 42, 0.75), rgba(15, 23, 42, 0.75)), url('{{ asset('images/jepret.jpg') }}') center / cover fixed;
    }
    .search-bar {
        background: #ffffff;
        padding: 16px;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        display: flex;
        gap: 12px;
        align-items: center;
    }
    .search-bar input, .search-bar select {
        flex: 1;
        padding: 10px 14px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        outline: none;
        font-size: 14px;
    }
    .btn-brand {
        background: #0d9488;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        border: none;
        font-weight: 600;
    }
    .table-wrap {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        overflow: hidden;
        border: 1px solid #e5e7eb;
    }
    .avatar {
        width: 48px;
        height: 48px;
        object-fit: cover;
        border-radius: 50%;
    }
    .avatar-fallback {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #ccfbf1;
        color: #0f766e;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 16px;
    }
    .chip {
        background: #f1f5f9;
        color: #475569;
        font-size: 11px;
        padding: 3px 8px;
        border-radius: 6px;
        font-weight: 500;
        display: inline-block;
        margin-right: 3px;
        margin-bottom: 3px;
    }
</style>
    
<div class="container py-4" style="max-width: 1200px;">
    <h1 class="fw-bold mb-1 text-white" style="letter-spacing:-0.02em;">Temukan fotografer untuk momenmu</h1>
    <p class="text-light opacity-75 mb-4">Pilih kota dan kategori, lihat fotografer yang tersedia, lalu buat pesanan.</p>

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

    <div class="table-wrap mb-5">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="py-3 ps-3">Fotografer</th>
                        <th class="py-3">Spesialisasi</th>
                        <th class="py-3">Hasil Foto</th>
                        <th class="py-3">Status</th>
                        <th class="py-3 pe-3 text-end">Aksi</th>
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
                            <td class="ps-3 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    @if ($foto)
                                        <img src="{{ asset('storage/' . $foto) }}" alt="{{ $item->name }}" class="avatar">
                                    @else
                                        <div class="avatar-fallback">{{ substr($item->name, 0, 2) }}</div>
                                    @endif
                                    <div>
                                        <div class="fw-semibold text-dark">{{ $item->name }}</div>
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
                                                 style="width:48px;height:48px;object-fit:cover;border-radius:6px;margin-right:4px">
                                        @endforeach
                                        @if ($item->photos->count() > 3)
                                            <span class="small text-muted fw-bold ms-1">+{{ $item->photos->count() - 3 }}</span>
                                        @endif
                                    </button>
                                @endif
                            </td>

                            <td>
                                @if ($item->status == 'AVAILABLE')
                                    <span class="badge rounded-pill bg-success-lt text-success fw-medium px-2 py-1">Tersedia</span>
                                @else
                                    <span class="badge rounded-pill bg-secondary-lt text-secondary fw-medium px-2 py-1">Tidak tersedia</span>
                                @endif
                            </td>

                            <td class="text-end pe-3" style="white-space:nowrap">
                                @if ($item->status == 'AVAILABLE')
                                    <a href="{{ route('booking.create', ['photographer_id' => $item->id]) }}"
                                       class="btn btn-brand btn-sm px-3 py-1">Pesan</a>
                                @else
                                    <button class="btn btn-outline-secondary btn-sm px-3 py-1" disabled>Pesan</button>
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

    <!-- PAKET -->
    <div class="row g-3 mb-4">
        @foreach ($packages as $package)
            <div class="col-md-4">
                <div class="card p-3 h-100 border-0 shadow-sm" style="border-radius: 12px;">
                    <h5 class="fw-bold text-dark">{{ $package->name }}</h5>
                    <p class="fw-bold mb-1" style="color: #0d9488;">
                        Rp {{ number_format($package->price, 0, ',', '.') }} 
                        <span class="text-muted fw-normal small">({{ $package->duration_hours ?? 'Sesi' }} jam)</span>
                    </p>
                    <p class="card-text text-secondary small mb-0">
                        <strong>DESKRIPSI:</strong> {{ $package->description }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
</div>

@foreach ($photographers as $item)
    @if ($item->photos->isNotEmpty())
        <template id="galeri-{{ $item->id }}">
    <div style="display: flex; gap: 20px; overflow-x: auto; padding-bottom: 12px; scrollbar-width: thin; width: 100%;">
        @foreach ($item->photos as $p)
            <div style="flex: 0 0 280px; width: 280px; background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                <div onclick="openModal('{{ $p->url }}')" style="cursor: pointer; position: relative; height: 260px; background: #f9fafb; display: flex; align-items: center; justify-content: center;">
                    <img src="{{ $p->url }}" alt="Hasil foto {{ $item->name }}"
                         style="max-width: 100%; max-height: 100%; object-fit: contain;">
                    
                    @if ($p->kategori)
                        <span style="position: absolute; top: 12px; left: 12px; background: rgba(17, 24, 39, 0.75); color: #fff; font-size: 11px; font-weight: bold; padding: 3px 8px; border-radius: 6px;">
                            {{ $p->kategori }}
                        </span>
                    @endif
                </div>
                <div style="padding: 12px 16px; border-top: 1px solid #f3f4f6; font-size: 13px; font-weight: 600; color: #374151;">
                    {{ $p->kategori ?: 'Portofolio' }}
                </div>
            </div>
        @endforeach
    </div>
</template>
    @endif
@endforeach

<div class="modal fade" id="modalGaleri" tabindex="-1" role="dialog" aria-hidden="true" style="background: rgba(0,0,0,.4)">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content border-0 shadow" style="border-radius: 12px;">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold" id="galeriJudul"></h5>
                    <div class="small text-secondary" id="galeriInfo"></div>
                </div>
                <button type="button" class="btn-close" data-tutup aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-4" id="galeriIsi"></div>
        </div>
    </div>
</div>

<div id="imageModal" onclick="closeModal()" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.85); z-index: 10600; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <button type="button" onclick="closeModal()" 
            style="position: absolute; top: 20px; right: 25px; background: rgba(255,255,255,0.25); color: white; border: none; width: 44px; height: 44px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: bold; line-height: 1; padding: 0; box-shadow: 0 2px 8px rgba(0,0,0,0.3); transition: background 0.2s;"
            onmouseover="this.style.background='rgba(255,255,255,0.4)'" 
            onmouseout="this.style.background='rgba(255,255,255,0.25)'">
        ✕
    </button>
    <img id="modalImg" src="" onclick="event.stopPropagation()" style="max-width: 90%; max-height: 85vh; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.5); object-fit: contain;">
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

    function openModal(imgUrl) {
        document.getElementById('modalImg').src = imgUrl;
        document.getElementById('imageModal').style.display = 'flex';
    }
    function closeModal() {
        document.getElementById('imageModal').style.display = 'none';
    }
</script>
@endsection