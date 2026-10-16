@extends('layouts.store')

@section('content')
<style>
    .order-card { 
        transition: all 0.2s ease; 
        border: 1px solid transparent; 
        cursor: pointer;
    }
    .order-card:hover { 
        border-color: #dee2e6; 
        transform: translateY(-3px); 
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08) !important;
    }
    .product-img-stack { 
        width: 75px; 
        height: 75px; 
        object-fit: cover; 
        border: 3px solid #fff; 
    }
    .img-stack-item {
        width: 75px;
        height: 75px;
        margin-right: -20px;
    }
    .img-stack-item:nth-child(1) { z-index: 10; }
    .img-stack-item:nth-child(2) { z-index: 9; }
    .img-stack-item:nth-child(3) { z-index: 8; }
    
    .img-stack-more {
        width: 75px;
        height: 75px;
        z-index: 1;
        border: 3px solid #fff;
    }
</style>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="fw-bold tracking-tight mb-1">My Orders</h1>
            <p class="text-muted">View your order history and track shipments.</p>
        </div>
        <a href="{{ route('store.index') }}" class="btn btn-outline-dark rounded-pill px-4">
            &larr; Continue Shopping
        </a>
    </div>

    <div class="row">
        <div class="col-lg-10 mx-auto">
            @forelse($orders as $order)
                <div class="card order-card shadow-sm rounded-4 bg-white mb-4 overflow-hidden" data-href="{{ route('orders.show', $order->id) }}">
                    
                    {{-- Header: Order Meta Info --}}
                    <div class="card-header bg-light border-bottom-0 py-3 px-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                        <div>
                            <span class="badge bg-dark px-3 py-2 rounded-pill font-monospace mb-2 mb-md-0">ORDER #{{ $order->tracking_number }}</span>
                            <span class="text-muted ms-md-2 small fw-medium d-block d-md-inline">Placed on {{ $order->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="text-md-end">
                            <span class="text-muted small">Total Amount:</span>
                            <span class="fw-bold text-danger ms-1 fs-5">₱{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>

                    {{-- Body: Visual Product List & Action --}}
                    <div class="card-body p-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
                            
                            {{-- Left: Overlapping Product Images --}}
                            <div class="d-flex align-items-center flex-wrap gap-3">
                                @php
                                    $items = $order->items ?? collect();
                                    $displayItems = $items->take(3); // Show max 3 images
                                    $remainingCount = $items->count() > 3 ? $items->count() - 3 : 0;
                                @endphp

                                @if($items->count() > 0)
                                    <div class="d-flex position-relative">
                                        @foreach($displayItems as $item)
                                            {{-- Safe check for deleted products --}}
                                            @if($item->variant && $item->variant->product)
                                                <div class="rounded-circle shadow-sm bg-white overflow-hidden position-relative img-stack-item">
                                                    <img src="{{ $item->variant->product->image_url }}" alt="Shoe" class="product-img-stack w-100 h-100 rounded-circle">
                                                </div>
                                            @else
                                                <div class="rounded-circle shadow-sm bg-light d-flex align-items-center justify-content-center text-muted position-relative img-stack-item border border-3 border-white">
                                                    <i class="bi bi-box-seam"></i>
                                                </div>
                                            @endif
                                        @endforeach

                                        {{-- If they ordered more than 3 items, show a +X badge --}}
                                        @if($remainingCount > 0)
                                            <div class="rounded-circle shadow-sm bg-dark d-flex align-items-center justify-content-center text-white fw-bold position-relative img-stack-more">
                                                +{{ $remainingCount }}
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="ms-md-4 mt-2 mt-md-0">
                                        <p class="fw-bold text-dark mb-1">
                                            {{ $items->first()->variant->product->name ?? 'Footwear Item' }}
                                            @if($items->count() > 1) 
                                                <span class="text-muted fw-normal">and {{ $items->count() - 1 }} other item(s)</span> 
                                            @endif
                                        </p>
                                        <p class="text-muted small mb-0">{{ $items->sum('quantity') }} items in this shipment</p>
                                    </div>
                                @else
                                    <p class="text-muted mb-0"><i class="bi bi-info-circle me-1"></i> Order details unavailable.</p>
                                @endif
                            </div>

                            {{-- Right: Status Badge & Button --}}
                            <div class="d-flex flex-column align-items-md-end gap-3 min-vw-25">
                                @if($order->status == 'pending')
                                    <span class="badge bg-warning text-dark rounded-pill px-4 py-2 fw-medium"><i class="bi bi-hourglass-split me-1"></i> Pending</span>
                                @elseif($order->status == 'paid')
                                    <span class="badge bg-info text-dark rounded-pill px-4 py-2 fw-medium"><i class="bi bi-gear-fill me-1"></i> Processing</span>
                                @elseif($order->status == 'shipped')
                                    <span class="badge bg-primary rounded-pill px-4 py-2 fw-medium"><i class="bi bi-truck me-1"></i> Shipped</span>
                                @elseif($order->status == 'completed')
                                    <span class="badge bg-success rounded-pill px-4 py-2 fw-medium"><i class="bi bi-house-door-fill me-1"></i> Delivered</span>
                                @elseif($order->status == 'cancelled')
                                    <span class="badge bg-danger rounded-pill px-4 py-2 fw-medium"><i class="bi bi-x-circle-fill me-1"></i> Cancelled</span>
                                @endif
                                
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-dark rounded-pill px-4 py-2 fw-bold shadow-sm w-100">
                                    Track Order
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 my-5">
                    <i class="bi bi-box-seam text-muted mb-3 d-block" style="font-size: 4rem;"></i>
                    <h4 class="fw-bold text-dark">No orders found</h4>
                    <p class="text-muted mb-4">You haven't placed any orders yet. Start exploring our collection!</p>
                    <a href="{{ route('store.index') }}" class="btn btn-dark btn-lg rounded-pill px-5">Go to Shop</a>
                </div>
            @endforelse

            <div class="mt-5 d-flex justify-content-center">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.order-card[data-href]').forEach(function(card) {
        card.addEventListener('click', function(e) {
            if (!e.target.closest('a, button')) {
                window.location.href = this.dataset.href;
            }
        });
    });
});
</script>
@endsection