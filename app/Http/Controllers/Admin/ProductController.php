<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['brand', 'category', 'variants']);

        // Search by Product Name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by Brand
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        // Filter by Category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Paginate results (10 items per page)
        $products = $query->latest()->paginate(10)->withQueryString();

        // Calculate KPI Metrics for Admin Dashboard Cards
        $totalProducts = Product::count();
        $allVariants = \App\Models\ProductVariant::all();
        $totalStock = $allVariants->sum('stock_quantity');
        $lowStockCount = Product::whereHas('variants', function ($q) {
            $q->havingRaw('SUM(stock_quantity) <= 5 AND SUM(stock_quantity) > 0');
        })->count();
        $outOfStockCount = Product::whereDoesntHave('variants', function ($q) {
            $q->where('stock_quantity', '>', 0);
        })->count();

        $brands = Brand::all();
        $categories = Category::all();

        return view('admin.products.index', compact(
            'products',
            'brands',
            'categories',
            'totalProducts',
            'totalStock',
            'lowStockCount',
            'outOfStockCount'
        ));
    }

    public function create()
    {
        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('brands', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'images' => 'required|array|min:1', // Replaced separate validations with one array
            'images.*' => 'image|max:2048',
            'description' => 'required|string',
        ]);

        $images = $request->file('images');

        // Take the FIRST image out of the array and make it the Main Image
        $mainImage = array_shift($images);

        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'price' => $request->price,
            'brand_id' => $request->brand_id,
            'category_id' => $request->category_id,
            'image_path' => $mainImage->store('products', 'public'),
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        // Save any remaining images to the Gallery
        if (!empty($images)) {
            foreach ($images as $image) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $image->store('products/gallery', 'public')
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'brands', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'images' => 'nullable|array', // Unified validation
            'images.*' => 'image|max:2048',
            'description' => 'required|string',
        ]);

        $product->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'price' => $request->price,
            'brand_id' => $request->brand_id,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        // In edit mode, any newly uploaded files are simply added to the gallery
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $image->store('products/gallery', 'public')
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if (Storage::disk('public')->exists($product->image_path)) {
            Storage::disk('public')->delete($product->image_path);
        }

        foreach ($product->images as $img) {
            if (Storage::disk('public')->exists($img->image_path)) {
                Storage::disk('public')->delete($img->image_path);
            }
        }

        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    public function destroyGalleryImage($id)
    {
        $image = ProductImage::findOrFail($id);
        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }
        $image->delete();

        return back()->with('success', 'Gallery image removed.');
    }
}
