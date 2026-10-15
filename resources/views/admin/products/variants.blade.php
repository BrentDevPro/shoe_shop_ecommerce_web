@extends('layouts.admin')

@section('content')
<style>
    .metric-card { border-radius: 0.75rem; border: none; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04); }
    .table-custom th { text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.5px; color: #6c757d; font-weight: 700; border-top: none; padding-bottom: 1rem; border-bottom: 1px solid #edf2f7; }
    .table-custom td { vertical-align: middle; font-size: 0.85rem; font-weight: 500; color: #1a202c; border-bottom: 1px solid #edf2f7; padding: 1rem 0.5rem; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4 pb-2">
    <div>
        <h3 class="fw-bold text-dark mb-1">Manage Variants</h3>
        <p class="text-muted small mb-0">Add sizes, colors, and manage stock for <span class="fw-bold text-dark">{{ $product->name }}</span>.</p>
    </div>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-medium">
        &larr; Back to Products
    </a>
</div>

{{-- Alerts --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-4">
    {{-- Top Section: Add New Variant Form --}}
    <div class="col-12">
        <div class="card metric-card bg-white p-4">
            <h6 class="fw-bold text-dark mb-4"><i class="bi bi-plus-circle me-2 text-primary"></i>Add New Size / Color</h6>
            
            <form action="{{ route('admin.products.variants.store', $product->id) }}" method="POST">
                @csrf
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold text-uppercase tracking-wide">Color</label>
                        <input type="text" name="color" class="form-control rounded-pill bg-light border-0 px-3" placeholder="e.g. Red/White" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold text-uppercase tracking-wide">Size (EU/US)</label>
                        <input type="text" name="size" class="form-control rounded-pill bg-light border-0 px-3" placeholder="e.g. 41 or 9.5" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-muted small fw-bold text-uppercase tracking-wide">Initial Stock</label>
                        <input type="number" name="stock_quantity" class="form-control rounded-pill bg-light border-0 px-3" value="0" min="0" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-dark rounded-pill w-100 fw-medium">Add Variant</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Bottom Section: List of Existing Variants --}}
    <div class="col-12">
        <div class="card metric-card bg-white overflow-hidden">
            <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                <h6 class="fw-bold text-dark mb-0">Current Inventory Details</h6>
            </div>
            
            <div class="card-body px-4 pb-4 pt-2">
                <div class="table-responsive">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th class="ps-1">Color</th>
                                <th>Size</th>
                                <th>Current Stock</th>
                                <th class="text-end pe-1">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($variants as $variant)
                                <tr>
                                    <td class="ps-1 fw-bold">{{ $variant->color }}</td>
                                    <td>{{ $variant->size }}</td>
                                    <td>
                                        {{-- Inline form to quickly update stock without a separate page --}}
                                        <form action="{{ route('admin.products.variants.update', [$product->id, $variant->id]) }}" method="POST" class="d-flex align-items-center gap-2" style="max-width: 150px;">
                                            @csrf
                                            @method('PUT')
                                            <input type="number" name="stock_quantity" class="form-control form-control-sm text-center rounded-pill bg-light border-0" value="{{ $variant->stock_quantity }}" min="0">
                                            <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3">Update</button>
                                        </form>
                                    </td>
                                    <td class="text-end pe-1">
                                        {{-- Delete Button --}}
                                        <form action="{{ route('admin.products.variants.destroy', [$product->id, $variant->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this variant?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="bi bi-box-seam fs-2 d-block mb-2 text-light"></i>
                                        No variants found. Add a size above to start selling this shoe!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection