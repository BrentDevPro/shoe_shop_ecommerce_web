@extends('layouts.admin')

@section('content')
<style>
    .metric-card {
        border-radius: 0.75rem;
        border: none;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }
    .table-custom th {
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        color: #6c757d;
        font-weight: 700;
        border-top: none;
        padding-bottom: 1rem;
        padding-top: 1rem;
        border-bottom: 1px solid #edf2f7;
    }
    .table-custom td {
        vertical-align: middle;
        font-size: 0.85rem;
        font-weight: 500;
        color: #1a202c;
        border-bottom: 1px solid #edf2f7;
        padding: 1rem 0.5rem;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4 pb-2">
    <div>
        <h3 class="fw-bold text-dark mb-1">Orders Management</h3>
        <p class="text-muted small mb-0">View and process all customer orders.</p>
    </div>
</div>

<div class="card metric-card bg-white overflow-hidden">
    <div class="card-body px-4 pb-4 pt-3">
        <div class="table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th class="ps-1">ORDER ID</th>
                        <th>CUSTOMER</th>
                        <th>SHOE & SIZE</th>
                        <th>TOTAL</th>
                        <th>STATUS</th>
                        <th class="text-end pe-1">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="ps-1 fw-bold text-dark">#{{ $order->tracking_number }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>
                                @if($order->items->count() > 0)
                                    @php $firstItem = $order->items->first(); @endphp
                                    {{ $firstItem->variant->product->name }} (Size EU {{ $firstItem->variant->size }})
                                    @if($order->items->count() > 1)
                                        <span class="text-muted small">+ {{ $order->items->count() - 1 }} more</span>
                                    @endif
                                @else
                                    <span class="text-muted">No items</span>
                                @endif
                            </td>
                            <td class="fw-bold text-dark">₱{{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                @php
                                    $badgeClass = match($order->status) {
                                        'completed' => 'bg-success',
                                        'shipped' => 'bg-primary',
                                        'cancelled' => 'bg-danger',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }} rounded-pill px-3 py-1 fw-medium" style="font-size: 0.7rem;">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="text-end pe-1">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-medium" style="font-size: 0.75rem;">
                                    Manage
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-3 text-light"></i>
                                No orders have been placed yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection