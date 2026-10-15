<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderHistoryController extends Controller
{
    public function index()
    {
        // Get all orders from the logged-in customer, newest first, including products
        $orders = Order::with('items.variant.product')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('store.orders.index', compact('orders'));
    }
}