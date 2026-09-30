<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Admin') - {{ config('app.name') }}</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css">
  @stack('styles')
</head>
<body>
  <div class="page">
    @php
      $menus = [
        ['route' => 'admin.dashboard',           'pattern' => 'admin.dashboard',       'label' => 'Dashboard'],
        ['route' => 'admin.bookings.index',      'pattern' => 'admin.bookings.*',      'label' => 'Booking'],
        ['route' => 'admin.schedule.index',      'pattern' => 'admin.schedule.*',      'label' => 'Jadwal'],
        ['route' => 'admin.photographers.index', 'pattern' => 'admin.photographers.*', 'label' => 'Fotografer'],
        ['route' => 'admin.packages.index',      'pattern' => 'admin.packages.*',      'label' => 'Paket'],
        ['route' => 'admin.customers.index',     'pattern' => 'admin.customers.*',     'label' => 'Pelanggan'],
      ];
    @endphp

    <aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
      <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu">
          <span class="navbar-toggler-icon"></span>
        </button>
        <h1 class="navbar-brand navbar-brand-autodark">
          <a href="{{ route('admin.dashboard') }}">{{ config('app.name') }}</a>
        </h1>

        <div class="collapse navbar-collapse" id="sidebar-menu">
          <ul class="navbar-nav pt-lg-3">
            @foreach ($menus as $m)
              <li class="nav-item {{ request()->routeIs($m['pattern']) ? 'active' : '' }}">
                <a class="nav-link" href="{{ route($m['route']) }}">
                  <span class="nav-link-title">{{ $m['label'] }}</span>
                </a>
              </li>
            @endforeach
          </ul>

          <div class="mt-auto p-3">
            <form action="{{ route('logout') }}" method="POST">
              @csrf
              <button class="btn btn-outline-light w-100">Keluar</button>
            </form>
          </div>
        </div>
      </div>
    </aside>

    <div class="page-wrapper">
      <div class="page-header d-print-none">
        <div class="container-xl">
          <h2 class="page-title">@yield('title')</h2>
        </div>
      </div>

      <div class="page-body">
        <div class="container-xl">
          @include('admin.partials.alerts')
          @yield('content')
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/js/tabler.min.js"></script>
  @stack('scripts')
</body>
</html>