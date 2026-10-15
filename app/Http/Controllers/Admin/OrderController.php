<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        // Fetch all orders, newest first, including the user who placed it
        $orders = Order::with(['user', 'items.variant.product'])->latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Load the related items, variants, and products for this specific order
        $order->load(['user', 'items.variant.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,shipped,completed,cancelled'
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Order status successfully updated to: ' . strtoupper($request->status));
    }
}
