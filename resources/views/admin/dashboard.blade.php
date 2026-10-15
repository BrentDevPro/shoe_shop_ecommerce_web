@extends('layouts.admin')

@section('content')
<style>
    .metric-card {
        border-radius: 0.75rem;
        border: none;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }
    .metric-icon-box {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
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

{{-- Dashboard Header --}}
<div class="d-flex justify-content-between align-items-center mb-4 pb-2">
    <div>
        <h3 class="fw-bold text-dark mb-1">Business Dashboard</h3>
        <p class="text-muted small mb-0">Overview of active revenue, total orders, and inventory status.</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-dark rounded-pill px-4 py-2 fw-medium d-flex align-items-center gap-2">
        <i class="bi bi-plus-lg"></i> Add New Shoe
    </a>
</div>

{{-- Metric Cards (Retaining your requested metrics) --}}
<div class="row g-3 mb-5">
    {{-- Card 1: Revenue (Completed) --}}
    <div class="col-xl-3 col-sm-6">
        <div class="card metric-card h-100 bg-white">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted small fw-bold mb-1">Total Revenue (Completed)</h6>
                    <h3 class="fw-bold mb-0 text-dark">₱{{ number_format($totalSales, 2) }}</h3>
                </div>
                <div class="metric-icon-box" style="background-color: #dcfce7; color: #166534;">
                    <i class="bi bi-currency-dollar"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 2: Pending Orders --}}
    <div class="col-xl-3 col-sm-6">
        <div class="card metric-card h-100 bg-white">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted small fw-bold mb-1">Pending Orders</h6>
                    <h3 class="fw-bold mb-0 text-dark">{{ $pendingOrders }}</h3>
                </div>
                <div class="metric-icon-box" style="background-color: #dbeafe; color: #1e40af;">
                    <i class="bi bi-bag"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 3: Active Products --}}
    <div class="col-xl-3 col-sm-6">
        <div class="card metric-card h-100 bg-white">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted small fw-bold mb-1">Active Products</h6>
                    <h3 class="fw-bold mb-0 text-dark">{{ $activeProducts }}</h3>
                </div>
                <div class="metric-icon-box" style="background-color: #cffafe; color: #164e63;">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 4: Total Orders --}}
    <div class="col-xl-3 col-sm-6">
        <div class="card metric-card h-100 bg-white">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted small fw-bold mb-1">Total Orders</h6>
                    <h3 class="fw-bold mb-0 text-dark">{{ $totalOrders }}</h3>
                </div>
                <div class="metric-icon-box" style="background-color: #fee2e2; color: #991b1b;">
                    <i class="bi bi-receipt"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Recent Orders Table --}}
<div class="card metric-card bg-white overflow-hidden">
    <div class="card-header bg-white border-bottom-0 pt-4 px-4 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold text-dark mb-0 fs-6">Recent Customer Orders</h5>
        <a href="{{ route('admin.orders.index') }}" class="text-dark fw-bold text-decoration-none border-bottom border-dark" style="font-size: 0.8rem;">View All Orders</a>
    </div>
    
    <div class="card-body px-4 pb-4 pt-2">
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
                    @forelse($recentOrders as $order)
                        <tr>
                            <td class="ps-1 fw-bold text-dark">#{{ $order->tracking_number }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>
                                @if($order->items->count() > 0)
                                    @php $firstItem = $order->items->first(); @endphp
                                    {{ $firstItem->variant->product->name }} (Size EU {{ $firstItem->variant->size }})
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
                            <td colspan="6" class="text-center py-5 text-muted">No recent orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection