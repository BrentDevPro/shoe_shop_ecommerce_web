<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;

class CategoryController extends Controller
{
    // Show the categories page
    public function index()
    {
        // Fetch categories and count how many products belong to each
        $categories = Category::withCount('products')->latest()->get();
        return view('admin.categories.index', compact('categories'));
    }

    // Add a new category
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name'
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return back()->with('success', 'New category added successfully.');
    }

    // Update an existing category name
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return back()->with('success', 'Category updated successfully.');
    }

    // Delete a category securely
    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return back()->with('success', 'Category removed successfully.');
        } catch (QueryException $e) {
            // This prevents a crash if shoes are currently using this category!
            return back()->with('error', 'Cannot delete this category because there are shoes assigned to it. Please reassign or delete those shoes first.');
        }
    }
}