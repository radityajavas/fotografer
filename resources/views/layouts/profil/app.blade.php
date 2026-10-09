
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Profil Saya') - Cocofonder</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f5f7fa;
            font-family: Arial, sans-serif;
            margin: 0;
        }

        .navbar-profile {
            background-color: #ffffff;
            border-bottom: 1px solid #e9ecef;
            padding: 12px 0;
        }

        .navbar-brand {
            font-weight: bold;
            color: #198754 !important;
            font-size: 23px;
        }

        .navbar-profile .nav-link {
            color: #333;
            font-weight: 500;
            padding: 10px 15px;
            border-radius: 8px;
        }

        .navbar-profile .nav-link:hover {
            color: #198754;
            background-color: #f0f8f3;
        }

        .navbar-profile .nav-link.active {
            color: #198754;
            background-color: #e8f5ed;
        }

        .profile-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #198754;
        }

        .profile-initial {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #198754;
            color: white;
            font-weight: bold;
        }

        .profile-dropdown {
            min-width: 220px;
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 8px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        .profile-dropdown .dropdown-item {
            padding: 10px;
            border-radius: 6px;
        }

        .profile-dropdown .dropdown-item:hover {
            background-color: #f0f8f3;
            color: #198754;
        }

        .profile-plus {
            width: 28px;
            height: 28px;
            padding: 0;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            border: 2px solid #fff;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.15);
        }

        .page-content {
            min-height: calc(100vh - 150px);
            padding: 30px 0;
        }

        .footer-profile {
            background-color: #ffffff;
            border-top: 1px solid #e9ecef;
            padding: 18px 0;
            color: #777;
            text-align: center;
        }
    </style>

    @stack('styles')
</head>

<body>

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg navbar-profile sticky-top">
        <div class="container">

            <a class="navbar-brand" href="{{ route('landing') }}">
                <i class="bi bi-camera-fill"></i>
                Cocofonder
            </a>

            <button class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarProfil"
                    aria-controls="navbarProfil"
                    aria-expanded="false"
                    aria-label="Buka navigasi">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarProfil">

                <ul class="navbar-nav ms-auto align-items-lg-center">

                    {{-- MENU HOME --}}
                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('landing') }}">
                            <i class="bi bi-house-door"></i>
                            Home
                        </a>
                    </li>

                    @auth

                        {{-- MENU PROFILE --}}
                        <li class="nav-item ms-lg-2">
                            <div class="d-flex align-items-center gap-1">

                                {{-- Klik foto/nama untuk membuka halaman profil --}}
                                <a class="nav-link d-flex align-items-center gap-2
                                    {{ request()->routeIs('profile') ? 'active' : '' }}"
                                   href="{{ route('profile') }}">

                                    @if (auth()->user()->photo)
                                        <img
                                            src="{{ asset('storage/' . auth()->user()->photo) }}"
                                            alt="Foto Profil"
                                            class="profile-avatar">
                                    @else
                                        <span class="profile-initial">
                                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                                        </span>
                                    @endif

                                    <span>Profile</span>
                                </a>

                                {{-- FORM GANTI FOTO --}}
                                <form
                                    action="{{ route('profile.photo') }}"
                                    method="POST"
                                    enctype="multipart/form-data"
                                    id="layoutPhotoForm">

                                    @csrf

                                    <input
                                        type="file"
                                        name="photo"
                                        id="layoutPhotoInput"
                                        accept="image/jpeg,image/png,image/webp"
                                        class="d-none"
                                        onchange="submitProfilePhoto()">

                                </form>

                            </div>
                        </li>

                        {{-- DROPDOWN AKUN --}}
                        <li class="nav-item dropdown ms-lg-2">
                            <a class="nav-link dropdown-toggle"
                               href="#"
                               id="accountDropdown"
                               role="button"
                               data-bs-toggle="dropdown"
                               aria-expanded="false">
                                {{ auth()->user()->name }}
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end profile-dropdown"
                                aria-labelledby="accountDropdown">

                                <li class="px-3 py-2">
                                    <div class="fw-semibold">
                                        {{ auth()->user()->name }}
                                    </div>

                                    <small class="text-muted">
                                        {{ auth()->user()->email }}
                                    </small>
                                </li>

                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <li>
                                    <a class="dropdown-item"
                                       href="{{ route('profile') }}">
                                        <i class="bi bi-person me-2"></i>
                                        Profil Saya
                                    </a>
                                </li>

                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf

                                        <button type="submit"
                                                class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i>
                                            Logout
                                        </button>
                                    </form>
                                </li>

                            </ul>
                        </li>

                    @else

                        {{-- MENU PENGUNJUNG --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                Login
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                Register
                            </a>
                        </li>

                    @endauth

                </ul>
            </div>
        </div>
    </nav>

    {{-- NOTIFIKASI --}}
    @if (session('success'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show"
                 role="alert">

                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Tutup"></button>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show"
                 role="alert">

                {{ session('error') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Tutup"></button>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show"
                 role="alert">

                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Tutup"></button>
            </div>
        </div>
    @endif

    {{-- KONTEN HALAMAN --}}
    <main class="page-content">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="footer-profile">
        <div class="container">
            <small>
                &copy; {{ date('Y') }} Cocofonder. All rights reserved.
            </small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function submitProfilePhoto() {
            const input = document.getElementById('layoutPhotoInput');
            const form = document.getElementById('layoutPhotoForm');

            if (!input.files || !input.files.length) {
                return;
            }

            const file = input.files[0];
            const allowedTypes = [
                'image/jpeg',
                'image/png',
                'image/webp'
            ];

            if (!allowedTypes.includes(file.type)) {
                alert('Pilih foto berformat JPG, PNG, atau WEBP.');
                input.value = '';
                return;
            }

            form.submit();
        }
    </script>

    @stack('scripts')

</body>
</html>
