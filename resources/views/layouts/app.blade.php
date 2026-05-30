<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MediTrack') - MediTrack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --sidebar-width: 230px; }

        *, *::before, *::after { box-sizing: border-box; }

        body { background: #f5f6fa; min-height: 100vh; font-size: 14px; }

        /* Sidebar */
        #sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: #2980b9;
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: transform .25s ease;
        }
        .sidebar-brand {
            padding: 18px 20px;
            border-bottom: 1px solid rgba(255,255,255,.15);
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .sidebar-brand i { font-size: 22px; color: #fff; }
        .sidebar-brand span { font-size: 16px; font-weight: 700; color: #fff; }

        #sidebar .nav-link {
            color: rgba(255,255,255,.75);
            padding: 10px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13.5px;
            border-left: 3px solid transparent;
            transition: all .15s;
        }
        #sidebar .nav-link i { font-size: 16px; width: 18px; }
        #sidebar .nav-link:hover { color: #fff; background: rgba(255,255,255,.12); }
        #sidebar .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,.2);
            border-left-color: #fff;
        }

        .sidebar-label {
            padding: 16px 20px 6px;
            font-size: 10px;
            font-weight: 700;
            color: rgba(255,255,255,.45);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 14px 16px;
            border-top: 1px solid rgba(255,255,255,.15);
        }
        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px;
            border-radius: 8px;
            background: rgba(255,255,255,.12);
            margin-bottom: 10px;
            text-decoration: none;
        }
        .sidebar-user:hover { background: rgba(255,255,255,.2); }
        .sidebar-user .uname { font-size: 12.5px; font-weight: 600; color: #fff; }
        .sidebar-user .uemail { font-size: 11px; color: rgba(255,255,255,.6); }
        .avatar-circle {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: rgba(255,255,255,.25);
            color: #fff;
            font-weight: 700;
            font-size: 13px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .avatar-img {
            width: 32px; height: 32px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }

        /* Main */
        #main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Topbar */
        #topbar {
            background: #fff;
            border-bottom: 1px solid #e0e0e0;
            padding: 0 24px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        .topbar-title { font-size: 15px; font-weight: 600; color: #2c3e50; }
        .topbar-right { display: flex; align-items: center; gap: 12px; }
        .topbar-avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e0e0e0;
        }
        .topbar-avatar-circle {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: #2980b9;
            color: #fff;
            font-weight: 700;
            font-size: 13px;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid #e0e0e0;
        }

        /* Content */
        #page-content { padding: 24px; flex: 1; min-width: 0; }

        /* Cards */
        .card { border: 1px solid #e0e0e0; border-radius: 8px; box-shadow: none; }
        .card-header { background: #fff; border-bottom: 1px solid #e0e0e0; padding: 12px 16px; font-weight: 600; font-size: 13.5px; }

        /* Stat cards */
        .stat-card { border-radius: 8px !important; border: 1px solid #e0e0e0 !important; }
        .stat-card .stat-icon {
            width: 46px; height: 46px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
        }

        /* Table */
        .table thead th {
            background: #f8f9fa;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .4px;
            color: #6c757d;
            border-bottom: 2px solid #e0e0e0;
        }
        .table tbody td { font-size: 13.5px; }

        /* Buttons */
        .btn { font-size: 13px; border-radius: 6px; }

        /* Avatar lg */
        .avatar-lg { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid #e0e0e0; }

        /* Auth */
        .auth-bg {
            min-height: 100vh;
            background: #f5f6fa;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .auth-box {
            background: #fff;
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,.2);
        }
        .auth-box-header {
            background: #2980b9;
            padding: 28px 24px;
            text-align: center;
            color: #fff;
        }
        .auth-box-header i { font-size: 32px; display: block; margin-bottom: 8px; }
        .auth-box-header h5 { font-weight: 700; margin: 0; font-size: 18px; }
        .auth-box-body { padding: 24px; }

        @media (max-width: 575.98px) {
            .auth-bg { padding: 0; align-items: stretch; }
            .auth-box { border-radius: 0; box-shadow: none; max-width: 100%; min-height: 100vh; display: flex; flex-direction: column; }
            .auth-box-body { flex: 1; padding: 24px 20px; }
            #topbar { padding: 0 12px; }
            #page-content { padding: 16px 12px 120px; }
            #bottom-nav { height: 75px; }
            #bottom-nav a, #bottom-nav .logout-btn { font-size: 9px; }
            #bottom-nav a i, #bottom-nav .logout-btn i { font-size: 18px; }
            .topbar-title { max-width: 70%; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        }

        /* Responsive */
        @media (max-width: 991.98px) {
            #sidebar { transform: translateX(-100%); width: 100%; max-width: 100%; }
            #sidebar.show { transform: translateX(0); }
            #main-wrapper { margin-left: 0; padding-bottom: 100px; min-height: calc(100vh - 60px); }
            #sidebar-overlay {
                display: none;
                position: fixed; inset: 0;
                background: rgba(0,0,0,.4);
                z-index: 999;
            }
            #sidebar-overlay.show { display: block; }
            #page-content { padding: 18px 12px 110px; }
            .topbar-title { font-size: 14px; }
            #topbar { padding: 0 14px; height: 52px; }
            #sidebar .nav-link { padding: 12px 18px; font-size: 14px; }
            #bottom-nav { display: flex; }
            .topbar-right .d-none.d-md-inline { display: none !important; }
        }

        /* Bottom nav for mobile */
        #bottom-nav {
            display: none;
            position: fixed;
            bottom: 0; left: 0; right: 0;
            height: 60px;
            background: #fff;
            border-top: 1px solid #e0e0e0;
            z-index: 1000;
            box-shadow: 0 -2px 10px rgba(0,0,0,.08);
        }
        #bottom-nav a {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 2px;
            text-decoration: none;
            color: #9ca3af;
            font-size: 10px;
            font-weight: 500;
            padding: 6px 0;
            transition: color .15s;
        }
        #bottom-nav a i { font-size: 20px; }
        #bottom-nav a.active { color: #2980b9; }
        #bottom-nav a:hover { color: #2980b9; }
        #bottom-nav .logout-btn {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 2px;
            background: none;
            border: none;
            color: #ef4444;
            font-size: 10px;
            font-weight: 500;
            padding: 6px 0;
            cursor: pointer;
        }
        #bottom-nav .logout-btn i { font-size: 20px; }

        @media (max-width: 991.98px) {
            #bottom-nav { display: flex; }
        }

        .toast-container { z-index: 1100; }
    </style>
    @stack('styles')
</head>
<body>

@auth
<div id="sidebar">
    <a href="{{ route('dashboard') }}" class="sidebar-brand">
        <i class="bi bi-capsule-pill"></i>
        <span>MediTrack</span>
    </a>

    <div class="sidebar-label">Menu</div>
    <nav class="nav flex-column">
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid"></i> Dashboard
        </a>
        <a href="{{ route('medicines.index') }}" class="nav-link {{ request()->routeIs('medicines.*') ? 'active' : '' }}">
            <i class="bi bi-journal-medical"></i> Medicines
        </a>
        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Users
        </a>
    </nav>

    <div class="sidebar-label">Account</div>
    <nav class="nav flex-column">
        <a href="{{ route('profile.show') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <i class="bi bi-person"></i> My Profile
        </a>
    </nav>

    <div class="sidebar-footer">
        <a href="{{ route('profile.show') }}" class="sidebar-user">
            @if(Auth::user()->profile_picture)
                <img src="{{ Storage::url(Auth::user()->profile_picture) }}" class="avatar-img" alt="">
            @else
                <div class="avatar-circle">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            @endif
            <div>
                <div class="uname">{{ Auth::user()->name }}</div>
                <div class="uemail">{{ Auth::user()->email }}</div>
            </div>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-sm w-100" style="background:rgba(255,255,255,.08);color:rgba(255,255,255,.6);border:1px solid rgba(255,255,255,.1)">
                <i class="bi bi-box-arrow-right me-1"></i> Logout
            </button>
        </form>
    </div>
</div>

<div id="sidebar-overlay"></div>

<div id="main-wrapper">
    <div id="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm d-lg-none p-1" id="sidebarToggle" style="border:1px solid #e0e0e0;background:#fff">
                <i class="bi bi-list fs-5"></i>
            </button>
            <span class="topbar-title">@yield('title', 'Dashboard')</span>
        </div>
        <div class="topbar-right">
            <a href="{{ route('profile.show') }}" class="d-flex align-items-center gap-2 text-decoration-none text-dark">
                @if(Auth::user()->profile_picture)
                    <img src="{{ Storage::url(Auth::user()->profile_picture) }}" class="topbar-avatar" alt="">
                @else
                    <div class="topbar-avatar-circle">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                @endif
                <span class="d-none d-md-inline" style="font-size:13px;font-weight:600">{{ Auth::user()->name }}</span>
            </a>
        </div>
    </div>

    <div id="page-content">
        @yield('content')
    </div>
</div>

{{-- Mobile Bottom Nav --}}
<nav id="bottom-nav">
    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid"></i> Home
    </a>
    <a href="{{ route('medicines.index') }}" class="{{ request()->routeIs('medicines.*') ? 'active' : '' }}">
        <i class="bi bi-journal-medical"></i> Medicines
    </a>
    <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
        <i class="bi bi-people"></i> Users
    </a>
    <a href="{{ route('profile.show') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
        <i class="bi bi-person"></i> Profile
    </a>
    <form method="POST" action="{{ route('logout') }}" style="flex:1;display:flex">
        @csrf
        <button type="submit" class="logout-btn">
            <i class="bi bi-box-arrow-right"></i> Logout
        </button>
    </form>
</nav>

@else
<div class="auth-bg">
    @yield('content')
</div>
@endauth

{{-- Toasts --}}
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    @if(session('toast_success'))
    <div class="toast align-items-center text-bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body"><i class="bi bi-check-circle-fill me-2"></i>{{ session('toast_success') }}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    @endif
    @if(session('toast_error'))
    <div class="toast align-items-center text-bg-danger border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body"><i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('toast_error') }}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.toast').forEach(el => new bootstrap.Toast(el, { delay: 3500 }).show());
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const toggler = document.getElementById('sidebarToggle');
    if (toggler) {
        toggler.addEventListener('click', () => { sidebar.classList.toggle('show'); overlay.classList.toggle('show'); });
        overlay.addEventListener('click', () => { sidebar.classList.remove('show'); overlay.classList.remove('show'); });
    }
</script>
@stack('scripts')
</body>
</html>
