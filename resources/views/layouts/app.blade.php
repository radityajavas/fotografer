<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cocofonder</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Tambahan: library untuk crop foto --}}
    <link href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.css" rel="stylesheet">

    <style>
        :root {
            --brand: #0f766e;
            --brand-dark: #0b5a54;
            --brand-soft: #e6f4f1;
            --ink: #1c2321;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            background: #f6f7f6;
            color: var(--ink);
        }

        /* Tombol */
        .btn-brand {
            background: var(--brand);
            color: #fff;
            border: 1px solid var(--brand);
            font-weight: 600;
            transition: background .15s, transform .15s, box-shadow .15s;
        }

        .btn-brand:hover {
            background: var(--brand-dark);
            border-color: var(--brand-dark);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(15, 118, 110, .3);
        }

        .btn-brand:active {
            transform: translateY(0);
            box-shadow: none;
        }

        .btn-brand-outline {
            background: transparent;
            color: var(--brand);
            border: 1.5px solid var(--brand);
            font-weight: 600;
            transition: background .15s, color .15s;
        }

        .btn-brand-outline:hover {
            background: var(--brand);
            color: #fff;
        }

        /* Header */
        .site-nav {
            background: #fff;
            border-bottom: 1px solid #e3e7e5;
        }

        .logo {
            display: inline-flex;
            align-items: center;
            gap: .55rem;
            text-decoration: none;
            color: var(--ink);
            font-size: 1.25rem;
            letter-spacing: -0.02em;
        }

        .logo-icon {
            width: 2rem;
            height: 2rem;
            background: var(--brand);
            border-radius: .55rem;
            display: grid;
            place-items: center;
        }

        .logo b {
            font-weight: 800;
        }

        .logo span {
            font-weight: 400;
            color: var(--brand);
        }

        .site-nav .nav-link {
            font-weight: 500;
            color: #4a5551;
        }

        .site-nav .nav-link:hover {
            color: var(--brand);
        }

        /* Bar pencarian terpadu */
        .search-bar {
            display: flex;
            align-items: stretch;
            background: #fff;
            border: 1px solid #d5dbd8;
            border-radius: .75rem;
            overflow: hidden;
        }

        .search-bar:focus-within {
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(15, 118, 110, .15);
        }

        .search-bar input,
        .search-bar select {
            border: 0;
            outline: 0;
            background: transparent;
            padding: .8rem 1rem;
            font: inherit;
            font-size: .95rem;
        }

        .search-bar input {
            flex: 1 1 45%;
            min-width: 0;
        }

        .search-bar select {
            flex: 1 1 30%;
            border-left: 1px solid #e3e7e5;
        }

        .search-bar button {
            border-radius: 0;
            padding: 0 1.75rem;
        }

        @media (max-width: 767px) {
            .search-bar {
                flex-direction: column;
            }

            .search-bar select {
                border-left: 0;
                border-top: 1px solid #e3e7e5;
            }

            .search-bar button {
                padding: .8rem;
            }
        }

        /* Avatar, chip, rating */
        .avatar {
            width: 2.6rem;
            height: 2.6rem;
            border-radius: 50%;
            object-fit: cover;
            flex: none;
        }

        .avatar-fallback {
            background: var(--brand-soft);
            color: var(--brand);
            font-weight: 700;
            display: grid;
            place-items: center;
            text-transform: uppercase;
            font-size: .85rem;
        }

        .chip {
            display: inline-block;
            background: #eef1ef;
            color: #45504c;
            font-size: .75rem;
            font-weight: 500;
            padding: .2rem .6rem;
            border-radius: 999px;
            margin: .1rem .15rem .1rem 0;
        }

        .stars {
            color: #d99a1c;
            letter-spacing: 1px;
            white-space: nowrap;
        }

        .stars .off {
            color: #d4d9d6;
        }

        .table-wrap {
            background: #fff;
            border: 1px solid #e3e7e5;
            border-radius: .75rem;
            overflow: hidden;
        }

        .table> :not(caption)>*>* {
            padding: .9rem 1rem;
        }

        .table thead th {
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #6b7672;
            font-weight: 600;
            background: #fafbfa;
        }

        .table-hover>tbody>tr:hover>* {
            background: var(--brand-soft);
        }

        .site-footer {
            color: #7a8580;
            font-size: .8rem;
        }

        /* Tambahan: avatar foto profil pelanggan */
        .profile-avatar {
            width: 2.6rem;
            height: 2.6rem;
            border-radius: 50%;
            position: relative;
            display: block;
            cursor: pointer;
        }

        .profile-avatar img,
        .profile-avatar .avatar-fallback {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        /* Tambahan: lingkaran hijau kecil untuk tanda tambah */
        .profile-plus {
            position: absolute;
            right: -2px;
            bottom: -2px;
            width: 13px;
            height: 13px;
            border-radius: 50%;
            background: var(--brand);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1.5px solid white;
            padding: 0;
        }

        .profile-plus::before,
        .profile-plus::after {
            content: "";
            position: absolute;
            background: white;
            border-radius: 1px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .profile-plus::before {
            width: 6px;
            height: 1.5px;
        }

        .profile-plus::after {
            width: 1.5px;
            height: 6px;
        }

        /* Tambahan: tampilan area crop foto */
        .crop-container {
            max-width: 100%;
            max-height: 400px;
            overflow: hidden;
        }

        .crop-container img {
            display: block;
            max-width: 100%;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-md site-nav">
        <div class="container">
            <a class="logo" href="/">
                <span class="logo-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 8h3l2-2.5h6L17 8h3a1 1 0 0 1 1 1v9a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1z" />
                        <circle cx="12" cy="13" r="3.5" />
                    </svg>
                </span>
                <div><b>Coco</b><span>fonder</span></div>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="menu">
                <ul class="navbar-nav ms-md-4 me-auto">
                    <li class="nav-item"><a class="nav-link" href="/">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('bookings.my') }}">Pesanan Saya</a></li>
                </ul>

                <div class="d-flex align-items-center gap-2 mt-3 mt-md-0">
                    @guest
                    <a href="{{ route('login') }}" class="btn btn-brand-outline px-4">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-brand px-4">Daftar</a>
                    @else

                    {{-- Form upload foto profil --}}
                    <form action="{{ route('profile.photo') }}" method="POST" enctype="multipart/form-data" class="m-0">
                        @csrf

                        {{-- Label dibuat seperti tombol avatar.
             Jadi saat avatar diklik, file foto akan dipilih. --}}
                        <label for="profile-photo" class="profile-avatar">

                            @if (auth()->user()->photo)
                            {{-- Kalau sudah punya foto, tampilkan foto tersebut --}}
                            <img
                                src="{{ asset('storage/' . auth()->user()->photo) }}"
                                alt="Foto Profil">
                            @else
                            {{-- Kalau belum punya foto, tampilkan inisial nama --}}
                            <div class="avatar-fallback">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                            @endif

                            {{-- Tambahan: tombol + untuk memilih foto --}}
                            <span class="profile-plus"></span>

                        </label>

                        {{-- Input file disembunyikan karena user cukup klik avatar --}}
                        <input
                            type="file"
                            name="photo"
                            id="profile-photo"
                            accept="image/*"
                            style="display: none;">

                    </form>

                    {{-- Nama user yang sedang login --}}
                    <span class="small fw-medium me-2">
                        {{ auth()->user()->name }}
                    </span>

                    {{-- Tombol logout --}}
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button class="btn btn-brand-outline btn-sm px-3">
                            Keluar
                        </button>
                    </form>

                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <main class="container py-4 py-md-5">
        @yield('content')
    </main>

    <footer class="site-footer text-center pb-4">&copy; 2026 Cocofonder</footer>

    {{-- Tambahan: modal untuk crop foto --}}
    <div class="modal fade" id="cropModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Atur Foto Profil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="crop-container">
                        <img id="crop-image" src="" alt="Crop Foto">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="button" class="btn btn-brand" id="save-crop">
                        Simpan
                    </button>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Tambahan: library untuk menjalankan crop foto --}}
    <script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.js"></script>

    {{-- Tambahan: proses pilih foto, crop, lalu kirim ke Laravel --}}
    <script>
        let cropper;

        const photoInput = document.getElementById('profile-photo');
        const cropImage = document.getElementById('crop-image');
        const cropModal = new bootstrap.Modal(document.getElementById('cropModal'));

        photoInput.addEventListener('change', function (event) {

            const file = event.target.files[0];

            if (!file) {
                return;
            }

            const imageUrl = URL.createObjectURL(file);

            cropImage.src = imageUrl;

            cropModal.show();

            cropImage.onload = function () {

                if (cropper) {
                    cropper.destroy();
                }

                cropper = new Cropper(cropImage, {
                    aspectRatio: 1,
                    viewMode: 1,
                    dragMode: 'move',
                    autoCropArea: 1,
                    responsive: true,
                    background: false,
                });

            };

        });

        document.getElementById('save-crop').addEventListener('click', function () {

            if (!cropper) {
                return;
            }

            cropper.getCroppedCanvas({
                width: 500,
                height: 500,
                imageSmoothingQuality: 'high'
            }).toBlob(function (blob) {

                const file = new File([blob], 'profile.jpg', {
                    type: 'image/jpeg'
                });

                const dataTransfer = new DataTransfer();

                dataTransfer.items.add(file);

                photoInput.files = dataTransfer.files;

                cropModal.hide();

                photoInput.closest('form').submit();

            }, 'image/jpeg', 0.9);

        });

        document.getElementById('cropModal').addEventListener('hidden.bs.modal', function () {

            if (cropper) {
                cropper.destroy();
                cropper = null;
            }

        });
    </script>

</body>

</html>