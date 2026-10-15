<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Total Revenue (Completed only)
        $totalSales = Order::where('status', 'completed')->sum('total_amount');
        
        // 2. Pending Orders
        $pendingOrders = Order::where('status', 'pending')->count();
        
        // 3. Active Products
        $activeProducts = Product::where('is_active', true)->count();
        
        // 4. Total Orders (All statuses)
        $totalOrders = Order::count();

        // Get recent orders with user and items
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