<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>{{ Auth::user()->role == 'admin' ? 'CMS Admin' : 'Staff Operasional' }} - Flight Hub</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    
    <script src="https://cdn.jsdelivr.net/npm/webfontloader@1.6.28/webfontloader.js"></script>
    <script>
        WebFont.load({
            google: {"families":["Public Sans:300,400,500,600,700"]},
            custom: {"families":["Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: ['{{ asset("assets/css/fonts.min.css") }}']},
            active: function() { sessionStorage.fonts = true; }
        });
    </script>

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}">
</head>
<body>
    <div class="wrapper">
        <div class="sidebar" data-background-color="dark">
            <div class="sidebar-logo">
                <div class="logo-header" data-background-color="dark">
                    {{-- Link Logo dibuat dinamis tergantung siapa yang login --}}
                    <a href="{{ Auth::user()->role == 'admin' ? route('admin.dashboard') : route('staff.dashboard') }}" class="logo">
                        <h2 class="text-white mt-3 fw-bold">
                            {{ Auth::user()->role == 'admin' ? 'Admin CMS' : 'Staff Hub' }}
                        </h2>
                    </a>
                    <div class="nav-toggle">
                        <button class="btn btn-toggle toggle-sidebar"><i class="gg-menu-right"></i></button>
                        <button class="btn btn-toggle sidenav-toggler"><i class="gg-menu-left"></i></button>
                    </div>
                </div>
            </div>
            
            <div class="sidebar-wrapper scrollbar scrollbar-inner">
                <div class="sidebar-content">
                    <ul class="nav nav-secondary">
                        
                        {{-- ========================================== --}}
                        {{-- 1. MENU KHUSUS ADMIN (CMS WEB) --}}
                        {{-- ========================================== --}}
                        @if(Auth::user()->role == 'admin')
                            <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <a href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-desktop"></i>
                                    <p>Dashboard CMS</p>
                                </a>
                            </li>
                        @endif


                        {{-- ========================================== --}}
                        {{-- 2. MENU KHUSUS STAFF (OPERASIONAL) --}}
                        {{-- ========================================== --}}
                        @if(Auth::user()->role == 'staff')
                            <li class="nav-item {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
                                <a href="{{ route('staff.dashboard') }}">
                                    <i class="fas fa-home"></i>
                                    <p>Dashboard Staff</p>
                                </a>
                            </li>
                            
                            <li class="nav-section">
                                <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
                                <h4 class="text-section">Data Master</h4>
                            </li>

                            <li class="nav-item {{ request()->routeIs('staff.airports.*') ? 'active' : '' }}">
                                <a href="{{ route('staff.airports.index') }}">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <p>Kelola Bandara</p>
                                </a>
                            </li>
                            <li class="nav-item {{ request()->routeIs('staff.airlines.*') ? 'active' : '' }}">
                                <a href="{{ route('staff.airlines.index') }}">
                                    <i class="fas fa-building"></i>
                                    <p>Kelola Maskapai</p>
                                </a>
                            </li>
                            <li class="nav-item {{ request()->routeIs('staff.airplanes.*') ? 'active' : '' }}">
                                <a href="{{ route('staff.airplanes.index') }}">
                                    <i class="fas fa-plane"></i>
                                    <p>Kelola Pesawat</p>
                                </a>
                            </li>

                            <li class="nav-section">
                                <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
                                <h4 class="text-section">Transaksi</h4>
                            </li>

                            <li class="nav-item {{ request()->routeIs('staff.flights.*') ? 'active' : '' }}">
                                <a href="{{ route('staff.flights.index') }}">
                                    <i class="fas fa-calendar-alt"></i>
                                    <p>Jadwal Penerbangan</p>
                                </a>
                            </li>
                            <li class="nav-item {{ request()->routeIs('staff.transactions.*') ? 'active' : '' }}">
                                <a href="{{ route('staff.transactions.index') }}">
                                    <i class="fas fa-shopping-cart"></i>
                                    <p>Pesanan Tiket Masuk</p>
                                </a>
                            </li>
                            <li class="nav-item {{ request()->routeIs('staff.promos.*') ? 'active' : '' }}">
    <a href="{{ route('staff.promos.index') }}">
        <i class="fas fa-tags"></i>
        <p>Manajemen Promo</p>
    </a>
</li>
                        @endif

                    </ul>
                </div>
            </div>
        </div>

        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo"></div>
                <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
                    <div class="container-fluid">
                        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                            
                            <li class="nav-item topbar-user d-flex align-items-center">
                                <div class="profile-pic d-flex align-items-center me-4">
                                    <div class="avatar-sm me-2">
                                        <img src="{{ asset('assets/img/profile.jpg') }}" alt="..." class="avatar-img rounded-circle">
                                    </div>
                                    <span class="profile-username fw-bold text-dark">
                                        Hi, {{ Auth::user()->name }}
                                        <small class="d-block text-muted" style="font-size: 0.75rem; text-transform: uppercase;">{{ Auth::user()->role }}</small>
                                    </span>
                                </div>

                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm fw-bold">
                                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                                    </button>
                                </form>
                            </li>

                        </ul>
                    </div>
                </nav>
            </div>

            <div class="container">
                <div class="page-inner">
                    @yield('content')
                </div>
            </div>

            <footer class="footer">
                <div class="container-fluid">
                    <div class="copyright ms-auto">
                        2026, Dibuat dengan <i class="fa fa-heart heart text-danger"></i> oleh Programmer Handal & Mandor
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
</body>
</html>