@extends('layouts.store')

@section('content')
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

    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-uppercase small fw-bold text-muted py-3">Order ID</th>
                        <th class="text-uppercase small fw-bold text-muted py-3">Date</th>
                        <th class="text-uppercase small fw-bold text-muted py-3">Total</th>
                        <th class="text-uppercase small fw-bold text-muted py-3">Status</th>
                        <th class="text-uppercase small fw-bold text-muted py-3 text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="fw-bold text-dark font-monospace">#{{ $order->tracking_number }}</td>
                            <td class="text-muted">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="fw-bold text-danger">₱{{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                @if($order->status == 'pending')
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2 fw-medium">Pending</span>
                                @elseif($order->status == 'paid')
                                    <span class="badge bg-info text-dark rounded-pill px-3 py-2 fw-medium">Processing</span>
                                @elseif($order->status == 'shipped')
                                    <span class="badge bg-primary rounded-pill px-3 py-2 fw-medium">Shipped</span>
                                @elseif($order->status == 'completed')
                                    <span class="badge bg-success rounded-pill px-3 py-2 fw-medium">Delivered</span>
                                @elseif($order->status == 'cancelled')
                                    <span class="badge bg-danger rounded-pill px-3 py-2 fw-medium">Cancelled</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-dark btn-sm rounded-pill px-4 py-2 fw-medium shadow-sm">
                                    Track Order
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-box-seam text-muted mb-3 d-block" style="font-size: 3rem;"></i>
                                <h5 class="fw-bold text-dark">No orders found</h5>
                                <p class="text-muted">You haven't placed any orders yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection