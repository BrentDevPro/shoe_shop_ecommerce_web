<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;

class BrandController extends Controller
{
    // Show the brands page
    public function index()
    {
        // Fetch brands and count how many products belong to each
        $brands = Brand::withCount('products')->latest()->get();
        return view('admin.brands.index', compact('brands'));
    }

    // Add a new brand
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name'
        ]);

        Brand::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return back()->with('success', 'New brand added successfully.');
    }

    // Update an existing brand name
    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id
        ]);

        $brand->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return back()->with('success', 'Brand updated successfully.');
    }

    // Delete a brand securely
    public function destroy(Brand $brand)
    {
        try {
            $brand->delete();
            return back()->with('success', 'Brand removed successfully.');
        } catch (QueryException $e) {
            // Protects against deleting brands currently in use
            return back()->with('error', 'Cannot delete this brand because there are shoes assigned to it. Please reassign or delete those shoes first.');
        }
    }
}