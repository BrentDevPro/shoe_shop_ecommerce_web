@extends('layouts.store')

@section('content')
<div class="container">

    <!-- Hero Section -->
    <div class="hero-banner rounded-4 p-5 mb-5 text-white position-relative shadow-sm">
        <div class="row align-items-center">
            <div class="col-md-7 position-relative z-1">
                <span class="badge bg-danger mb-3 px-3 py-2 rounded-pill">2026 COLLECTION</span>
                <h1 class="display-4 fw-bold mb-3">Step Into Excellence</h1>
                <p class="lead text-light opacity-75 mb-4">
                    Engineered comfort meets modern street aesthetics. Discover original performance sneakers and basketball releases.
                </p>
                <a href="#" class="btn btn-light text-dark rounded-pill px-4 py-2 fw-semibold">
                    Shop Featured Shoes &rarr;
                </a>
            </div>
            <!-- Decorative element simulating the lightning bolt from design -->
            <div class="col-md-5 d-none d-md-block text-end opacity-25">
                <h1 class="display-1 fw-bold" style="font-size: 15rem; line-height: 0.8;">&#9889;</h1>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div class="flex-grow-1" style="max-width: 300px;">
            <input type="text" class="form-control rounded-pill bg-white border-light-subtle" placeholder="Search shoe name or brand...">
        </div>

        <div class="d-flex gap-2 overflow-auto pb-2 pb-md-0">
            <button class="btn btn-dark rounded-pill px-4">All Shoes</button>
            @foreach($categories as $category)
            <button class="btn btn-outline-secondary rounded-pill px-4 border-light-subtle bg-white text-dark">{{ $category->name }}</button>
            @endforeach
        </div>

        <div>
            <select class="form-select rounded-pill bg-white border-light-subtle w-auto">
                <option>Sort by: Featured</option>
                <option>Price: Low to High</option>
                <option>Price: High to Low</option>
            </select>
        </div>
    </div>

    <!-- Product Grid -->
    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach($products as $product)
        <div class="col">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden product-card bg-white">
                <div class="product-image-container">
                    <img src="{{ $product->image_path }}" alt="{{ $product->name }}">
                </div>
                <div class="card-body p-4">
                    <p class="text-muted small fw-bold mb-1 text-uppercase">{{ $product->category->name ?? 'Uncategorized' }}</p>
                    <h5 class="card-title fw-bold mb-2">{{ $product->name }}</h5>
                    <p class="card-text text-muted small text-truncate">{{ $product->description }}</p>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <span class="fs-5 fw-bold">₱{{ number_format($product->price, 2) }}</span>
                        <!-- In Phase 7, this will link to the product detail page -->
                        <a href="{{ route('store.product', $product->slug) }}" class="btn btn-outline-dark rounded-pill px-3 py-1 btn-sm fw-medium">View Options</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>
@endsection