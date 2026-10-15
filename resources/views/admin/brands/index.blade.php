@extends('layouts.admin')

@section('content')
<style>
    .metric-card { border-radius: 0.75rem; border: none; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04); }
    .table-custom th { text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; color: #6c757d; font-weight: 700; border-top: none; padding-bottom: 1rem; border-bottom: 2px solid #edf2f7; }
    .table-custom td { vertical-align: middle; font-size: 0.9rem; font-weight: 500; color: #1a202c; border-bottom: 1px solid #edf2f7; padding: 1.25rem 0.5rem; }
    .tracking-wide { letter-spacing: 0.05em; }
    .form-control:focus { box-shadow: none; border-color: #dee2e6; }
    .editable-input:focus { border-color: #212529 !important; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4 pb-2">
    <div>
        <h3 class="fw-bold text-dark mb-1">Manage Brands</h3>
        <p class="text-muted small mb-0">Manage shoe manufacturers and brand partnerships.</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4 border-0 bg-success text-white px-4 py-3" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close btn-close-white mt-1 me-2" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm mb-4 border-0 bg-danger text-white px-4 py-3" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close btn-close-white mt-1 me-2" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-4 mb-5">
    {{-- Left Column: Brands Table --}}
    <div class="col-lg-8">
        <div class="card metric-card bg-white h-100 border border-light-subtle">
            <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-2">
                <h6 class="fw-bold text-dark mb-0">Active Brands Overview</h6>
            </div>
            
            <div class="card-body px-4 pb-4 pt-0">
                <div class="table-responsive">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th class="ps-2" style="width: 45%;">Brand Name</th>
                                <th class="text-center" style="width: 30%;">Products Linked</th>
                                <th class="text-end pe-2" style="width: 25%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($brands as $brand)
                                <tr>
                                    <td class="ps-2">
                                        <form action="{{ route('admin.brands.update', $brand->id) }}" method="POST" class="m-0">
                                            @csrf
                                            @method('PUT')
                                            <div class="d-flex align-items-center gap-2">
                                                {{-- Improved Editable Text Field UI --}}
                                                <div class="position-relative">
                                                    <input type="text" name="name" class="form-control editable-input rounded-pill border border-secondary-subtle bg-white fw-bold text-dark ps-3 pe-4 py-1 shadow-sm" value="{{ $brand->name }}" title="Edit brand name" required style="font-size: 0.85rem; width: 190px;">
                                                    <i class="bi bi-pencil-fill position-absolute top-50 end-0 translate-middle-y me-3 text-muted" style="font-size: 0.75rem; pointer-events: none;"></i>
                                                </div>
                                                <button type="submit" class="btn btn-dark btn-sm rounded-pill px-3 py-1 fw-medium shadow-sm">Save</button>
                                            </div>
                                        </form>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill px-3 py-2 fw-medium" style="font-size: 0.75rem;">
                                            <i class="bi bi-box-seam me-1"></i> {{ $brand->products_count }} Shoes
                                        </span>
                                    </td>
                                    <td class="text-end pe-2">
                                        <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to completely remove this brand?');" class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3 py-1 fw-medium d-inline-flex align-items-center gap-2" title="Delete">
                                                <i class="bi bi-trash3"></i> Remove
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                                            <i class="bi bi-award text-muted fs-3"></i>
                                        </div>
                                        <p class="text-muted fw-medium mb-0">No brands found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Column: Add Brand Form --}}
    <div class="col-lg-4">
        <div class="card metric-card bg-white border border-light-subtle sticky-top" style="top: 100px;">
            <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                <h6 class="fw-bold text-dark mb-0">Add New Brand</h6>
            </div>
            <div class="card-body p-4">
                <p class="text-muted small mb-4">Register a new shoe manufacturer to your catalog.</p>
                
                <form action="{{ route('admin.brands.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label text-uppercase small fw-bold text-muted tracking-wide ms-2">Brand Name</label>
                        <input type="text" name="name" class="form-control rounded-pill bg-light border-0 px-4 py-2 @error('name') is-invalid @enderror" placeholder="e.g. Nike, Adidas" value="{{ old('name') }}" required>
                        @error('name') <div class="invalid-feedback ms-3">{{ $message }}</div> @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-dark rounded-pill w-100 fw-bold py-3 d-flex justify-content-center align-items-center gap-2 shadow-sm">
                        <i class="bi bi-plus-circle"></i> Add Brand
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection