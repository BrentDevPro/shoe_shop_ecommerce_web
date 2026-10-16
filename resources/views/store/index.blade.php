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
    
    .dropdown-menu {
        transition: none !important;
        animation: none !important;
    }

    .pagination-wrapper {
        background: #ffffff;
        border-radius: 1rem;
        padding: 0.85rem 1.25rem;
        border: 1px solid rgba(0, 0, 0, 0.06);
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.03);
    }

    .pagination-wrapper nav[role="navigation"] {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        width: 100% !important;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .pagination {
        margin: 0 !important;
        padding: 0 !important;
        display: flex !important;
        align-items: center !important;
        gap: 0.4rem !important;
        list-style: none !important;
    }

    .pagination .page-item .page-link,
    .pagination .page-item span {
        border-radius: 50rem !important;
        border: 1px solid #e2e8f0 !important;
        color: #212529 !important;
        min-width: 42px !important;
        height: 42px !important;
        padding: 0 0.85rem !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-weight: 600 !important;
        font-size: 0.95rem !important;
        transition: all 0.2s ease !important;
        background-color: #ffffff !important;
        box-shadow: none !important;
        text-decoration: none !important;
    }

    .pagination .page-item:first-child .page-link,
    .pagination .page-item:first-child span,
    .pagination .page-item:last-child .page-link,
    .pagination .page-item:last-child span {
        min-width: 44px !important;
        height: 44px !important;
        font-size: 1.2rem !important;
        font-weight: bold !important;
        padding: 0 !important;
    }

    .pagination .page-item .page-link:hover {
        background-color: #f1f5f9 !important;
        color: #000000 !important;
        border-color: #cbd5e1 !important;
    }

    .pagination .page-item.active .page-link {
        background-color: #212529 !important;
        border-color: #212529 !important;
        color: #ffffff !important;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15) !important;
    }

    .pagination .page-item.disabled .page-link,
    .pagination .page-item.disabled span {
        background-color: #f8f9fa !important;
        color: #cbd5e1 !important;
        border-color: #f1f5f9 !important;
        opacity: 0.6 !important;
        pointer-events: none !important;
    }
</style>

<div class="container">

    <div class="hero-banner rounded-4 p-5 mb-5 text-white position-relative shadow-sm">
        <div class="row align-items-center">
            <div class="col-md-7 position-relative z-1">
                <span class="badge bg-danger mb-3 px-3 py-2 rounded-pill">THE LATEST COLLECTION</span>
                <h1 class="display-4 fw-bold mb-3">Find Your Next Favorite Pair</h1>
                <p class="lead text-light opacity-75 mb-4">
                    From everyday essentials to standout sneakers, find the right fit for wherever the day takes you.
                </p>
                <a href="#productGridWrapper" id="shopFeaturedBtn" class="btn btn-light text-dark rounded-pill px-4 py-2 fw-semibold shadow-sm">
                    Shop the Collection &rarr;
                </a>
            </div>
            <div class="col-md-5 d-none d-md-block text-end opacity-25">
                <h1 class="display-1 fw-bold" style="font-size: 15rem; line-height: 0.8;">&#9889;</h1>
            </div>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white sticky-top" style="top: 100px; z-index: 10;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-sliders me-2"></i>Filters</h5>
                    <button type="button" id="resetFiltersBtn" class="btn btn-link text-danger p-0 text-decoration-none small fw-semibold border-0 bg-transparent {{ (request('search') || request('category') || request('brand')) ? '' : 'd-none' }}">
                        Reset All
                    </button>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted text-uppercase">Search</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0 rounded-start-pill ps-3">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" id="searchInput" class="form-control bg-light border-0 rounded-end-pill shadow-none text-dark" placeholder="Shoe name, model..." value="{{ request('search') }}">
                    </div>
                </div>

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

        <div class="col-lg-9">

            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center bg-white p-3 rounded-4 shadow-sm mb-4 gap-3">
                <p class="text-muted small fw-medium mb-0" id="showingText">
                    Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
                </p>

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

            <div id="productGridWrapper" style="scroll-margin-top: 90px;">
                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4 mb-4">
                    @forelse($products as $product)
                    <div class="col">
                        <a href="{{ route('store.product', $product->slug) }}" class="text-decoration-none text-dark d-block h-100">
                            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden product-card bg-white" style="transition: transform 0.2s; cursor: pointer;">
                                <div class="product-image-container">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
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
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 bg-white text-center py-5 px-4 my-2">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                                <i class="bi bi-search text-muted display-6"></i>
                            </div>
                            <h4 class="fw-bold text-dark mb-2">No products found</h4>
                            <p class="text-muted small mb-4">We couldn't find any footwear matching your filters. Try adjusting your search or category selection.</p>
                            <div>
                                <button type="button" onclick="document.getElementById('resetFiltersBtn').click()" class="btn btn-dark rounded-pill px-4 py-2 fw-medium shadow-sm">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i> Clear All Filters
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforelse
                </div>

                @if($products->hasPages())
                <div class="pagination-wrapper mb-5">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
                @endif
            </div>

        </div>

    </div>

</div>

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
    const resetFiltersBtn = document.getElementById('resetFiltersBtn');
    const shopFeaturedBtn = document.getElementById('shopFeaturedBtn');

    let debounceTimer;

    function formatPaginationIcons() {
        const prevItem = document.querySelector('.pagination .page-item:first-child .page-link, .pagination .page-item:first-child span');
        if (prevItem) {
            prevItem.innerHTML = '<i class="bi bi-chevron-left"></i>';
        }

        const nextItem = document.querySelector('.pagination .page-item:last-child .page-link, .pagination .page-item:last-child span');
        if (nextItem) {
            nextItem.innerHTML = '<i class="bi bi-chevron-right"></i>';
        }
    }

    function checkResetVisibility() {
        const queryVal = (navSearchInput ? navSearchInput.value : searchInput.value).trim();
        const activeCategory = document.querySelector('.category-link.active');
        const categoryId = activeCategory ? activeCategory.dataset.category : '';
        const activeBrand = document.querySelector('.brand-link.active');
        const brandId = activeBrand ? activeBrand.dataset.brand : '';

        if (resetFiltersBtn) {
            if (queryVal || categoryId || brandId) {
                resetFiltersBtn.classList.remove('d-none');
            } else {
                resetFiltersBtn.classList.add('d-none');
            }
        }
    }

    function fetchProducts(page = null) {
        let url = new URL(window.location.href);

        const queryVal = (navSearchInput ? navSearchInput.value : searchInput.value).trim();
        if (queryVal) url.searchParams.set('search', queryVal);
        else url.searchParams.delete('search');

        const activeCategory = document.querySelector('.category-link.active');
        const categoryId = activeCategory ? activeCategory.dataset.category : '';
        if (categoryId) url.searchParams.set('category', categoryId);
        else url.searchParams.delete('category');

        const activeBrand = document.querySelector('.brand-link.active');
        const brandId = activeBrand ? activeBrand.dataset.brand : '';
        if (brandId) url.searchParams.set('brand', brandId);
        else url.searchParams.delete('brand');

        const activeSort = document.querySelector('.sort-option.bg-light');
        const sortValue = activeSort ? activeSort.dataset.value : 'latest';
        if (sortValue && sortValue !== 'latest') url.searchParams.set('sort', sortValue);
        else url.searchParams.delete('sort');

        if (page) {
            url.searchParams.set('page', page);
        } else {
            url.searchParams.delete('page');
        }

        checkResetVisibility();

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                const newGrid = doc.getElementById('productGridWrapper');
                const newShowing = doc.getElementById('showingText');

                if (newGrid && gridWrapper) gridWrapper.innerHTML = newGrid.innerHTML;
                if (newShowing && showingText) showingText.innerHTML = newShowing.innerHTML;

                formatPaginationIcons();
                window.history.pushState({}, '', url);

                if (page) {
                    gridWrapper.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
    }

    formatPaginationIcons();

    if (resetFiltersBtn) {
        resetFiltersBtn.addEventListener('click', function(e) {
            e.preventDefault();

            if (searchInput) searchInput.value = '';
            if (navSearchInput) navSearchInput.value = '';

            categoryLinks.forEach(l => l.classList.remove('active'));
            const defaultCat = document.querySelector('.category-link[data-category=""]');
            if (defaultCat) defaultCat.classList.add('active');

            brandLinks.forEach(l => l.classList.remove('active'));
            const defaultBrand = document.querySelector('.brand-link[data-brand=""]');
            if (defaultBrand) defaultBrand.classList.add('active');

            sortOptions.forEach(opt => opt.classList.remove('bg-light', 'fw-bold'));
            const defaultSort = document.querySelector('.sort-option[data-value="latest"]');
            if (defaultSort) defaultSort.classList.add('bg-light', 'fw-bold');
            if (sortDropdownLabel) sortDropdownLabel.textContent = 'Sort by: Featured';

            fetchProducts();
        });
    }

    if (shopFeaturedBtn) {
        shopFeaturedBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const gridTarget = document.getElementById('productGridWrapper');
            if (gridTarget) {
                gridTarget.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    }

    // Intercept Pagination Clicks inside Grid Wrapper for Real-time AJAX
    gridWrapper.addEventListener('click', function(e) {
        const pageLink = e.target.closest('.pagination-wrapper a');
        if (pageLink) {
            e.preventDefault();
            const linkUrl = new URL(pageLink.href);
            const page = linkUrl.searchParams.get('page');
            if (page) fetchProducts(page);
        }
    });

    if (searchInput) {
        searchInput.addEventListener('input', () => {
            if (navSearchInput) navSearchInput.value = searchInput.value;
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => fetchProducts(), 300);
        });
    }

    if (navSearchInput) {
        navSearchInput.addEventListener('input', () => {
            if (searchInput) searchInput.value = navSearchInput.value;
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => fetchProducts(), 300);
        });
    }

    if (navSearchForm) {
        navSearchForm.addEventListener('submit', (e) => {
            e.preventDefault();
            fetchProducts();
        });
    }

    sortOptions.forEach(option => {
        option.addEventListener('click', function(e) {
            e.preventDefault();

            sortOptions.forEach(opt => opt.classList.remove('bg-light', 'fw-bold'));
            this.classList.add('bg-light', 'fw-bold');

            sortDropdownLabel.textContent = this.textContent.trim();
            fetchProducts();
        });
    });

    categoryLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            categoryLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
            fetchProducts();
        });
    });

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