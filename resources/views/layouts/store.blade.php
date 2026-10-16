<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Footwear') }}</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .hero-banner {
            background-color: #1a202c;
        }

        .product-card {
            transition: transform 0.2s;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-image-container {
            height: 250px;
            overflow: hidden;
            background-color: #e9ecef;
        }

        .product-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .search-input:focus {
            box-shadow: none !important;
        }
        
        .dropdown-menu {
            transition: opacity 0.2s ease-in-out, transform 0.2s ease-in-out;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-white border-bottom py-3 sticky-top shadow-sm">
        <div class="container">
            
            <a class="navbar-brand fw-bold text-danger fs-4 me-lg-5" href="{{ url('/') }}">
                <i class="bi bi-shop"></i> SHOE SHOP.
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#storeNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="storeNav">
                
                <form id="navSearchForm" action="{{ route('store.index') }}" method="GET" class="d-flex mx-auto my-3 my-lg-0 w-100" style="max-width: 500px;">
                    <div class="input-group shadow-sm rounded-pill overflow-hidden border border-light-subtle">
                        <input type="text" id="navSearchInput" name="search" class="form-control search-input border-0 bg-light px-4 py-2" placeholder="Search shoes, brands..." value="{{ request('search') }}">
                        <button class="btn btn-light bg-light border-0 px-4 text-dark" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>

                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center gap-3">
                    
                    @auth
                    @php
                    $orderCount = \App\Models\Order::where('user_id', Auth::id())
                    ->whereIn('status', ['pending', 'paid', 'shipped'])
                    ->count();
                    @endphp
                    <li class="nav-item">
                        <a class="nav-link text-dark d-flex align-items-center gap-2 fw-medium px-lg-3" href="{{ route('orders.index') }}" title="Orders & Tracking">
                            <i class="bi bi-truck fs-5"></i>
                            <span>My Orders</span>
                            @if($orderCount > 0)
                                <span class="badge bg-danger rounded-pill">{{ $orderCount }}</span>
                            @endif
                        </a>
                    </li>
                    @endauth

                    <li class="nav-item">
                        @php $cartCount = session('cart') ? count(session('cart')) : 0; @endphp
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-dark rounded-circle d-flex align-items-center justify-content-center position-relative shadow-sm" style="width: 45px; height: 45px;" title="View Shopping Cart">
                            <i class="bi bi-cart3 fs-5"></i> 
                            @if($cartCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-white" style="font-size: 0.7rem; padding: 0.35em 0.6em; transform: translate(-30%, -20%) !important;">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                    </li>

                    @guest
                    <li class="nav-item ms-lg-2">
                        <a href="{{ route('login') }}" class="btn btn-dark rounded-pill px-4 py-2 shadow-sm fw-bold">Login</a>
                    </li>
                    @else
                    <li class="nav-item dropdown ms-lg-2">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 text-dark fw-medium p-0" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="border: none; background: transparent;">
                            <div class="bg-light border rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 45px; height: 45px;">
                                <i class="bi bi-person-fill fs-5 text-secondary"></i>
                            </div>
                            <span class="d-none d-sm-inline">Hi, {{ explode(' ', Auth::user()->name)[0] }}</span>
                        </a>
                        
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-3 py-0 overflow-hidden" style="min-width: 240px;">
                            <li class="px-4 py-3 bg-light border-bottom">
                                <p class="mb-0 fw-bold text-dark">{{ Auth::user()->name }}</p>
                                <small class="text-muted d-block text-truncate" style="max-width: 200px;">{{ Auth::user()->email ?? 'Member' }}</small>
                            </li>
                            
                            <li class="pt-2">
                                <a class="dropdown-item py-2 px-4 fw-medium text-secondary d-flex align-items-center gap-3" href="{{ route('store.index') }}">
                                    <i class="bi bi-shop fs-5"></i> Store Home
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2 px-4 fw-medium text-dark d-flex align-items-center gap-3" href="{{ route('profile.index') }}">
                                    <i class="bi bi-person-gear fs-5"></i> My Profile
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider my-2 mx-3">
                            </li>
                            <li class="pb-2">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 px-4 text-danger fw-medium d-flex align-items-center gap-3">
                                        <i class="bi bi-box-arrow-right fs-5"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>
</body>

</html>