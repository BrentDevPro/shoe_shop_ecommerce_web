@extends('layouts.admin')

@section('content')
<style>
    .metric-card { border-radius: 0.75rem; border: none; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04); }
    .tracking-wide { letter-spacing: 0.05em; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4 pb-2">
    <div>
        <h3 class="fw-bold text-dark mb-1">Add New Shoe</h3>
        <p class="text-muted small mb-0">List a new footwear model in your catalog.</p>
    </div>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-medium">
        &larr; Back to Inventory
    </a>
</div>

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row g-4 mb-5">
        <div class="col-lg-8">
            <div class="card metric-card bg-white h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                    <h6 class="fw-bold text-dark mb-0">Product Information</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-uppercase small fw-bold text-muted tracking-wide ms-2">Shoe Name</label>
                            <input type="text" name="name" class="form-control rounded-pill bg-light border-0 px-4 py-2 @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g. Air Max Ultra" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-uppercase small fw-bold text-muted tracking-wide ms-2">Price (₱)</label>
                            <input type="number" step="0.01" name="price" class="form-control rounded-pill bg-light border-0 px-4 py-2 @error('price') is-invalid @enderror" value="{{ old('price') }}" placeholder="e.g. 4500.00" required>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-uppercase small fw-bold text-muted tracking-wide ms-2">Brand</label>
                            <select name="brand_id" class="form-select rounded-pill bg-light border-0 px-4 py-2 text-dark" required style="cursor: pointer;">
                                <option value="" disabled selected>Select a brand...</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-uppercase small fw-bold text-muted tracking-wide ms-2">Category</label>
                            <select name="category_id" class="form-select rounded-pill bg-light border-0 px-4 py-2 text-dark" required style="cursor: pointer;">
                                <option value="" disabled selected>Select a category...</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="form-label text-uppercase small fw-bold text-muted tracking-wide ms-2">Description</label>
                        <textarea name="description" class="form-control rounded-4 bg-light border-0 p-4 @error('description') is-invalid @enderror" rows="5" placeholder="Describe the shoe's features...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card metric-card bg-white mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                    <h6 class="fw-bold text-dark mb-0">Product Images</h6>
                </div>
                <div class="card-body p-4 text-center">
                    
                    <div id="imagePlaceholder" class="bg-light rounded-4 border border-light-subtle d-flex align-items-center justify-content-center mb-4 mx-auto" style="width: 100%; height: 160px;">
                        <div class="text-muted">
                            <i class="bi bi-images d-block mb-2" style="font-size: 2.5rem;"></i>
                            <span class="small fw-medium">No images selected</span>
                        </div>
                    </div>

                    <div id="previewContainer" class="d-flex flex-wrap justify-content-center gap-2 mb-4"></div>
                    
                    <div class="text-start">
                        <label class="form-label fw-bold text-uppercase small text-muted tracking-wide ms-2">Upload File(s)</label>
                        <input type="file" name="images[]" id="imageInput" class="form-control rounded-pill bg-light border-0 px-3 py-2 @error('images') is-invalid @enderror" accept="image/*" multiple required>
                        <small class="text-muted ms-2 mt-2 d-block lh-sm" style="font-size: 0.75rem;">Hold CTRL/CMD to select multiple files at once. The first image will be the Main thumbnail.</small>
                    </div>
                </div>
            </div>

            <div class="card metric-card bg-white">
                <div class="card-body p-4">
                    <div class="bg-light rounded-4 p-3 mb-4 d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Active Status</h6>
                            <p class="text-muted small mb-0 lh-sm">Show on public storefront.</p>
                        </div>
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input fs-4 m-0" type="checkbox" name="is_active" value="1" checked style="cursor: pointer;">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-dark rounded-pill w-100 fw-bold py-3 d-flex justify-content-center align-items-center gap-2 shadow-sm">
                        <i class="bi bi-plus-circle"></i> Create Shoe
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    document.getElementById('imageInput').addEventListener('change', function(event) {
        const files = event.target.files;
        const previewContainer = document.getElementById('previewContainer');
        const placeholder = document.getElementById('imagePlaceholder');
        
        previewContainer.innerHTML = ''; 

        if (files.length > 0) {
            placeholder.classList.add('d-none');
            
            Array.from(files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let badge = index === 0 ? '<span class="position-absolute top-0 start-50 translate-middle badge rounded-pill bg-dark" style="font-size: 0.6rem;">Main</span>' : '';
                    let border = index === 0 ? 'border-primary border-2' : 'border-light-subtle';
                    
                    let imgHtml = `
                        <div class="position-relative mt-2">
                            ${badge}
                            <img src="${e.target.result}" class="rounded-3 shadow-sm border ${border}" style="width: 75px; height: 75px; object-fit: cover; background: #f8f9fa;">
                        </div>
                    `;
                    previewContainer.innerHTML += imgHtml;
                }
                reader.readAsDataURL(file);
            });
        } else {
            placeholder.classList.remove('d-none');
        }
    });
</script>
@endsection