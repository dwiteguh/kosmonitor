<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KosMonitor')</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            overflow-x: hidden;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #212529;
            color: #fff;
            padding-top: 20px;
            transition: all 0.3s;
        }
        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
            padding: 10px 20px;
            display: block;
            transition: 0.3s;
        }
        .sidebar a:hover, .sidebar a.active {
            color: #fff;
            background-color: #343a40;
            border-left: 4px solid #0d6efd;
        }
        .sidebar .brand {
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            color: #0d6efd;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            min-height: 100vh;
        }
        .top-navbar {
            background-color: #fff;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column">
        <div class="brand">KosMonitor</div>
        
        <div class="flex-grow-1">
            @if(Auth::check() && Auth::user()->role === 'owner')
                <!-- MENU KHUSUS OWNER -->
                <small class="text-uppercase text-muted px-3 mb-1 d-block mt-3">Menu Utama</small>
                <a href="{{ route('owner.dashboard') }}" class="{{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
                
                <small class="text-uppercase text-muted px-3 mb-1 d-block mt-3">Data Kos</small>
                <a href="{{ route('owner.rooms.index') }}" class="{{ request()->routeIs('owner.rooms.*') ? 'active' : '' }}">
                    <i class="bi bi-door-open me-2"></i> Data Kamar
                </a>
                <a href="{{ route('owner.tenants.index') }}" class="{{ request()->routeIs('owner.tenants.*') ? 'active' : '' }}">
                    <i class="bi bi-people me-2"></i> Data Penghuni
                </a>
                
                <small class="text-uppercase text-muted px-3 mb-1 d-block mt-3">Transaksi</small>
                <a href="{{ route('owner.leases.index') }}" class="{{ request()->routeIs('owner.leases.*') ? 'active' : '' }}">
                    <i class="bi bi-journal-text me-2"></i> Penyewaan
                </a>
                <a href="{{ route('owner.invoices.index') }}" class="{{ request()->routeIs('owner.invoices.*') ? 'active' : '' }}">
                    <i class="bi bi-receipt me-2"></i> Keuangan & Tagihan
                </a>
                
                <small class="text-uppercase text-muted px-3 mb-1 d-block mt-3">Layanan</small>
                <a href="{{ route('owner.complaints.index') }}" class="{{ request()->routeIs('owner.complaints.*') ? 'active' : '' }}">
                    <i class="bi bi-chat-dots me-2"></i> Keluhan Penghuni
                </a>

            @elseif(Auth::check() && Auth::user()->role === 'tenant')
                <!-- MENU KHUSUS TENANT (PENGHUNI) -->
                <small class="text-uppercase text-muted px-3 mb-1 d-block mt-3">Menu Utama</small>
                <a href="{{ route('tenant.dashboard') }}" class="{{ request()->routeIs('tenant.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
                
                <small class="text-uppercase text-muted px-3 mb-1 d-block mt-3">Keuangan</small>
                <a href="{{ route('tenant.invoices.index') }}" class="{{ request()->routeIs('tenant.invoices.*') ? 'active' : '' }}">
                    <i class="bi bi-wallet2 me-2"></i> Tagihan Saya
                </a>
                
                <small class="text-uppercase text-muted px-3 mb-1 d-block mt-3">Layanan</small>
                <a href="{{ route('tenant.complaints.index') }}" class="{{ request()->routeIs('tenant.complaints.*') ? 'active' : '' }}">
                    <i class="bi bi-chat-square-text me-2"></i> Keluhan / Komplain
                </a>
            @endif
        </div>

        <div class="mt-auto mb-3">
            <!-- Form Logout -->
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-link text-danger text-decoration-none w-100 text-start px-4">
                    <i class="bi bi-box-arrow-left me-2"></i> Keluar
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <h4 class="m-0">@yield('page_title', 'Dashboard')</h4>
            <div class="user-info">
                <i class="bi bi-person-circle fs-4 me-2 align-middle"></i>
                <span class="align-middle fw-bold">{{ Auth::user()->name }}</span>
            </div>
        </div>

        <!-- Dynamic Content -->
        @yield('content')
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>