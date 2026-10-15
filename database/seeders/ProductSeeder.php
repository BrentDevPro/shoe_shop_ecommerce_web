<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Create Categories
        $running = Category::create(['name' => 'Running', 'slug' => 'running']);
        $basketball = Category::create(['name' => 'Basketball', 'slug' => 'basketball']);
        $lifestyle = Category::create(['name' => 'Lifestyle', 'slug' => 'lifestyle']);

        // Create Brands
        $nike = Brand::create(['name' => 'Nike', 'slug' => 'nike']);
        $newBalance = Brand::create(['name' => 'New Balance', 'slug' => 'new-balance']);

        // Create Products (Using image placeholders via Unsplash source)
        $p1 = Product::create([
            'brand_id' => $nike->id,
            'category_id' => $running->id,
            'name' => 'Air Zoom Pegasus 40',
            'slug' => 'air-zoom-pegasus-40',
            'description' => 'Lightweight, springy response for everyday road running.',
            'price' => 7495.00, // Realistic PHP pricing
            'image_path' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=600&auto=format&fit=crop',
            'is_active' => true,
        ]);

        $p2 = Product::create([
            'brand_id' => $nike->id,
            'category_id' => $basketball->id,
            'name' => 'Air Jordan 4 Retro',
            'slug' => 'air-jordan-4-retro',
            'description' => 'Iconic basketball silhouette featuring premium materials.',
            'price' => 11295.00,
            'image_path' => 'https://images.unsplash.com/photo-1608231387042-66d1773070a5?q=80&w=600&auto=format&fit=crop',
            'is_active' => true,
        ]);

        $p3 = Product::create([
            'brand_id' => $newBalance->id,
            'category_id' => $lifestyle->id,
            'name' => 'New Balance 9060',
            'slug' => 'new-balance-9060',
            'description' => 'Swayed futuristic aesthetic with dual-density midsole.',
            'price' => 8295.00,
            'image_path' => 'https://images.unsplash.com/photo-1539185441755-769473a23570?q=80&w=600&auto=format&fit=crop',
            'is_active' => true,
        ]);

        // Add size variants for the first product
        ProductVariant::create(['product_id' => $p1->id, 'color' => 'Red/White', 'size' => '9', 'stock_quantity' => 10]);
        ProductVariant::create(['product_id' => $p1->id, 'color' => 'Red/White', 'size' => '9.5', 'stock_quantity' => 5]);
        ProductVariant::create(['product_id' => $p1->id, 'color' => 'Red/White', 'size' => '10', 'stock_quantity' => 0]);
    }
}