<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - {{ config('app.name', 'Footwear') }}</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f6f9; font-family: system-ui, -apple-system, sans-serif; }
        
        /* Premium Dark Sidebar matching reference */
        .sidebar { background-color: #161b22; color: #8b95a5; }
        .sidebar-brand { color: #ffffff; text-decoration: none; display: flex; align-items: center; gap: 8px; font-size: 1.25rem; font-weight: 700; }
        .sidebar-brand i { color: #eb4f5a; font-size: 1.3rem; }
        
        .nav-link { color: #8b95a5; border-radius: 0.5rem; padding: 0.8rem 1rem; margin-bottom: 0.25rem; transition: all 0.2s ease; font-size: 0.95rem; font-weight: 500; display: flex; align-items: center; }
        .nav-link:hover:not(.active) { color: #ffffff; background-color: rgba(255,255,255,0.05); }
        .nav-link.active { background-color: #272c40; color: #ffffff !important; font-weight: 600; }
        .nav-link i { margin-right: 12px; font-size: 1.1rem; }
        
        .badge-orders { background-color: #eb4f5a; color: white; font-size: 0.7rem; padding: 2px 7px; border-radius: 50%; margin-left: auto; }
        
        .top-navbar { background-color: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); }
    </style>
</head>
<body>
    <div class="d-flex" style="min-height: 100vh;">
        <!-- Premium Dark Sidebar -->
        <div class="sidebar p-3 d-flex flex-column" style="width: 260px;">
            <div class="mb-4 mt-2 px-2">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
                    <i class="bi bi-shield-check"></i> AdminPanel
                </a>
                <div class="text-secondary mt-1" style="font-size: 0.75rem; padding-left: 32px;">
                    Footwear Store Manager
                </div>
            </div>
            
            <ul class="nav flex-column mb-auto mt-2">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-layout-text-window-reverse"></i> Overview
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam"></i> Shoes & Inventory
                    </a>
                </li>
                <li class="nav-item">
                    @php $pendingOrdersCount = \App\Models\Order::where('status', 'pending')->count(); @endphp
                    <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                        <i class="bi bi-receipt"></i> Orders Management
                        @if($pendingOrdersCount > 0)
                            <span class="badge-orders">{{ $pendingOrdersCount }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <i class="bi bi-tags"></i> Categories
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.brands.index') }}" class="nav-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                        <i class="bi bi-award"></i> Brands
                    </a>
                </li>
                <li class="nav-item mt-4">
                    <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <i class="bi bi-gear"></i> Account Settings
                    </a>
                </li>
            </ul>
            
            <div class="mt-auto pt-4 d-flex flex-column gap-3">
                <!-- View Customer Shop (Opens in New Tab) -->
                <a href="{{ route('store.index') }}" target="_blank" class="btn btn-outline-light rounded-pill w-100 d-flex align-items-center justify-content-center gap-2" style="font-size: 0.85rem; border-color: rgba(255,255,255,0.2);">
                    <i class="bi bi-eye"></i> View Customer Shop
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-link text-secondary text-decoration-none w-100 text-start px-3 py-1" style="font-size: 0.85rem;">
                        <i class="bi bi-box-arrow-left me-2"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1">
            <!-- Retained Top Control Panel -->
            <nav class="navbar top-navbar sticky-top border-bottom border-light-subtle px-4 py-3 shadow-sm">
                <span class="navbar-brand mb-0 h1 fw-bold text-dark fs-5">Control Panel</span>
                <div class="ms-auto d-flex align-items-center gap-3">
                    <div class="text-end d-none d-md-block">
                        <p class="mb-0 text-dark fw-semibold" style="font-size: 0.9rem;">{{ Auth::user()->name }}</p>
                        <p class="mb-0 text-muted" style="font-size: 0.8rem;">System Administrator</p>
                    </div>
                    <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </nav>
            <div class="p-4 p-md-5">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>