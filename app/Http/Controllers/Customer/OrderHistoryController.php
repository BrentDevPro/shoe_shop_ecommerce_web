<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderHistoryController extends Controller
{
    // 1. The new Master List View
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('store.orders.index', compact('orders'));
    }

    // 2. The specific Order Tracking View
    public function show(Order $order)
    {
        // Ensure users can only see their own orders
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Load the items, variants, and products for this specific order
        $order->load('items.variant.product');

        return view('store.orders.show', compact('order'));
    }
}