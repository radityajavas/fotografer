@extends('layouts.app')

@section('content')
<style>
    /* Halaman paket: memakai warna & font brand yang sama dengan beranda */
    .paket-hero {
        background: linear-gradient(135deg, #0f766e 0%, #115e59 60%, #134e4a 100%);
        border-radius: 18px;
        color: #fff;
        padding: 34px 32px;
        margin-bottom: 28px;
    }
    .paket-hero h1 {
        font-size: 1.7rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        margin: 0 0 6px;
    }
    .paket-hero p {
        margin: 0;
        color: rgba(255, 255, 255, .82);
        max-width: 620px;
    }
    .paket-card {
        background: #fff;
        border: 1px solid #e3e7e5;
        border-radius: 16px;
        padding: 22px;
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: transform .15s, box-shadow .15s;
    }
    .paket-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 26px rgba(15, 118, 110, .12);
    }
    .paket-card h2 {
        font-size: 1.1rem;
        font-weight: 700;
        margin: 0 0 10px;
        color: #1c2321;
    }
    .paket-harga {
        font-size: 1.35rem;
        font-weight: 800;
        color: #0f766e;
        line-height: 1.2;
    }
    .paket-durasi {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 8px;
        background: #e6f4f1;
        color: #0b5a54;
        font-size: .8rem;
        font-weight: 600;
        padding: .25rem .7rem;
        border-radius: 999px;
    }
    .paket-deskripsi {
        margin: 14px 0 18px;
        color: #5b6560;
        font-size: .92rem;
        line-height: 1.6;
    }
    .paket-card .btn {
        margin-top: auto;
    }
    @media (max-width: 575.98px) {
        .paket-hero { padding: 26px 22px; }
        .paket-hero h1 { font-size: 1.4rem; }
    }
</style>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0 small">
        <li class="breadcrumb-item"><a href="{{ route('landing') }}" class="btn btn-brand btn-sm px-3 text-decoration-none">Beranda</a></li>
        <li class="breadcrumb-item active" aria-current="page">Paket Fotografi</li>
    </ol>
</nav>

<div class="paket-hero">
    <h1>Paket Fotografi</h1>
    <p>Pilih paket yang paling sesuai dengan momenmu. Setiap paket sudah mencakup durasi pemotretan
        sesuai keterangan, dan kamu bisa memilih fotografer saat melanjutkan pemesanan.</p>
</div>

<div class="row g-4">
    @forelse ($packages as $package)
        <div class="col-md-6 col-lg-4">
            <div class="paket-card">
                <h2>{{ $package->name }}</h2>

                <div class="paket-harga">
                    Rp {{ number_format($package->price, 0, ',', '.') }}
                </div>

                <span class="paket-durasi">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="9"></circle>
                        <path d="M12 7v5l3 2"></path>
                    </svg>
                    Durasi {{ $package->duration_hours }} jam
                </span>

                <p class="paket-deskripsi">{{ $package->description }}</p>

                <button type="button"
                        class="btn btn-brand w-100"
                        data-buka-pesan
                        data-paket-id="{{ $package->id }}"
                        data-paket-nama="{{ $package->name }}"
                        data-paket-info="Rp {{ number_format($package->price, 0, ',', '.') }} • {{ $package->duration_hours }} jam">
                    Pesan Sekarang
                </button>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-light border mb-0 text-center py-4">
                Belum ada paket fotografi yang tersedia.
            </div>
        </div>
    @endforelse
</div>

{{-- Modal pilih fotografer + tombol Pesan Sekarang (sama seperti di beranda) --}}
@include('partials.pesan-paket')
@endsection
