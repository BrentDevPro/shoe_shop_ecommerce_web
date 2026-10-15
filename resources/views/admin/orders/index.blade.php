@extends('layouts.admin')

@section('content')
<style>
    .metric-card {
        border-radius: 1rem;
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

    /* Storefront-Identical Pagination Styling */
    .pagination-wrapper {
        background: #ffffff;
        border-top: 1px solid #edf2f7;
        padding: 1rem 1.5rem;
    }

    .pagination-wrapper nav[role="navigation"] {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        width: 100% !important;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .pagination {
        margin: 0 !important;
        padding: 0 !important;
        display: flex !important;
        align-items: center !important;
        gap: 0.4rem !important;
        list-style: none !important;
    }

    .pagination .page-item .page-link,
    .pagination .page-item span {
        border-radius: 50rem !important;
        border: 1px solid #e2e8f0 !important;
        color: #212529 !important;
        min-width: 42px !important;
        height: 42px !important;
        padding: 0 0.85rem !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-weight: 600 !important;
        font-size: 0.95rem !important;
        transition: all 0.2s ease !important;
        background-color: #ffffff !important;
        box-shadow: none !important;
        text-decoration: none !important;
    }

    .pagination .page-item:first-child .page-link,
    .pagination .page-item:first-child span,
    .pagination .page-item:last-child .page-link,
    .pagination .page-item:last-child span {
        min-width: 44px !important;
        height: 44px !important;
        font-size: 1.2rem !important;
        font-weight: bold !important;
        padding: 0 !important;
    }

    .pagination .page-item .page-link:hover {
        background-color: #f1f5f9 !important;
        color: #000000 !important;
        border-color: #cbd5e1 !important;
    }

    .pagination .page-item.active .page-link {
        background-color: #212529 !important;
        border-color: #212529 !important;
        color: #ffffff !important;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15) !important;
    }

    .pagination .page-item.disabled .page-link,
    .pagination .page-item.disabled span {
        background-color: #f8f9fa !important;
        color: #cbd5e1 !important;
        border-color: #f1f5f9 !important;
        opacity: 0.6 !important;
        pointer-events: none !important;
    }
</style>

<div class="container-fluid px-4 py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Orders Management</h3>
            <p class="text-muted small mb-0">View and process all customer orders.</p>
        </div>
    </div>

    {{-- Search & Filter Toolbar --}}
    <div class="card metric-card bg-white p-3 mb-4">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-3 align-items-center">
            
            {{-- Search Bar --}}
            <div class="col-md-6">
                <div class="input-group border border-light-subtle rounded-pill shadow-sm overflow-hidden bg-light">
                    <span class="input-group-text bg-light border-0 ps-3 text-muted">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control bg-light border-0 py-2 text-dark shadow-none" placeholder="Search order # or customer name..." value="{{ request('search') }}">
                </div>
            </div>

            {{-- Status Filter --}}
            <div class="col-md-4">
                <select name="status" class="form-select rounded-pill bg-light border border-light-subtle px-3 py-2 text-dark shadow-sm" onchange="this.form.submit()">
                    <option value="">All Order Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid / Processing</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed / Delivered</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            {{-- Reset Filters Button --}}
            <div class="col-md-2 text-end ms-auto">
                @if(request('search') || request('status'))
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-danger rounded-pill w-100 py-2" title="Reset Filters">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                    </a>
                @endif
            </div>

        </form>
    </div>

    {{-- Orders Table Card --}}
    <div class="card metric-card bg-white overflow-hidden">
        <div class="card-body px-4 pb-4 pt-3">
            <div class="table-responsive">
                <table class="table table-custom mb-0 align-middle">
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
                                <td>{{ $order->user->name ?? 'Guest User' }}</td>
                                <td>
                                    @if(isset($order->items) && $order->items->count() > 0)
                                        @php $firstItem = $order->items->first(); @endphp
                                        {{ $firstItem->variant->product->name ?? 'Footwear Item' }} (Size EU {{ $firstItem->variant->size ?? 'N/A' }})
                                        @if($order->items->count() > 1)
                                            <span class="text-muted small">+ {{ $order->items->count() - 1 }} more</span>
                                        @endif
                                    @else
                                        <span class="text-muted">No items</span>
                                    @endif
                                </td>
                                <td class="fw-bold text-dark">₱{{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    @if($order->status == 'completed')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1 fw-medium" style="font-size: 0.75rem;">
                                            <i class="bi bi-check-circle-fill me-1"></i> Completed
                                        </span>
                                    @elseif($order->status == 'shipped')
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fw-medium" style="font-size: 0.75rem;">
                                            <i class="bi bi-truck me-1"></i> Shipped
                                        </span>
                                    @elseif($order->status == 'paid')
                                        <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle rounded-pill px-3 py-1 fw-medium" style="font-size: 0.75rem;">
                                            <i class="bi bi-gear-fill me-1"></i> Processing
                                        </span>
                                    @elseif($order->status == 'pending')
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-3 py-1 fw-medium" style="font-size: 0.75rem;">
                                            <i class="bi bi-hourglass-split me-1"></i> Pending
                                        </span>
                                    @elseif($order->status == 'cancelled')
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1 fw-medium" style="font-size: 0.75rem;">
                                            <i class="bi bi-x-circle-fill me-1"></i> Cancelled
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-1 fw-medium" style="font-size: 0.75rem;">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end pe-1">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-medium shadow-sm" style="font-size: 0.75rem;">
                                        Manage
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-3 text-light"></i>
                                    No orders found matching your search.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination Footer --}}
        @if(method_exists($orders, 'hasPages') && $orders->hasPages())
            <div class="card-footer bg-white pagination-wrapper d-flex justify-content-center">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const prevItem = document.querySelector('.pagination .page-item:first-child .page-link, .pagination .page-item:first-child span');
    if (prevItem) prevItem.innerHTML = '<i class="bi bi-chevron-left"></i>';

    const nextItem = document.querySelector('.pagination .page-item:last-child .page-link, .pagination .page-item:last-child span');
    if (nextItem) nextItem.innerHTML = '<i class="bi bi-chevron-right"></i>';
});
</script>
@endsection