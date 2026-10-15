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
            <input type="text" id="searchInput" class="form-control rounded-pill bg-white border-light-subtle" placeholder="Search shoe name or brand..." value="{{ request('search') }}">
        </div>

        <div class="d-flex gap-2 overflow-auto pb-2 pb-md-0" id="categoryFilters">
            <button class="btn {{ !request('category') ? 'btn-dark' : 'btn-outline-secondary bg-white text-dark' }} rounded-pill px-4 filter-btn border-light-subtle" data-category="">All Shoes</button>
            @foreach($categories as $category)
            <button class="btn {{ request('category') == $category->id ? 'btn-dark' : 'btn-outline-secondary bg-white text-dark' }} rounded-pill px-4 border-light-subtle filter-btn" data-category="{{ $category->id }}">{{ $category->name }}</button>
            @endforeach
        </div>

        <div>
            <select id="sortSelect" class="form-select rounded-pill bg-white border-light-subtle w-auto">
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Sort by: Featured</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
            </select>
        </div>
    </div>

    <!-- Showing Products Text -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <p class="text-muted small fw-medium mb-0" id="showingText">
            Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
        </p>
    </div>

    <!-- Product Grid Wrapper (For AJAX Replacement) -->
    <div id="productGridWrapper">
        <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
            @forelse($products as $product)
            <div class="col">
                {{-- Make the entire card clickable by wrapping it in an anchor tag --}}
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
                <p class="text-muted">Try adjusting your search or category filters.</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $products->links() }}
        </div>
    </div>

</div>

<!-- Real-Time AJAX Search & Filter Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const filterBtns = document.querySelectorAll('.filter-btn');
    const sortSelect = document.getElementById('sortSelect');
    const gridWrapper = document.getElementById('productGridWrapper');
    const showingText = document.getElementById('showingText');

    let debounceTimer;

    function fetchProducts() {
        // Build the URL with current parameters
        let url = new URL(window.location.href);
        
        if (searchInput.value) url.searchParams.set('search', searchInput.value);
        else url.searchParams.delete('search');

        const activeBtn = document.querySelector('.filter-btn.btn-dark');
        const category = activeBtn ? activeBtn.dataset.category : '';
        if (category) url.searchParams.set('category', category);
        else url.searchParams.delete('category');

        url.searchParams.set('sort', sortSelect.value);

        // Fetch new data quietly in the background
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => response.text())
            .then(html => {
                // Parse the returned HTML and extract just the grid and text
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                gridWrapper.innerHTML = doc.getElementById('productGridWrapper').innerHTML;
                showingText.innerHTML = doc.getElementById('showingText').innerHTML;
                
                // Update the browser URL without reloading
                window.history.pushState({}, '', url);
            });
    }

    // Listen for typing in the search bar (with a 300ms delay so it doesn't fire on every single keystroke)
    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(fetchProducts, 300);
    });

    // Listen for sort dropdown changes
    sortSelect.addEventListener('change', fetchProducts);

    // Listen for category button clicks
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Toggle active styling
            filterBtns.forEach(b => {
                b.classList.remove('btn-dark');
                b.classList.add('btn-outline-secondary', 'bg-white', 'text-dark');
            });
            this.classList.remove('btn-outline-secondary', 'bg-white', 'text-dark');
            this.classList.add('btn-dark');
            
            fetchProducts();
        });
    });
});
</script>
@endsection