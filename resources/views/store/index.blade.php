@extends('layouts.store')

@section('content')
<style>
    .filter-link {
        transition: all 0.2s ease;
        padding: 0.4rem 0.75rem;
        border-radius: 50rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #495057;
        text-decoration: none;
        font-size: 0.9rem;
    }
    .filter-link:hover {
        background-color: #f8f9fa;
        color: #212529;
    }
    .filter-link.active {
        background-color: #212529;
        color: #fff !important;
        font-weight: 600;
    }
    .filter-link.active .badge {
        background-color: #fff !important;
        color: #212529 !important;
    }
    
    /* Instant opening for sort dropdown menu */
    .dropdown-menu {
        transition: none !important;
        animation: none !important;
    }
</style>

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
            <div class="col-md-5 d-none d-md-block text-end opacity-25">
                <h1 class="display-1 fw-bold" style="font-size: 15rem; line-height: 0.8;">&#9889;</h1>
            </div>
        </div>
    </div>

    <!-- Store Layout: Sidebar (Left) + Catalog (Right) -->
    <div class="row g-4">

        {{-- Left Sidebar: Filters Only --}}
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white sticky-top" style="top: 100px; z-index: 10;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-sliders me-2"></i>Filters</h5>
                    @if(request('search') || request('category') || request('brand'))
                        <a href="{{ route('store.index') }}" class="btn btn-link text-danger p-0 text-decoration-none small fw-semibold">
                            Reset All
                        </a>
                    @endif
                </div>

                {{-- Sidebar Search Input --}}
                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted text-uppercase">Search</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0 rounded-start-pill ps-3">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" id="searchInput" class="form-control bg-light border-0 rounded-end-pill shadow-none text-dark" placeholder="Shoe name, model..." value="{{ request('search') }}">
                    </div>
                </div>

                {{-- Category Filter --}}
                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted text-uppercase mb-2">Categories</label>
                    <div class="d-flex flex-column gap-1" style="max-height: 250px; overflow-y: auto;">
                        <a href="#" class="filter-link category-link {{ !request('category') ? 'active' : '' }}" data-category="">
                            <span>All Categories</span>
                        </a>
                        @foreach($categories as $category)
                            <a href="#" class="filter-link category-link {{ request('category') == $category->id ? 'active' : '' }}" data-category="{{ $category->id }}">
                                <span>{{ $category->name }}</span>
                                <span class="badge bg-light text-secondary rounded-pill">{{ $category->products_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Brand Filter --}}
                <div>
                    <label class="form-label small fw-bold text-muted text-uppercase mb-2">Brands</label>
                    <div class="d-flex flex-column gap-1" style="max-height: 220px; overflow-y: auto;">
                        <a href="#" class="filter-link brand-link {{ !request('brand') ? 'active' : '' }}" data-brand="">
                            <span>All Brands</span>
                        </a>
                        @foreach($brands as $brand)
                            <a href="#" class="filter-link brand-link {{ request('brand') == $brand->id ? 'active' : '' }}" data-brand="{{ $brand->id }}">
                                <span>{{ $brand->name }}</span>
                                <span class="badge bg-light text-secondary rounded-pill">{{ $brand->products_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

        {{-- Right Content: Header & Product Grid --}}
        <div class="col-lg-9">

            {{-- Top Sort Bar --}}
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center bg-white p-3 rounded-4 shadow-sm mb-4 gap-3">
                <p class="text-muted small fw-medium mb-0" id="showingText">
                    Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
                </p>

                {{-- Custom Sort Dropdown --}}
                <div class="dropdown">
                    <button class="btn btn-white border border-light-subtle rounded-pill px-4 py-2 text-dark fw-medium d-flex align-items-center gap-2 shadow-sm dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="background-color: #fff;">
                        <i class="bi bi-sort-down fs-5 text-secondary"></i>
                        <span id="sortDropdownLabel">
                            @if(request('sort') == 'price_asc')
                                Price: Low to High
                            @elseif(request('sort') == 'price_desc')
                                Price: High to Low
                            @else
                                Sort by: Featured
                            @endif
                        </span>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-2 py-0 overflow-hidden" aria-labelledby="sortDropdown" style="min-width: 220px;">
                        <li>
                            <a class="dropdown-item sort-option py-2 px-4 fw-medium text-dark d-flex align-items-center justify-content-between {{ (!request('sort') || request('sort') == 'latest') ? 'bg-light fw-bold' : '' }}" href="#" data-value="latest">
                                Sort by: Featured
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item sort-option py-2 px-4 fw-medium text-dark d-flex align-items-center justify-content-between {{ request('sort') == 'price_asc' ? 'bg-light fw-bold' : '' }}" href="#" data-value="price_asc">
                                Price: Low to High
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item sort-option py-2 px-4 fw-medium text-dark d-flex align-items-center justify-content-between {{ request('sort') == 'price_desc' ? 'bg-light fw-bold' : '' }}" href="#" data-value="price_desc">
                                Price: High to Low
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Product Grid Wrapper (Targeted by AJAX) --}}
            <div id="productGridWrapper">
                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4 mb-5">
                    @forelse($products as $product)
                    <div class="col">
                        <a href="{{ route('store.product', $product->slug) }}" class="text-decoration-none text-dark d-block h-100">
                            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden product-card bg-white" style="transition: transform 0.2s; cursor: pointer;">
                                <div class="product-image-container">
                                    <img src="{{ $product->image_path }}" alt="{{ $product->name }}">
                                </div>
                                <div class="card-body p-4">
                                    <p class="text-muted small fw-bold mb-1 text-uppercase">{{ $product->category->name ?? 'Uncategorized' }}</p>
                                    <h5 class="card-title fw-bold mb-2">{{ $product->name }}</h5>
                                    <p class="card-text text-muted small text-truncate">{{ $product->description }}</p>

                                    <div class="d-flex justify-content-between align-items-center mt-4">
                                        <span class="fs-5 fw-bold">₱{{ number_format($product->price, 2) }}</span>
                                        <span class="btn btn-outline-dark rounded-pill px-3 py-1 btn-sm fw-medium">View Options</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-search text-muted mb-3 d-block" style="font-size: 3rem;"></i>
                        <h4 class="fw-bold text-dark">No products found</h4>
                        <p class="text-muted">Try adjusting your search, category, or brand filters.</p>
                    </div>
                    @endforelse
                </div>

                {{-- Pagination Links --}}
                <div class="d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            </div>

        </div>

    </div>

</div>

<!-- Real-Time AJAX Search & Filter Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const navSearchInput = document.getElementById('navSearchInput');
    const navSearchForm = document.getElementById('navSearchForm');
    const sortOptions = document.querySelectorAll('.sort-option');
    const sortDropdownLabel = document.getElementById('sortDropdownLabel');
    const categoryLinks = document.querySelectorAll('.category-link');
    const brandLinks = document.querySelectorAll('.brand-link');
    const gridWrapper = document.getElementById('productGridWrapper');
    const showingText = document.getElementById('showingText');

    let debounceTimer;

    function fetchProducts() {
        let url = new URL(window.location.href);

        // Prefer active text from navSearchInput or searchInput
        const queryVal = (navSearchInput ? navSearchInput.value : searchInput.value).trim();
        if (queryVal) url.searchParams.set('search', queryVal);
        else url.searchParams.delete('search');

        // Active Category
        const activeCategory = document.querySelector('.category-link.active');
        const categoryId = activeCategory ? activeCategory.dataset.category : '';
        if (categoryId) url.searchParams.set('category', categoryId);
        else url.searchParams.delete('category');

        // Active Brand
        const activeBrand = document.querySelector('.brand-link.active');
        const brandId = activeBrand ? activeBrand.dataset.brand : '';
        if (brandId) url.searchParams.set('brand', brandId);
        else url.searchParams.delete('brand');

        // Active Sort
        const activeSort = document.querySelector('.sort-option.bg-light');
        const sortValue = activeSort ? activeSort.dataset.value : 'latest';
        if (sortValue && sortValue !== 'latest') url.searchParams.set('sort', sortValue);
        else url.searchParams.delete('sort');

        url.searchParams.delete('page');

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                const newGrid = doc.getElementById('productGridWrapper');
                const newShowing = doc.getElementById('showingText');

                if (newGrid && gridWrapper) gridWrapper.innerHTML = newGrid.innerHTML;
                if (newShowing && showingText) showingText.innerHTML = newShowing.innerHTML;

                window.history.pushState({}, '', url);
            });
    }

    // Live search from Sidebar Search Input
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            if (navSearchInput) navSearchInput.value = searchInput.value;
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(fetchProducts, 300);
        });
    }

    // Live search from Navbar Search Input
    if (navSearchInput) {
        navSearchInput.addEventListener('input', () => {
            if (searchInput) searchInput.value = navSearchInput.value;
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(fetchProducts, 300);
        });
    }

    // Prevent full page submit on Navbar Form when on Storefront
    if (navSearchForm) {
        navSearchForm.addEventListener('submit', (e) => {
            e.preventDefault();
            fetchProducts();
        });
    }

    // Custom Sort Dropdown Option Selection
    sortOptions.forEach(option => {
        option.addEventListener('click', function(e) {
            e.preventDefault();

            sortOptions.forEach(opt => opt.classList.remove('bg-light', 'fw-bold'));
            this.classList.add('bg-light', 'fw-bold');

            sortDropdownLabel.textContent = this.textContent.trim();
            fetchProducts();
        });
    });

    // Category links
    categoryLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            categoryLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
            fetchProducts();
        });
    });

    // Brand links
    brandLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            brandLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
            fetchProducts();
        });
    });
});
</script>
@endsection