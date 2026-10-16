@extends('layouts.admin')

@section('content')
<style>
    .metric-card { border-radius: 0.75rem; border: none; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04); }
    .tracking-wide { letter-spacing: 0.05em; }
</style>

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4 pb-2">
    <div>
        <h3 class="fw-bold text-dark mb-1">Order Details</h3>
        <p class="text-muted small mb-0">Manage order <span class="fw-bold text-dark font-monospace">#{{ $order->tracking_number }}</span></p>
    </div>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-medium">
        &larr; Back to Orders
    </a>
</div>

{{-- Alerts --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-4">
    {{-- Left Column: Order Items --}}
    <div class="col-lg-8">
        <div class="card metric-card bg-white h-100">
            <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-2">
                <h6 class="fw-bold text-dark mb-0">Items Ordered</h6>
            </div>
            
            <div class="card-body p-0">
                <div class="list-group list-group-flush border-top-0">
                    @foreach($order->items as $item)
                        <div class="list-group-item p-4 d-flex align-items-center gap-4 border-light-subtle">
                            <img src="{{ $item->variant->product->image_url }}" alt="Shoe" class="rounded shadow-sm border border-light-subtle" style="width: 75px; height: 75px; object-fit: cover; background: #fbfbfb;">
                            <div class="flex-grow-1">
                                <h6 class="fw-bold text-dark mb-1">{{ $item->variant->product->name }}</h6>
                                <p class="text-muted small mb-0">Size: EU {{ $item->variant->size }} <span class="mx-1">|</span> Color: {{ $item->variant->color }}</p>
                            </div>
                            <div class="text-end">
                                <span class="d-block text-muted small mb-1">{{ $item->quantity }} x ₱{{ number_format($item->price_at_purchase, 2) }}</span>
                                <span class="fw-bold text-dark">₱{{ number_format($item->quantity * $item->price_at_purchase, 2) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <div class="card-footer bg-light border-top-0 rounded-bottom-4 p-4 text-end">
                <div class="d-flex justify-content-end align-items-center gap-4 mb-2">
                    <span class="text-muted small">Subtotal:</span>
                    <span class="fw-medium text-dark">₱{{ number_format($order->total_amount - $order->shipping_fee, 2) }}</span>
                </div>
                <div class="d-flex justify-content-end align-items-center gap-4 mb-3">
                    <span class="text-muted small">Shipping Fee:</span>
                    <span class="fw-medium text-dark">₱{{ number_format($order->shipping_fee, 2) }}</span>
                </div>
                <div class="d-flex justify-content-end align-items-center gap-4 pt-3 border-top border-light-subtle">
                    <span class="text-uppercase fw-bold text-muted small tracking-wide">Total Amount:</span>
                    <h4 class="fw-bold text-danger mb-0">₱{{ number_format($order->total_amount, 2) }}</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Column: Status Update & Customer Info --}}
    <div class="col-lg-4">
        
        {{-- Status Update Form --}}
        <div class="card metric-card bg-white mb-4">
            <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                <h6 class="fw-bold text-dark mb-0">Update Order Status</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <select name="status" class="form-select rounded-pill bg-light border-0 px-3 py-2 fw-medium text-dark" style="cursor: pointer;">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-dark rounded-pill w-100 fw-bold py-2 d-flex justify-content-center align-items-center gap-2">
                        <i class="bi bi-arrow-repeat"></i> Update Status
                    </button>
                </form>
            </div>
        </div>

        {{-- Customer Info --}}
        <div class="card metric-card bg-white">
            <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                <h6 class="fw-bold text-dark mb-0">Customer Details</h6>
            </div>
            <div class="card-body p-4">
                
                {{-- Name --}}
                <div class="mb-4">
                    <p class="mb-1 text-muted small text-uppercase tracking-wide fw-bold">Customer Profile</p>
                    <div class="d-flex align-items-center gap-3 mt-2">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-secondary fw-bold" style="width: 40px; height: 40px;">
                            {{ substr($order->user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="mb-0 fw-bold text-dark">{{ $order->user->name }}</p>
                            <p class="mb-0 text-muted small">{{ $order->user->email }}</p>
                        </div>
                    </div>
                </div>

                {{-- Payment --}}
                <div class="mb-4">
                    <p class="mb-1 text-muted small text-uppercase tracking-wide fw-bold">Payment Method</p>
                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 fw-medium border border-primary border-opacity-25 mt-1">
                        <i class="bi bi-credit-card me-1"></i> {{ $order->payment_method }}
                    </span>
                </div>

                {{-- Address --}}
                <div>
                    <p class="mb-1 text-muted small text-uppercase tracking-wide fw-bold">Shipping Address</p>
                    <div class="bg-light rounded-4 p-3 mt-2">
                        <p class="mb-0 small text-dark lh-base">
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i> 
                            {{ $order->shipping_address }}
                        </p>
                    </div>
                </div>
                
                {{-- Date --}}
                <div class="mt-4 pt-4 border-top border-light-subtle">
                    <p class="mb-1 text-muted small text-uppercase tracking-wide fw-bold">Date Placed</p>
                    <p class="mb-0 text-dark small fw-medium"><i class="bi bi-calendar3 me-2 text-muted"></i>{{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection