@extends('layouts.store')

@section('content')
<style>
    .timeline-container { position: relative; display: flex; justify-content: space-between; align-items: center; width: 100%; margin: 3rem 0; }
    .timeline-line { position: absolute; top: 25px; transform: translateY(-50%); left: 12.5%; right: 12.5%; height: 4px; background-color: #e9ecef; z-index: 1; }
    .timeline-progress { position: absolute; top: 25px; transform: translateY(-50%); left: 12.5%; width: 0%; height: 4px; background-color: #20c997; z-index: 1; transition: width 0.4s ease; }
    .timeline-progress-paid { width: 25%; }
    .timeline-progress-shipped { width: 50%; }
    .timeline-progress-completed { width: 75%; }
    .timeline-step { position: relative; z-index: 2; text-align: center; background: transparent; flex: 1; }
    .timeline-icon { width: 50px; height: 50px; border-radius: 50%; background-color: #e9ecef; color: #adb5bd; display: inline-flex; align-items: center; justify-content: center; font-size: 1.25rem; transition: all 0.3s ease; border: 4px solid #fff; }
    .timeline-icon .bi { display: inline-flex; align-items: center; justify-content: center; width: 1em; height: 1em; line-height: 1; vertical-align: 0; }
    .timeline-step.completed .timeline-icon { background-color: #198754; color: #fff; }
    .timeline-step.active .timeline-icon { background-color: #dc3545; color: #fff; box-shadow: 0 0 0 10px rgba(220, 53, 69, 0.2); animation: pulse 2s infinite; }
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
        100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
    }
</style>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="fw-bold tracking-tight mb-1">Live Order Tracking</h1>
            <p class="text-muted">Track your real-time footwear shipment details.</p>
        </div>
        <a href="{{ route('orders.index') }}" class="btn btn-outline-dark rounded-pill px-4">
            &larr; Back to My Orders
        </a>
    </div>

    @php
        $isCancelled = $order->status === 'cancelled';
        $stages = ['pending', 'paid', 'shipped', 'completed'];
        $currentIndex = array_search($order->status, $stages);
    @endphp

    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-5 bg-white">
        <div class="d-flex flex-column flex-md-row justify-content-between border-bottom pb-4 mb-4 gap-2">
            <div>
                <span class="badge bg-dark px-3 py-2 rounded-pill font-monospace mb-2 mb-md-0">
                    ORDER #{{ $order->tracking_number }}
                </span>
                <span class="text-muted ms-md-2 d-block d-md-inline">{{ $order->created_at->format('M d, Y h:i A') }}</span>
            </div>
            <div class="text-md-end">
                <span class="text-muted">Total: </span>
                <span class="fs-4 fw-bold text-danger">₱{{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>

        @if($isCancelled)
            <div class="alert alert-danger rounded-4 text-center py-4 my-4" role="alert">
                <i class="bi bi-x-circle-fill fs-1 d-block mb-2"></i>
                <h5 class="fw-bold">This Order Has Been Cancelled</h5>
                <p class="mb-0 text-muted">If you have questions, please reach out to customer support.</p>
            </div>
        @else
            <div class="timeline-container">
                <div class="timeline-line"></div>
                <div class="timeline-progress timeline-progress-{{ $order->status }}"></div>

                @php 
                    $stepClass = '';
                    if ($order->status === 'pending') $stepClass = 'active';
                    elseif ($currentIndex > 0) $stepClass = 'completed';
                @endphp
                <div class="timeline-step {{ $stepClass }}">
                    <div class="timeline-icon">
                        @if($currentIndex > 0) <i class="bi bi-check-lg"></i> @else <i class="bi bi-file-earmark-text"></i> @endif
                    </div>
                    <p class="fw-bold small mb-0 mt-2">Placed</p>
                </div>

                @php 
                    $stepClass = '';
                    if ($order->status === 'paid') $stepClass = 'active';
                    elseif ($currentIndex > 1) $stepClass = 'completed';
                @endphp
                <div class="timeline-step {{ $stepClass }}">
                    <div class="timeline-icon"><i class="bi bi-gear-fill"></i></div>
                    <p class="fw-bold small mb-0 mt-2">Processing</p>
                </div>

                @php 
                    $stepClass = '';
                    if ($order->status === 'shipped') $stepClass = 'active';
                    elseif ($currentIndex > 2) $stepClass = 'completed';
                @endphp
                <div class="timeline-step {{ $stepClass }}">
                    <div class="timeline-icon"><i class="bi bi-truck"></i></div>
                    <p class="fw-bold small mb-0 mt-2">Shipped</p>
                </div>

                @php 
                    $stepClass = '';
                    if ($order->status === 'completed') $stepClass = 'completed';
                @endphp
                <div class="timeline-step {{ $stepClass }}">
                    <div class="timeline-icon"><i class="bi bi-house-door-fill"></i></div>
                    <p class="fw-bold small mb-0 mt-2">Delivered</p>
                </div>
            </div>
        @endif

        <div class="bg-light p-4 rounded-4">
            <h6 class="text-muted small fw-bold text-uppercase mb-4">Purchased Footwear</h6>
            <div class="row g-4">
                @foreach($order->items as $item)
                    <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        
                        @if($item->variant && $item->variant->product)
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $item->variant->product->image_url }}" alt="shoe" class="rounded shadow-sm" style="width: 70px; height: 70px; object-fit: cover; background-color: #fff;">
                                <div>
                                    <h6 class="fw-bold mb-0">{{ $item->variant->product->name }}</h6>
                                    <p class="text-muted small mb-0">Size: EU {{ $item->variant->size }} | Color: {{ $item->variant->color }}</p>
                                    <p class="text-muted small mb-0">Qty: {{ $item->quantity }}</p>
                                </div>
                            </div>
                        @else
                            {{-- Keep order history visible when a product is removed. --}}
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded border d-flex align-items-center justify-content-center bg-white text-muted shadow-sm" style="width: 70px; height: 70px;">
                                    <i class="bi bi-box-seam fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-muted mb-0">Product Unavailable</h6>
                                    <p class="text-muted small mb-0">Item removed from catalog</p>
                                    <p class="text-muted small mb-0">Qty: {{ $item->quantity }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="text-md-end">
                            <p class="fw-bold mb-0">₱{{ number_format($item->price_at_purchase * $item->quantity, 2) }}</p>
                            <small class="text-muted">₱{{ number_format($item->price_at_purchase, 2) }} each</small>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <hr class="my-4 border-light-subtle">
            <div class="text-md-end text-muted small">
                <p class="mb-1">Subtotal: <strong>₱{{ number_format($order->total_amount - $order->shipping_fee, 2) }}</strong></p>
                <p class="mb-0">Shipping Fee: <strong>₱{{ number_format($order->shipping_fee, 2) }}</strong></p>
            </div>
        </div>
    </div>
</div>
@endsection