@extends('layouts.admin')

@section('content')
<style>
    .variant-table tbody tr {
        transition: background-color 0.15s ease-in-out;
    }
    .variant-table tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.015);
    }
    .action-btn {
        width: 36px;
        height: 36px;
        transition: all 0.2s ease;
    }
    .action-btn:hover {
        transform: translateY(-2px);
    }
</style>

<div class="container-fluid px-4 py-4">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 text-muted small mb-1">
                <a href="{{ route('admin.products.index') }}" class="text-decoration-none text-muted">Products</a>
                <i class="bi bi-chevron-right text-muted" style="font-size: 0.75rem;"></i>
                <span class="text-dark fw-semibold">Manage Variants</span>
            </div>
            <h2 class="fw-bold text-dark tracking-tight mb-0">Manage Variants</h2>
        </div>
        <div>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-dark rounded-pill px-4 py-2 fw-medium shadow-sm d-inline-flex align-items-center gap-2">
                <i class="bi bi-arrow-left"></i>
                <span>Back to Products</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4 p-3 d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill fs-5 me-3 text-success"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4 p-3 d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5 me-3 text-danger"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-4 overflow-hidden border bg-light d-flex align-items-center justify-content-center shadow-sm" style="width: 75px; height: 75px; flex-shrink: 0;">
                    @if(isset($product->image_path) && $product->image_path)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-100 h-100 object-fit-cover">
                    @else
                        <i class="bi bi-box-seam fs-2 text-muted"></i>
                    @endif
                </div>
                <div>
                    <span class="badge bg-light text-secondary rounded-pill px-3 py-1 mb-1 border fw-medium">
                        {{ $product->category->name ?? 'Product Item' }}
                    </span>
                    <h4 class="fw-bold text-dark mb-1">{{ $product->name }}</h4>
                    <p class="text-muted small mb-0">Base Catalog Price: <strong class="text-dark fw-bold">₱{{ number_format($product->price ?? 0, 2) }}</strong></p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="bg-light rounded-4 px-4 py-3 text-center border border-light-subtle">
                    <span class="text-muted small d-block text-uppercase fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Configured Variants</span>
                    <span class="fs-4 fw-bold text-dark font-monospace">{{ $variants->count() }}</span>
                </div>
                <div class="bg-light rounded-4 px-4 py-3 text-center border border-light-subtle">
                    <span class="text-muted small d-block text-uppercase fw-bold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Total Inventory</span>
                    <span class="fs-4 fw-bold text-danger font-monospace">{{ $variants->sum('stock_quantity') }} <small class="fs-6 fw-normal text-muted">units</small></span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4 sticky-top" style="top: 20px; z-index: 5;">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px;">
                        <i class="bi bi-plus-lg fs-5"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-0">Add Variant</h5>
                        <small class="text-muted">Set color, size, and initial stock</small>
                    </div>
                </div>

                <hr class="border-light-subtle my-3">

                <form action="{{ route('admin.products.variants.store', $product->id) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="color" class="form-label small fw-bold text-muted text-uppercase" style="font-size: 0.75rem;">Colorway</label>
                        <div class="input-group border border-light-subtle rounded-pill shadow-sm overflow-hidden bg-light">
                            <span class="input-group-text bg-light border-0 ps-3 text-muted">
                                <i class="bi bi-palette"></i>
                            </span>
                            <input type="text" name="color" id="color" class="form-control bg-light border-0 py-2 text-dark shadow-none @error('color') is-invalid @enderror" placeholder="e.g. Red/White" value="{{ old('color') }}" required>
                        </div>
                        @error('color')
                            <div class="invalid-feedback d-block ms-2 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="size" class="form-label small fw-bold text-muted text-uppercase" style="font-size: 0.75rem;">Size (EU / US)</label>
                        <div class="input-group border border-light-subtle rounded-pill shadow-sm overflow-hidden bg-light">
                            <span class="input-group-text bg-light border-0 ps-3 text-muted">
                                <i class="bi bi-ruler"></i>
                            </span>
                            <input type="text" name="size" id="size" class="form-control bg-light border-0 py-2 text-dark shadow-none @error('size') is-invalid @enderror" placeholder="e.g. 41 or 9.5" value="{{ old('size') }}" required>
                        </div>
                        @error('size')
                            <div class="invalid-feedback d-block ms-2 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="stock_quantity" class="form-label small fw-bold text-muted text-uppercase" style="font-size: 0.75rem;">Initial Stock</label>
                        <div class="input-group border border-light-subtle rounded-pill shadow-sm overflow-hidden bg-light">
                            <span class="input-group-text bg-light border-0 ps-3 text-muted">
                                <i class="bi bi-layers"></i>
                            </span>
                            <input type="number" name="stock_quantity" id="stock_quantity" min="0" class="form-control bg-light border-0 py-2 text-dark shadow-none @error('stock_quantity') is-invalid @enderror" placeholder="0" value="{{ old('stock_quantity', 0) }}" required>
                        </div>
                        @error('stock_quantity')
                            <div class="invalid-feedback d-block ms-2 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-dark w-100 rounded-pill py-2.5 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-check2-circle fs-5"></i>
                        <span>Add Variant</span>
                    </button>
                </form>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-light-subtle">
                    <div>
                        <h5 class="fw-bold text-dark mb-0">Current Inventory Details</h5>
                        <p class="text-muted small mb-0">Manage sizes, colors, and stock counts via the action modal</p>
                    </div>
                    <span class="badge bg-light text-dark border rounded-pill px-3 py-2 font-monospace fw-semibold">{{ $variants->count() }} Variants</span>
                </div>

                <div class="table-responsive">
                    <table class="table variant-table align-middle mb-0">
                        <thead>
                            <tr class="bg-light">
                                <th class="border-0 rounded-start-pill text-uppercase small fw-bold text-muted py-3 ps-3">Color</th>
                                <th class="border-0 text-uppercase small fw-bold text-muted py-3">Size</th>
                                <th class="border-0 text-uppercase small fw-bold text-muted py-3">Status</th>
                                <th class="border-0 text-uppercase small fw-bold text-muted py-3">Stock</th>
                                <th class="border-0 rounded-end-pill text-uppercase small fw-bold text-muted py-3 text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($variants as $variant)
                                <tr>
                                    <td class="ps-3 py-3 fw-bold text-dark">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="rounded-circle bg-dark d-inline-block" style="width: 8px; height: 8px;"></span>
                                            <span>{{ $variant->color }}</span>
                                        </div>
                                    </td>

                                    <td class="py-3">
                                        <span class="badge bg-dark rounded-pill px-3 py-2 font-monospace fs-6">{{ $variant->size }}</span>
                                    </td>

                                    <td class="py-3">
                                        @if($variant->stock_quantity > 10)
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2 fw-medium d-inline-flex align-items-center gap-1">
                                                <i class="bi bi-check-circle-fill"></i> In Stock
                                            </span>
                                        @elseif($variant->stock_quantity > 0)
                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-3 py-2 fw-medium d-inline-flex align-items-center gap-1">
                                                <i class="bi bi-exclamation-triangle-fill"></i> Low Stock
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-2 fw-medium d-inline-flex align-items-center gap-1">
                                                <i class="bi bi-x-circle-fill"></i> Out of Stock
                                            </span>
                                        @endif
                                    </td>

                                    <td class="py-3">
                                        <div class="d-flex align-items-center gap-1">
                                            <span class="fw-bold text-dark font-monospace fs-6">{{ $variant->stock_quantity }}</span>
                                            <small class="text-muted">units</small>
                                        </div>
                                    </td>

                                    <td class="text-end pe-3 py-3">
                                        <div class="d-flex justify-content-end align-items-center gap-2">
                                            <button type="button" class="btn btn-outline-dark btn-sm rounded-circle action-btn d-inline-flex align-items-center justify-content-center shadow-sm" data-bs-toggle="modal" data-bs-target="#editVariantModal{{ $variant->id }}" title="Edit Variant">
                                                <i class="bi bi-pencil-fill"></i>
                                            </button>

                                            <form action="{{ route('admin.products.variants.destroy', [$product->id, $variant->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this variant?');" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-circle action-btn d-inline-flex align-items-center justify-content-center shadow-sm" title="Remove Variant">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>
                                        </div>

                                        <div class="modal fade" id="editVariantModal{{ $variant->id }}" tabindex="-1" aria-labelledby="editVariantModalLabel{{ $variant->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content rounded-4 border-0 shadow-lg text-start">
                                                    <div class="modal-header border-bottom-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px;">
                                                                <i class="bi bi-pencil-fill fs-6"></i>
                                                            </div>
                                                            <div>
                                                                <h5 class="modal-title fw-bold text-dark mb-0" id="editVariantModalLabel{{ $variant->id }}">Edit Variant Details</h5>
                                                                <small class="text-muted">Update colorway, size, or stock quantity</small>
                                                            </div>
                                                        </div>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('admin.products.variants.update', [$product->id, $variant->id]) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body p-4">
                                                            
                                                            <div class="mb-3">
                                                                <label class="form-label small fw-bold text-muted text-uppercase" style="font-size: 0.75rem;">Colorway</label>
                                                                <div class="input-group border border-light-subtle rounded-pill shadow-sm overflow-hidden bg-light">
                                                                    <span class="input-group-text bg-light border-0 ps-3 text-muted">
                                                                        <i class="bi bi-palette"></i>
                                                                    </span>
                                                                    <input type="text" name="color" class="form-control bg-light border-0 py-2 text-dark shadow-none" value="{{ old('color', $variant->color) }}" required>
                                                                </div>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label small fw-bold text-muted text-uppercase" style="font-size: 0.75rem;">Size Option</label>
                                                                <div class="input-group border border-light-subtle rounded-pill shadow-sm overflow-hidden bg-light">
                                                                    <span class="input-group-text bg-light border-0 ps-3 text-muted">
                                                                        <i class="bi bi-ruler"></i>
                                                                    </span>
                                                                    <input type="text" name="size" class="form-control bg-light border-0 py-2 text-dark shadow-none" value="{{ old('size', $variant->size) }}" required>
                                                                </div>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label small fw-bold text-muted text-uppercase" style="font-size: 0.75rem;">Stock Quantity</label>
                                                                <div class="input-group border border-light-subtle rounded-pill shadow-sm overflow-hidden bg-light">
                                                                    <span class="input-group-text bg-light border-0 ps-3 text-muted">
                                                                        <i class="bi bi-layers"></i>
                                                                    </span>
                                                                    <input type="number" name="stock_quantity" min="0" class="form-control bg-light border-0 py-2 text-dark shadow-none" value="{{ old('stock_quantity', $variant->stock_quantity) }}" required>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="modal-footer border-top-0 pb-4 px-4 pt-0 gap-2">
                                                            <button type="button" class="btn btn-light rounded-pill px-4 fw-medium" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm d-inline-flex align-items-center gap-2">
                                                                <i class="bi bi-check2-circle fs-5"></i>
                                                                <span>Save Changes</span>
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="my-3">
                                            <i class="bi bi-box-seam text-muted display-4 d-block mb-3 opacity-50"></i>
                                            <h5 class="fw-bold text-dark">No variants found</h5>
                                            <p class="text-muted small mb-0">Add a size or color above to start managing inventory for this product.</p>
                                        </div>
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