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
                    
                    <div id="existingImageChoices" class="d-flex flex-wrap justify-content-center gap-3 mb-3">
                        <button type="button" class="main-image-choice btn p-0 position-relative mt-2 border border-primary border-2 bg-transparent" data-main-image-id="" aria-pressed="true" title="Set as main photo" style="width: 75px; height: 75px;">
                            <span class="main-image-badge position-absolute top-0 start-50 translate-middle badge rounded-pill bg-dark" style="font-size: 0.6rem;">Main</span>
                            <img src="{{ $product->image_url }}" alt="Current main product photo" class="rounded-3 shadow-sm w-100 h-100" style="object-fit: cover; background: #f8f9fa;">
                        </button>

                        @foreach($product->images as $img)
                            <div class="position-relative mt-2">
                                <button type="button" class="main-image-choice btn p-0 position-relative border border-light-subtle bg-transparent" data-main-image-id="{{ $img->id }}" aria-pressed="false" title="Set as main photo" style="width: 75px; height: 75px;">
                                    <span class="main-image-badge position-absolute top-0 start-50 translate-middle badge rounded-pill bg-dark d-none" style="font-size: 0.6rem;">Main</span>
                                    <img src="{{ $img->image_url }}" alt="Product photo" class="rounded-3 shadow-sm w-100 h-100" style="object-fit: cover; background: #f8f9fa;">
                                </button>
                                <button type="button" form="delete-gallery-{{ $img->id }}" onclick="if(confirm('Remove this gallery image?')) document.getElementById('delete-gallery-{{ $img->id }}').submit();" class="btn btn-danger btn-sm rounded-circle p-0 position-absolute shadow-sm d-flex align-items-center justify-content-center" style="width: 22px; height: 22px; top: -6px; right: -6px;">
                                    <i class="bi bi-x" style="font-size: 0.9rem;"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <div id="previewContainer" class="d-flex flex-wrap justify-content-center gap-3 mb-4"></div>

                    <hr class="border-light-subtle my-4">

                    <div class="text-start">
                        <label for="imageInput" class="form-label fw-bold text-uppercase small text-muted tracking-wide ms-2">Add Product Images</label>
                        <input type="file" name="images[]" id="imageInput" class="form-control rounded-pill bg-light border-0 px-3 py-2" accept="image/*" multiple>
                        <input type="hidden" name="main_image_id" id="mainImageId">
                        <input type="hidden" name="main_image_index" id="mainImageIndex">
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

<script>
    const imageInput = document.getElementById('imageInput');
    const previewContainer = document.getElementById('previewContainer');
    const mainImageId = document.getElementById('mainImageId');
    const mainImageIndex = document.getElementById('mainImageIndex');
    const existingMainChoice = document.querySelector('.main-image-choice[data-main-image-id=""]');
    let selectedMainChoice = existingMainChoice;
    let previewUrls = [];

    function selectMainImage(choice) {
        selectedMainChoice = choice;

        document.querySelectorAll('.main-image-choice, .new-image-choice').forEach((imageChoice) => {
            const selected = imageChoice === choice;
            imageChoice.classList.toggle('border-primary', selected);
            imageChoice.classList.toggle('border-2', selected);
            imageChoice.classList.toggle('border-light-subtle', !selected);
            imageChoice.setAttribute('aria-pressed', selected ? 'true' : 'false');
            imageChoice.querySelector('.main-image-badge').classList.toggle('d-none', !selected);
        });

        if (choice.dataset.mainImageId !== undefined) {
            mainImageId.value = choice.dataset.mainImageId;
            mainImageIndex.value = '';
        } else {
            mainImageId.value = '';
            mainImageIndex.value = choice.dataset.mainImageIndex;
        }
    }

    document.querySelectorAll('.main-image-choice').forEach((choice) => {
        choice.addEventListener('click', () => selectMainImage(choice));
    });

    imageInput.addEventListener('change', (event) => {
        previewUrls.forEach((url) => URL.revokeObjectURL(url));
        previewUrls = [];
        previewContainer.replaceChildren();
        selectMainImage(existingMainChoice);

        Array.from(event.target.files).forEach((file, index) => {
            const imageUrl = URL.createObjectURL(file);
            previewUrls.push(imageUrl);

            const choice = document.createElement('button');
            choice.type = 'button';
            choice.className = 'new-image-choice btn p-0 position-relative mt-2 border border-light-subtle bg-transparent';
            choice.dataset.mainImageIndex = index;
            choice.setAttribute('aria-pressed', 'false');
            choice.setAttribute('title', 'Set as main photo');
            choice.style.width = '75px';
            choice.style.height = '75px';

            const badge = document.createElement('span');
            badge.className = 'main-image-badge position-absolute top-0 start-50 translate-middle badge rounded-pill bg-dark d-none';
            badge.style.fontSize = '0.6rem';
            badge.textContent = 'Main';

            const image = document.createElement('img');
            image.src = imageUrl;
            image.alt = file.name;
            image.className = 'rounded-3 shadow-sm w-100 h-100';
            image.style.objectFit = 'cover';
            image.style.background = '#f8f9fa';

            choice.append(badge, image);
            choice.addEventListener('click', () => selectMainImage(choice));
            previewContainer.append(choice);
        });
    });
</script>
@endsection