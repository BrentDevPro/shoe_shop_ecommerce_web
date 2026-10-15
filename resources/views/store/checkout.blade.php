@extends('layouts.store')

@section('content')
<div class="container my-5">
    <h2 class="fw-bold mb-4">Secure Checkout</h2>

    @if(session('error'))
        <div class="alert alert-danger rounded-4">{{ session('error') }}</div>
    @endif

    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <div class="row g-5">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <h4 class="fw-bold mb-4">Shipping Information</h4>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold text-uppercase">Full Name</label>
                        <input type="text" class="form-control rounded-3" value="{{ Auth::user()->name }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold text-uppercase">Delivery Address <span class="text-danger">*</span></label>
                        <!-- Will pre-fill with Tarlac City if it exists in the user profile -->
                        <textarea name="shipping_address" class="form-control rounded-3" rows="3" required>{{ Auth::user()->address ?? 'Tarlac City, Philippines' }}</textarea>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h4 class="fw-bold mb-4">Payment Method</h4>
                    <div class="form-check mb-3 p-3 border rounded-3 bg-light">
                        <input class="form-check-input ms-1" type="radio" name="payment_method" value="COD" id="cod" checked>
                        <label class="form-check-label ms-2 fw-bold" for="cod">Cash on Delivery (COD)</label>
                    </div>
                    <div class="form-check mb-3 p-3 border rounded-3">
                        <input class="form-check-input ms-1" type="radio" name="payment_method" value="GCash" id="gcash">
                        <label class="form-check-label ms-2 fw-bold text-primary" for="gcash">GCash (E-Wallet)</label>
                    </div>
                    <div class="form-check mb-3 p-3 border rounded-3">
                        <input class="form-check-input ms-1" type="radio" name="payment_method" value="Maya" id="maya">
                        <label class="form-check-label ms-2 fw-bold text-success" for="maya">Maya</label>
                    </div>
                    <div class="form-check p-3 border rounded-3">
                        <input class="form-check-input ms-1" type="radio" name="payment_method" value="Visa" id="visa">
                        <label class="form-check-label ms-2 fw-bold" for="visa">Credit/Debit Card (Visa)</label>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 bg-light p-4 sticky-top" style="top: 100px;">
                    <h4 class="fw-bold mb-4">Order Details</h4>
                    <ul class="list-unstyled mb-4">
                        @foreach($cart as $item)
                            <li class="d-flex justify-content-between mb-2">
                                <span class="text-muted">{{ $item['quantity'] }}x {{ $item['product_name'] }}</span>
                                <span>₱{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <hr>
                    <div class="d-flex justify-content-between mb-2 mt-3">
                        <span class="text-muted">Subtotal</span>
                        <span>₱{{ number_format($total, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Shipping (Flat Rate)</span>
                        <span>₱{{ number_format($shipping_fee, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold fs-5">Total to Pay</span>
                        <span class="fw-bold fs-5 text-danger">₱{{ number_format($total + $shipping_fee, 2) }}</span>
                    </div>
                    
                    <button type="submit" class="btn btn-danger btn-lg w-100 rounded-pill py-3 fw-bold shadow-sm">
                        Confirm & Place Order
                    </button>
                    <p class="text-center text-muted small mt-3"><i class="bi bi-shield-lock-fill"></i> Secure and encrypted transaction.</p>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection