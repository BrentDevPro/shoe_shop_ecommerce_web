@extends('layouts.store')

@section('content')
<style>
    .tracking-wide { letter-spacing: 0.05em; }
    .form-control:focus, .form-select:focus { box-shadow: none; border-color: #dee2e6; }
    
    .thumbnail-wrapper { height: 80px; cursor: pointer; transition: all 0.2s ease; border: 2px solid transparent; background-color: #f8f9fa; }
    .thumbnail-wrapper:hover { border-color: #adb5bd; }
    .thumbnail-wrapper.active-thumb { border-color: #212529; }
    .thumbnail-wrapper img { width: 100%; height: 100%; object-fit: contain; padding: 0.25rem; }
    .main-image-container { height: 450px; display: flex; align-items: center; justify-content: center; overflow: hidden; background-color: #f8f9fa; cursor: crosshair; }
    #mainImage { max-height: 100%; max-width: 100%; object-fit: contain; transition: transform 0.3s ease; transform-origin: center center; }
</style>

<div class="container my-5">
    <div class="mb-4">
        <span class="text-muted small">Store / {{ $product->category->name ?? 'Category' }} / <span class="text-dark fw-bold">{{ $product->name }}</span></span>
    </div>

    @if(session('success'))
        <div class="alert alert-success rounded-4" role="status">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger rounded-4" role="alert">{{ session('error') }}</div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
        <div class="row g-5 align-items-center">
            
            <div class="col-lg-6">
                <div class="d-flex gap-3">
                    <div class="d-flex flex-column gap-2" style="width: 80px;">
                        <div class="thumbnail-wrapper active-thumb rounded-3 overflow-hidden shadow-sm gallery-thumb" data-image="{{ $product->image_url }}">
                            <img src="{{ $product->image_url }}" alt="Thumbnail">
                        </div>
                        
                        @foreach($product->images as $galleryImg)
                            <div class="thumbnail-wrapper rounded-3 overflow-hidden shadow-sm border-transparent gallery-thumb" data-image="{{ $galleryImg->image_url }}">
                                <img src="{{ $galleryImg->image_url }}" alt="Thumbnail">
                            </div>
                        @endforeach
                    </div>

                    <div class="flex-grow-1 position-relative">
                        <div class="main-image-container rounded-4 shadow-sm border border-light-subtle" id="imageContainer">
                            <img src="{{ $product->image_url }}" id="mainImage" alt="{{ $product->name }}">
                        </div>
                        
                        <div class="mt-3 d-flex justify-content-center align-items-center gap-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;" onclick="zoomOut()" title="Zoom Out">
                                <i class="bi bi-zoom-out"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-light rounded-pill px-3 fw-medium text-muted border shadow-sm" onclick="resetZoom()">
                                <i class="bi bi-arrows-angle-contract me-1"></i> Reset Zoom
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;" onclick="zoomIn()" title="Zoom In">
                                <i class="bi bi-zoom-in"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 ps-lg-4">
                <h6 class="text-danger fw-bold text-uppercase tracking-wide small mb-2">{{ $product->brand->name ?? 'Brand' }}</h6>
                <h1 class="fw-bold text-dark mb-3 display-5" style="letter-spacing: -0.03em;">{{ $product->name }}</h1>
                
                <div class="mb-4">
                    <span class="badge bg-light text-dark border rounded-pill px-3 py-2 fw-medium">
                        <i class="bi bi-tag me-1"></i> {{ $product->category->name ?? 'Category' }}
                    </span>
                </div>

                <h2 class="fw-bold text-dark mb-1">₱{{ number_format($product->price, 2) }}</h2>
                <p class="text-muted small mb-4">Local delivery and taxes included.</p>

                <p class="text-muted lh-lg mb-5" style="font-size: 0.95rem;">{{ $product->description }}</p>

                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <div class="row g-3 mb-4">
                        <div class="col-8">
                            <label class="form-label fw-bold text-uppercase small text-dark tracking-wide" style="font-size: 0.75rem;">Select Size & Color <span class="text-danger">*</span></label>
                            <select name="variant_id" class="form-select form-select-lg rounded-pill bg-light border-0 px-4 fw-medium text-muted" style="font-size: 0.9rem;" required>
                                <option value="" disabled selected>Choose size & variant...</option>
                                @if(isset($product->variants) &&$product->variants->count() > 0)
                                    @foreach($product->variants as $variant)
                                        <option value="{{ $variant->id }}">
                                            {{ $variant->size }} - {{$variant->color }} ({{ $variant->stock_quantity ?? $variant->stock }} left)
                                        </option>
                                    @endforeach
                                @else
                                    <option value="default">Standard - Default</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-4">
                            <label class="form-label fw-bold text-uppercase small text-dark tracking-wide" style="font-size: 0.75rem;">Quantity</label>
                            <input type="number" name="quantity" class="form-control form-control-lg rounded-pill bg-light border-0 text-center fw-medium" value="1" min="1" max="10" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-dark btn-lg w-100 rounded-pill fw-bold py-3 mb-4 shadow-sm d-flex justify-content-center align-items-center gap-2">
                        <i class="bi bi-cart3 fs-5"></i> Add to Cart
                    </button>
                </form>

                <div class="d-flex justify-content-center gap-4 text-muted small fw-medium mt-2">
                    <span><i class="bi bi-shield-check text-dark me-1"></i> Secure Checkout</span>
                    <span><i class="bi bi-truck text-dark me-1"></i> Fast Shipping</span>
                    <span><i class="bi bi-arrow-return-left text-dark me-1"></i> Easy Returns</span>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    const mainImage = document.getElementById('mainImage');
    const thumbnails = document.querySelectorAll('.gallery-thumb');
    let currentScale = 1;

    thumbnails.forEach(thumb => {
        thumb.addEventListener('click', function() {
            mainImage.src = this.getAttribute('data-image');
            resetZoom();

            thumbnails.forEach(el => {
                el.classList.remove('active-thumb');
                el.classList.add('border-transparent');
            });
            this.classList.remove('border-transparent');
            this.classList.add('active-thumb');
        });
    });

    function zoomIn() {
        if (currentScale < 3) {
            currentScale += 0.5;
            mainImage.style.transform = `scale(${currentScale})`;
        }
    }

    function zoomOut() {
        if (currentScale > 1) {
            currentScale -= 0.5;
            mainImage.style.transform = `scale(${currentScale})`;
        }
    }

    function resetZoom() {
        currentScale = 1;
        mainImage.style.transform = `scale(1)`;
    }
</script>
@endsection