{{--
  Modal pilih fotografer + JavaScript untuk tombol "Pesan Sekarang".
  Dipakai di beranda dan halaman /paket agar alurnya sama.
  Variabel yang dibutuhkan: $photographers
--}}
<div class="modal fade" id="modalPesan" tabindex="-1" aria-labelledby="modalPesanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content border-0 shadow" method="GET" action="{{ route('booking.create') }}" style="border-radius: 12px;">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title fw-bold" id="modalPesanLabel">Pesan paket</h5>
                    <div class="small text-secondary" id="pesanPaketInfo"></div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Paket pilihan: <strong id="pesanPaketNama"></strong></p>
                <label for="pesanFotografer" class="form-label fw-semibold">Fotografer</label>
                <select name="photographer_id" id="pesanFotografer" class="form-select" required>
                    <option value="">— Pilih fotografer —</option>
                    @foreach ($photographers->where('status', 'AVAILABLE') as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}@if ($item->city) — {{ $item->city }}@endif</option>
                    @endforeach
                </select>
                <p class="small text-secondary mt-2 mb-0">
                    Fotografer harus dipilih sebelum pemesanan dilanjutkan.
                </p>
                <input type="hidden" name="package_id" id="pesanPaketId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-brand">Lanjut Pesan</button>
            </div>
        </form>
    </div>
</div>

<script>
    (function () {
        // Ingat fotografer yang pernah dipilih agar tidak hilang saat memilih paket
        document.querySelectorAll('[data-fotografer-id]').forEach(function (el) {
            el.addEventListener('click', function () {
                try { localStorage.setItem('cocofonder_fotografer', el.dataset.fotograferId); } catch (e) {}
            });
        });

        // Tombol "Pesan Sekarang": bawa ID paket ke form booking lewat query string
        document.querySelectorAll('[data-buka-pesan]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                document.getElementById('pesanPaketId').value = btn.dataset.paketId;
                document.getElementById('pesanPaketNama').textContent = btn.dataset.paketNama;
                document.getElementById('pesanPaketInfo').textContent = btn.dataset.paketInfo;

                var select = document.getElementById('pesanFotografer');
                var terakhir = null;
                try { terakhir = localStorage.getItem('cocofonder_fotografer'); } catch (e) {}
                if (terakhir && select.querySelector('option[value="' + terakhir + '"]')) {
                    select.value = terakhir;
                }

                bootstrap.Modal.getOrCreateInstance(document.getElementById('modalPesan')).show();
            });
        });
    })();
</script>
