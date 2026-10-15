<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class CatalogController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        // Eager load category and brand to prevent N+1 query performance issues
        $products = Product::with(['category', 'brand'])->where('is_active', true)->latest()->get();
        
        return view('store.index', compact('categories', 'products'));
    }

    public function show($slug)
    {
        // Fetch the product, along with its available variants (sizes/colors)
        $product = Product::with('variants')->where('slug', $slug)->where('is_active', true)->firstOrFail();
        
        return view('store.show', compact('product'));
    }
}