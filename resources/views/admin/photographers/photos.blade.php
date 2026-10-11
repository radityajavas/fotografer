@extends('layouts.admin')

@section('title', 'Hasil Foto - ' . $photographer->name)

@section('content')
<style>
    .ph-foto { position: relative; border-radius: 8px; overflow: hidden; border: 1px solid #e5e7eb; background: #fff; }
    .ph-foto img { display: block; width: 100%; height: 170px; object-fit: cover; }
    .ph-kat { position: absolute; left: 8px; bottom: 8px; padding: 2px 8px; border-radius: 4px; font-size: 11px; background: rgba(17, 17, 17, .75); color: #fff; }
    .ph-foto button { position: absolute; top: 8px; right: 8px; border: 0; border-radius: 4px; padding: 3px 9px; font-size: 12px; background: rgba(220, 38, 38, .92); color: #fff; cursor: pointer; }
    #modalHapus { background: rgba(24, 36, 51, .35); }
    #modalHapus .modal-content { border: 0; border-radius: 6px; }
</style>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h5 class="mb-1">{{ $photographer->name }}</h5>
                <div class="text-muted small">
                    {{ $photographer->specialization }} &middot; {{ implode(', ', $photographer->serviceAreas()) ?: '-' }} &middot; {{ $photographer->phone }}
                </div>
            </div>
            <a href="{{ route('admin.photographers.index') }}" class="btn btn-sm btn-outline-secondary">Kembali</a>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <h6 class="mb-3">Tambah hasil foto</h6>
        <form method="POST" action="{{ route('admin.photographers.photos.store', $photographer->id) }}" enctype="multipart/form-data">
            @csrf
            <input type="file" name="photos[]" accept=".jpg,.jpeg,.png,.webp" multiple required class="form-control mb-2">
            <input type="text" name="kategori" list="daftar-kategori" maxlength="50" value="{{ old('kategori') }}"
                   class="form-control mb-2" placeholder="Kategori, mis. Wisuda (boleh dikosongkan)">
            <datalist id="daftar-kategori">
                <option value="Wisuda"><option value="Wedding"><option value="Prewedding">
                <option value="Produk"><option value="Event"><option value="Portrait">
            </datalist>
            <div class="text-muted small mb-3">Maksimal 10 foto sekali upload, tiap foto 2 MB (jpg, png, webp).</div>
            <button type="submit" class="btn btn-primary btn-sm">Upload</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h6 class="mb-3">Hasil foto ({{ $photographer->photos->count() }})</h6>

        @if ($photographer->photos->isEmpty())
            <div class="text-muted">Belum ada foto.</div>
        @else
            <div class="row">
                @foreach ($photographer->photos as $photo)
                    <div class="col-6 col-md-3 mb-3">
                        <div class="ph-foto">
                            <img src="{{ $photo->url }}" alt="Hasil foto">
                            @if ($photo->kategori)
                                <span class="ph-kat">{{ $photo->kategori }}</span>
                            @endif
                            <form method="POST" action="{{ route('admin.photos.destroy', $photo->id) }}" class="form-hapus">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn-hapus">Hapus</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<div class="modal fade" id="modalHapus" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body text-center" style="padding: 24px">
                <h6 class="mb-2">Hapus foto ini?</h6>
                <div class="text-muted small mb-3">Foto yang dihapus tidak bisa dikembalikan.</div>
                <button type="button" class="btn btn-sm btn-outline-secondary" data-tutup>Batal</button>
                <button type="button" class="btn btn-sm btn-danger" id="ya-hapus">Ya, hapus</button>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        var modal = document.getElementById('modalHapus');
        var formAktif = null, backdrop = null;

        function tutup() {
            modal.classList.remove('show');
            modal.style.display = 'none';
            if (backdrop) { backdrop.remove(); backdrop = null; }
            formAktif = null;
        }

        document.querySelectorAll('.btn-hapus').forEach(function (btn) {
            btn.addEventListener('click', function () {
                formAktif = btn.closest('form');
                document.body.appendChild(modal);
                backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop fade show';
                backdrop.style.background = 'transparent';
                document.body.appendChild(backdrop);
                modal.style.display = 'block';
                modal.classList.add('show');
            });
        });

        modal.addEventListener('click', function (e) {
            if (e.target === modal || e.target.hasAttribute('data-tutup')) tutup();
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && formAktif) tutup();
        });
        document.getElementById('ya-hapus').addEventListener('click', function () {
            if (formAktif) formAktif.submit();
        });
    })();
</script>
@endsection