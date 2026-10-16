<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSales = Order::where('status', 'completed')->sum('total_amount');

        $pendingOrders = Order::where('status', 'pending')->count();

        $activeProducts = Product::where('is_active', true)->count();

        $totalOrders = Order::count();

        $recentOrders = Order::with(['user', 'items.variant.product'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalSales',
            'pendingOrders',
            'activeProducts',
            'totalOrders',
            'recentOrders'
        ));
    }
}
