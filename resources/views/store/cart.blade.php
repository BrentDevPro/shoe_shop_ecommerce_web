@extends('layouts.store')

@section('content')
<div class="container my-5">
    <h2 class="fw-bold mb-4">Your Shopping Bag</h2>

    @if(session('success'))
        <div class="alert alert-success rounded-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger rounded-4">{{ session('error') }}</div>
    @endif

    @if(count($cart) > 0)
        <div class="row g-5">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <ul class="list-group list-group-flush">
                        @foreach($cart as $id => $details)
                            <li class="list-group-item p-4 d-flex align-items-center gap-4">
                                <img src="{{ $details['image_path'] }}" alt="{{ $details['product_name'] }}" class="rounded" style="width: 80px; height: 80px; object-fit: cover; background: #f8f9fa;">
                                <div class="flex-grow-1">
                                    <h5 class="fw-bold mb-1">{{ $details['product_name'] }}</h5>
                                    <p class="text-muted small mb-0">Size: {{ $details['size'] }} | Color: {{ $details['color'] }}</p>
                                    <p class="text-muted small mb-0">Qty: {{ $details['quantity'] }} &times; ₱{{ number_format($details['price'], 2) }}</p>
                                </div>
                                <div class="text-end">
                                    <p class="fw-bold fs-5 mb-2">₱{{ number_format($details['price'] * $details['quantity'], 2) }}</p>
                                    <form action="{{ route('cart.remove') }}" method="POST" onsubmit="return confirm('Remove this item from your shopping bag?')">
                                        @csrf
                                        <input type="hidden" name="variant_id" value="{{ $id }}">
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">Remove</button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 bg-light p-4">
                    <h4 class="fw-bold mb-4">Order Summary</h4>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-bold">₱{{ number_format($total, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="text-muted">Estimated Shipping</span>
                        <span class="text-muted">Calculated at checkout</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold fs-5">Total</span>
                        <span class="fw-bold fs-5">₱{{ number_format($total, 2) }}</span>
                    </div>
                    <a href="{{ route('checkout.index') }}" class="btn btn-dark btn-lg w-100 rounded-pill py-3 fw-bold">Proceed to Checkout</a>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <h4 class="text-muted mb-4">Your bag is empty.</h4>
            <a href="{{ route('store.index') }}" class="btn btn-dark rounded-pill px-5 py-2">Continue Shopping</a>
        </div>
    @endif
</div>
@endsection