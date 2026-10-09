{{--
  Pesan siap pilih. Klik salah satu untuk mengisi kolom pesan (masih bisa diedit sebelum dikirim).
  Pemakaian: @include('chat._quick_replies', ['replies' => [...]])
--}}
<div class="mb-2 d-flex flex-wrap gap-2" id="quick-replies">
  @foreach ($replies as $r)
    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill"
            data-quick-reply="{{ $r }}">{{ $r }}</button>
  @endforeach
</div>

<script>
  document.getElementById('quick-replies').addEventListener('click', function (e) {
    var btn = e.target.closest('[data-quick-reply]');
    if (!btn) return;
    var input = document.querySelector('input[name="message"]');
    input.value = btn.getAttribute('data-quick-reply');
    input.focus();
  });
</script>
