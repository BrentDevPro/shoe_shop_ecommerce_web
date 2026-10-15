@extends('layouts.admin')

@section('content')
<style>
  .metric-card {
    border-radius: 0.75rem;
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
</style>

<div class="d-flex justify-content-between align-items-center mb-4 pb-2">
  <div>
    <h3 class="fw-bold text-dark mb-1">Shoes & Inventory</h3>
    <p class="text-muted small mb-0">Manage your product catalog and stock variants.</p>
  </div>
  <a href="{{ route('admin.products.create') }}" class="btn btn-dark rounded-pill px-4 py-2 fw-medium d-flex align-items-center gap-2">
    <i class="bi bi-plus-lg"></i> Add New Shoe
  </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
  <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card metric-card bg-white overflow-hidden">
  <div class="card-body px-4 pb-4 pt-3">
    <div class="table-responsive">
      <table class="table table-custom mb-0">
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
              <img src="{{ $product->image_path }}" alt="{{ $product->name }}" class="rounded shadow-sm border border-light-subtle" style="width: 50px; height: 50px; object-fit: cover;">
            </td>
            <td class="fw-bold text-dark">{{ $product->name }}</td>
            <td>{{ $product->brand->name ?? 'N/A' }}</td>
            <td>{{ $product->category->name ?? 'N/A' }}</td>
            <td class="fw-bold text-dark">₱{{ number_format($product->price, 2) }}</td>
            <td>
              @if($product->is_active)
              <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 fw-medium" style="font-size: 0.7rem;">Active</span>
              @else
              <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-1 fw-medium" style="font-size: 0.7rem;">Draft</span>
              @endif
            </td>

            {{-- Action Column --}}
            <td class="text-end pe-1">
              <div class="d-flex justify-content-end align-items-center gap-1">
                <!-- Variants Button -->
                <a href="{{ route('admin.products.variants.index', $product->id) }}" class="btn btn-sm btn-dark rounded-pill px-3 fw-medium" style="font-size: 0.75rem;">
                  <i class="bi bi-layers me-1"></i> Variants
                </a>

                <!-- REAL Edit Button -->
                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 32px; height: 32px; padding: 0; line-height: 30px;" title="Edit">
                  <i class="bi bi-pencil"></i>
                </a>

                <!-- REAL Delete Button (Requires a form for security) -->
                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to completely delete this shoe?');" class="m-0 p-0">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" style="width: 32px; height: 32px; padding: 0; line-height: 30px;" title="Delete">
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
</div>
@endsection