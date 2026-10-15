@extends('layouts.admin')

@section('content')
<style>
    .metric-card {
        border-radius: 1rem;
        border: none;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }

    .table-custom th {
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        color: #6c757d;
        font-weight: 700;
        border-top: none;
        padding-bottom: 1rem;
        border-bottom: 1px solid #edf2f7;
    }

    .table-custom td {
        vertical-align: middle;
        font-size: 0.85rem;
        font-weight: 500;
        color: #1a202c;
        border-bottom: 1px solid #edf2f7;
        padding: 1rem 0.5rem;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .action-btn:hover {
        transform: translateY(-2px);
    }

    /* STOREFRONT-IDENTICAL PAGINATION STYLING */
    .pagination-wrapper {
        background: #ffffff;
        border-top: 1px solid #edf2f7;
        padding: 1rem 1.5rem;
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

    /* General Page Link Buttons */
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

    /* Enlarged Icon Styling for Previous & Next Buttons */
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

<div class="container-fluid px-4 py-4">

    {{-- Top Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1">Shoes & Inventory</h3>
            <p class="text-muted small mb-0">Manage your product catalog and stock variants.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-dark rounded-pill px-4 py-2 fw-medium d-inline-flex align-items-center gap-2 shadow-sm">
            <i class="bi bi-plus-lg"></i>
            <span>Add New Shoe</span>
        </a>
    </div>

    {{-- Alert Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4 p-3 d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill fs-5 me-3 text-success"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Search & Filter Toolbar --}}
    <div class="card metric-card bg-white p-3 mb-4">
        <form action="{{ route('admin.products.index') }}" method="GET" class="row g-3 align-items-center">
            
            {{-- Search Bar --}}
            <div class="col-md-5">
                <div class="input-group border border-light-subtle rounded-pill shadow-sm overflow-hidden bg-light">
                    <span class="input-group-text bg-light border-0 ps-3 text-muted">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control bg-light border-0 py-2 text-dark shadow-none" placeholder="Search shoe name, model..." value="{{ request('search') }}">
                </div>
            </div>

            {{-- Brand Filter --}}
            @if(isset($brands) && $brands->count() > 0)
            <div class="col-md-3">
                <select name="brand_id" class="form-select rounded-pill bg-light border border-light-subtle px-3 py-2 text-dark shadow-sm" onchange="this.form.submit()">
                    <option value="">All Brands</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            {{-- Category Filter --}}
            @if(isset($categories) && $categories->count() > 0)
            <div class="col-md-3">
                <select name="category_id" class="form-select rounded-pill bg-light border border-light-subtle px-3 py-2 text-dark shadow-sm" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            {{-- Reset Filters --}}
            <div class="col-md-1 text-end ms-auto">
                @if(request('search') || request('brand_id') || request('category_id'))
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-danger rounded-pill w-100 py-2" title="Reset Filters">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>

        </form>
    </div>

    {{-- Inventory Table Card --}}
    <div class="card metric-card bg-white overflow-hidden">
        <div class="card-body px-4 pb-4 pt-3">
            <div class="table-responsive">
                <table class="table table-custom mb-0 align-middle">
                    <thead>
                        <tr>
                            <th class="ps-1">Image</th>
                            <th>Product Name</th>
                            <th>Brand</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th class="text-end pe-1">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td class="ps-1">
                                    <img src="{{ $product->image_path }}" alt="{{ $product->name }}" class="rounded-3 shadow-sm border border-light-subtle" style="width: 50px; height: 50px; object-fit: cover;">
                                </td>
                                <td class="fw-bold text-dark">{{ $product->name }}</td>
                                <td>{{ $product->brand->name ?? 'N/A' }}</td>
                                <td>{{ $product->category->name ?? 'N/A' }}</td>
                                <td class="fw-bold text-dark">₱{{ number_format($product->price, 2) }}</td>
                                <td>
                                    @if($product->is_active)
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 fw-medium" style="font-size: 0.75rem;">Active</span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-1 fw-medium" style="font-size: 0.75rem;">Draft</span>
                                    @endif
                                </td>

                                {{-- Action Column --}}
                                <td class="text-end pe-1">
                                    <div class="d-flex justify-content-end align-items-center gap-1">
                                        <!-- Variants Button -->
                                        <a href="{{ route('admin.products.variants.index', $product->id) }}" class="btn btn-sm btn-dark rounded-pill px-3 fw-medium d-inline-flex align-items-center gap-1" style="font-size: 0.75rem;">
                                            <i class="bi bi-layers"></i>
                                            <span>Variants</span>
                                        </a>

                                        <!-- Edit Button -->
                                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-secondary rounded-circle action-btn shadow-sm" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <!-- Delete Button -->
                                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to completely delete this shoe?');" class="m-0 p-0 d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle action-btn shadow-sm" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-box-seam fs-2 d-block mb-3 text-light"></i>
                                    No products found in inventory.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination Footer matching Storefront --}}
        @if(method_exists($products, 'hasPages') && $products->hasPages())
            <div class="card-footer bg-white pagination-wrapper">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Format pagination arrows to match storefront Bootstrap chevron icons
    const prevItem = document.querySelector('.pagination .page-item:first-child .page-link, .pagination .page-item:first-child span');
    if (prevItem) {
        prevItem.innerHTML = '<i class="bi bi-chevron-left"></i>';
    }

    const nextItem = document.querySelector('.pagination .page-item:last-child .page-link, .pagination .page-item:last-child span');
    if (nextItem) {
        nextItem.innerHTML = '<i class="bi bi-chevron-right"></i>';
    }
});
</script>
@endsection