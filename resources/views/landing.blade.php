@extends('layouts.app')

@section('content')

<h1 class="fw-bold mb-1" style="letter-spacing:-0.02em;">Temukan fotografer untuk momenmu</h1>
<p class="text-secondary mb-4">Pilih yang sedang tersedia, lihat spesialisasinya, lalu buat pesanan.</p>

<!-- Bar pencarian terpadu -->
<form action="{{ route('fotografer.cari') }}" method="GET" class="search-bar mb-4">
    <input type="text" name="lokasi" value="{{ request('lokasi') }}" placeholder="Lokasi, mis. Malang">
    <select name="kategori">
        <option value="">Semua kategori</option>
        <option value="wedding">Wedding / Prewedding</option>
        <option value="portrait">Portrait / Modeling</option>
        <option value="event">Event / Konser</option>
        <option value="product">Produk UMKM</option>
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
                    <th>Rating</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($photographers as $item)
                    @php
                        $rating = $item->rating ?? null;
                        $chips  = array_filter(array_map('trim', explode(',', $item->specialization ?? '')));
                        $foto   = $item->photo ?? null;
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
                                    <div class="small text-secondary">{{ $item->phone }}</div>
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
                            @if ($rating)
                                <span class="stars">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span class="{{ $i <= round($rating) ? '' : 'off' }}">★</span>
                                    @endfor
                                </span>
                                <span class="small text-secondary ms-1">{{ number_format($rating, 1) }}</span>
                            @else
                                <span class="small text-secondary">Belum ada rating</span>
                            @endif
                        </td>

                        <td>
                            @if ($item->status == 'AVAILABLE')
                                <span class="badge rounded-pill text-bg-success fw-medium">Tersedia</span>
                            @else
                                <span class="badge rounded-pill text-bg-secondary fw-medium">Tidak tersedia</span>
                            @endif
                        </td>

                        <td class="text-end">
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

@endsection