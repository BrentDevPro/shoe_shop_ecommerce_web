<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function index(Product $product)
    {
        $variants = $product->variants()->latest()->get();

        return view('admin.products.variants', compact('product', 'variants'));
    }

    public function store(Request $request, Product $product)
    {
        $request->validate([
            'color' => 'required|string|max:255',
            'size' => 'required|string|max:50',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        $product->variants()->create($request->only('color', 'size', 'stock_quantity'));

        return back()->with('success', 'New variant added successfully.');
    }

    public function update(Request $request, Product $product, ProductVariant $variant)
    {
        $request->validate([
            'color' => 'required|string|max:255',
            'size' => 'required|string|max:50',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        $variant->update($request->only('color', 'size', 'stock_quantity'));

        return back()->with('success', 'Variant details updated successfully.');
    }

    public function destroy(Product $product, ProductVariant $variant)
    {
        try {
            $variant->delete();

            return back()->with('success', 'Variant removed.');
        } catch (QueryException $e) {
            return back()->with('error', 'Cannot delete this variant because it has already been ordered by a customer. Please set the stock to 0 instead.');
        }
    }
}
