@extends('layouts.admin')

@section('content')
<style>
    .metric-card { border-radius: 0.75rem; border: none; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04); }
    .tracking-wide { letter-spacing: 0.05em; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4 pb-2">
    <div>
        <h3 class="fw-bold text-dark mb-1">Edit Shoe</h3>
        <p class="text-muted small mb-0">Update details for <span class="fw-bold text-dark">{{ $product->name }}</span>.</p>
    </div>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-medium">
        &larr; Back to Inventory
    </a>
</div>

<form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row g-4 mb-5">
        {{-- Left Column: Main Details --}}
        <div class="col-lg-8">
            <div class="card metric-card bg-white h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                    <h6 class="fw-bold text-dark mb-0">Product Information</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-uppercase small fw-bold text-muted tracking-wide ms-2">Shoe Name</label>
                            <input type="text" name="name" class="form-control rounded-pill bg-light border-0 px-4 py-2" value="{{ old('name', $product->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-uppercase small fw-bold text-muted tracking-wide ms-2">Price (₱)</label>
                            <input type="number" step="0.01" name="price" class="form-control rounded-pill bg-light border-0 px-4 py-2" value="{{ old('price', $product->price) }}" required>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-uppercase small fw-bold text-muted tracking-wide ms-2">Brand</label>
                            <select name="brand_id" class="form-select rounded-pill bg-light border-0 px-4 py-2 text-dark" required>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ (old('brand_id', $product->brand_id) == $brand->id) ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-uppercase small fw-bold text-muted tracking-wide ms-2">Category</label>
                            <select name="category_id" class="form-select rounded-pill bg-light border-0 px-4 py-2 text-dark" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ (old('category_id', $product->category_id) == $category->id) ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="form-label text-uppercase small fw-bold text-muted tracking-wide ms-2">Description</label>
                        <textarea name="description" class="form-control rounded-4 bg-light border-0 p-4" rows="5">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Media & Actions --}}
        <div class="col-lg-4">
            {{-- Unified Image Card --}}
            <div class="card metric-card bg-white mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                    <h6 class="fw-bold text-dark mb-0">Product Images</h6>
                </div>
                <div class="card-body p-4 text-center">
                    
                    {{-- Display Current Images --}}
                    <div class="d-flex flex-wrap justify-content-center gap-3 mb-4">
                        {{-- Main Image --}}
                        <div class="position-relative mt-2">
                            <span class="position-absolute top-0 start-50 translate-middle badge rounded-pill bg-dark" style="font-size: 0.6rem;">Main</span>
                            <img src="{{ $product->image_path }}" class="rounded-3 shadow-sm border border-primary border-2" style="width: 75px; height: 75px; object-fit: cover; background: #f8f9fa;">
                        </div>

                        {{-- Gallery Images --}}
                        @foreach($product->images as $img)
                            <div class="position-relative mt-2">
                                <img src="{{ asset('storage/' . $img->image_path) }}" class="rounded-3 shadow-sm border border-light-subtle" style="width: 75px; height: 75px; object-fit: cover; background: #f8f9fa;">
                                <button type="button" form="delete-gallery-{{ $img->id }}" onclick="if(confirm('Remove this gallery image?')) document.getElementById('delete-gallery-{{ $img->id }}').submit();" class="btn btn-danger btn-sm rounded-circle p-0 position-absolute shadow-sm d-flex align-items-center justify-content-center" style="width: 22px; height: 22px; top: -6px; right: -6px;">
                                    <i class="bi bi-x" style="font-size: 0.9rem;"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <hr class="border-light-subtle my-4">
                    
                    <div class="text-start">
                        <label class="form-label fw-bold text-uppercase small text-muted tracking-wide ms-2">Add More Images</label>
                        <input type="file" name="images[]" class="form-control rounded-pill bg-light border-0 px-3 py-2" accept="image/*" multiple>
                        <small class="text-muted ms-2 mt-2 d-block lh-sm" style="font-size: 0.75rem;">Uploading files here will add them to your product gallery.</small>
                    </div>
                </div>
            </div>

            {{-- Status & Save Card --}}
            <div class="card metric-card bg-white">
                <div class="card-body p-4">
                    <div class="bg-light rounded-4 p-3 mb-4 d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Active Status</h6>
                            <p class="text-muted small mb-0 lh-sm">Show on public storefront.</p>
                        </div>
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input fs-4 m-0" type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }} style="cursor: pointer;">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-dark rounded-pill w-100 fw-bold py-3 d-flex justify-content-center align-items-center gap-2 shadow-sm">
                        <i class="bi bi-save"></i> Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- Hidden Delete Forms for Gallery Images --}}
@foreach($product->images as $img)
    <form id="delete-gallery-{{ $img->id }}" action="{{ route('admin.products.gallery.destroy', $img->id) }}" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
@endforeach
@endsection