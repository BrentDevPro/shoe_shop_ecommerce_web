<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductVariant;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Core Categories
        $categories = [
            'Basketball' => Category::firstOrCreate(['slug' => 'basketball'], ['name' => 'Basketball']),
            'Running' => Category::firstOrCreate(['slug' => 'running'], ['name' => 'Running']),
            'Lifestyle' => Category::firstOrCreate(['slug' => 'lifestyle'], ['name' => 'Lifestyle']),
            'Skateboarding' => Category::firstOrCreate(['slug' => 'skateboarding'], ['name' => 'Skateboarding']),
            'Training' => Category::firstOrCreate(['slug' => 'training'], ['name' => 'Training']),
        ];

        // 2. Seed Core Brands
        $brands = [
            'Nike' => Brand::firstOrCreate(['slug' => 'nike'], ['name' => 'Nike']),
            'Adidas' => Brand::firstOrCreate(['slug' => 'adidas'], ['name' => 'Adidas']),
            'Jordan' => Brand::firstOrCreate(['slug' => 'jordan'], ['name' => 'Jordan']),
            'Puma' => Brand::firstOrCreate(['slug' => 'puma'], ['name' => 'Puma']),
            'New Balance' => Brand::firstOrCreate(['slug' => 'new-balance'], ['name' => 'New Balance']),
            'Asics' => Brand::firstOrCreate(['slug' => 'asics'], ['name' => 'Asics']),
            'Converse' => Brand::firstOrCreate(['slug' => 'converse'], ['name' => 'Converse']),
            'Vans' => Brand::firstOrCreate(['slug' => 'vans'], ['name' => 'Vans']),
            'Reebok' => Brand::firstOrCreate(['slug' => 'reebok'], ['name' => 'Reebok']),
            'Under Armour' => Brand::firstOrCreate(['slug' => 'under-armour'], ['name' => 'Under Armour']),
        ];

        // High-Quality Footwear Image Pool (Unsplash CDN)
        $images = [
            'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1608231387042-66d1773070a5?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1584735935682-2f2b69dff9d2?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1560769629-975ec94e6a86?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1512374382149-233c42b6a83b?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1539185441755-769473a23570?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1552346154-21d32810aba3?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1603808033192-082d6919d3e1?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1582588678413-dbf45f4823e9?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1515955656352-a1fa3ffcd111?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1579338559194-a162d19bf842?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1514989940723-e8e51635b782?auto=format&fit=crop&w=800&q=80',
        ];

        // 3. Catalog Data (50 Shoes)
        $shoes = [
            // Nike
            ['name' => "Nike Air Force 1 '07", 'brand' => 'Nike', 'category' => 'Lifestyle', 'price' => 5495.00, 'color' => 'Triple White'],
            ['name' => 'Nike Dunk Low Retro', 'brand' => 'Nike', 'category' => 'Skateboarding', 'price' => 5995.00, 'color' => 'Panda Black/White'],
            ['name' => 'Nike Air Max 270', 'brand' => 'Nike', 'category' => 'Running', 'price' => 8295.00, 'color' => 'Black/White'],
            ['name' => 'Nike ZoomX Vaporfly NEXT% 3', 'brand' => 'Nike', 'category' => 'Running', 'price' => 13495.00, 'color' => 'Volt Yellow'],
            ['name' => 'Nike Pegasus 40', 'brand' => 'Nike', 'category' => 'Running', 'price' => 6895.00, 'color' => 'Obsidian Blue'],
            ['name' => 'Nike Metcon 9', 'brand' => 'Nike', 'category' => 'Training', 'price' => 7895.00, 'color' => 'Smoke Grey'],

            // Jordan
            ['name' => 'Air Jordan 1 Retro High OG', 'brand' => 'Jordan', 'category' => 'Basketball', 'price' => 9895.00, 'color' => 'Chicago Lost & Found'],
            ['name' => 'Air Jordan 4 Retro', 'brand' => 'Jordan', 'category' => 'Basketball', 'price' => 11495.00, 'color' => 'Military Blue'],
            ['name' => 'Air Jordan 11 Retro', 'brand' => 'Jordan', 'category' => 'Basketball', 'price' => 12495.00, 'color' => 'Gratitude White/Gold'],
            ['name' => 'Air Jordan 3 Retro', 'brand' => 'Jordan', 'category' => 'Basketball', 'price' => 10995.00, 'color' => 'White Cement Reimagined'],
            ['name' => 'Jordan Luka 2', 'brand' => 'Jordan', 'category' => 'Basketball', 'price' => 7495.00, 'color' => 'Lake Bled'],

            // Adidas
            ['name' => 'Adidas Ultraboost Light', 'brand' => 'Adidas', 'category' => 'Running', 'price' => 9500.00, 'color' => 'Core Black'],
            ['name' => 'Adidas Samba OG', 'brand' => 'Adidas', 'category' => 'Lifestyle', 'price' => 6800.00, 'color' => 'Cloud White/Core Black'],
            ['name' => 'Adidas Gazelle', 'brand' => 'Adidas', 'category' => 'Lifestyle', 'price' => 5800.00, 'color' => 'Collegiate Navy'],
            ['name' => 'Adidas Forum Low', 'brand' => 'Adidas', 'category' => 'Lifestyle', 'price' => 5500.00, 'color' => 'White/Royal Blue'],
            ['name' => 'Adidas NMD_R1 V3', 'brand' => 'Adidas', 'category' => 'Running', 'price' => 7500.00, 'color' => 'Grey Five'],
            ['name' => 'Adidas Harden Volume 8', 'brand' => 'Adidas', 'category' => 'Basketball', 'price' => 8500.00, 'color' => 'Flamingo Pink'],

            // New Balance
            ['name' => 'New Balance 550', 'brand' => 'New Balance', 'category' => 'Lifestyle', 'price' => 6495.00, 'color' => 'White/Green'],
            ['name' => 'New Balance 2002R', 'brand' => 'New Balance', 'category' => 'Lifestyle', 'price' => 8295.00, 'color' => 'Protection Pack Rain Cloud'],
            ['name' => 'New Balance 990v6 Made in USA', 'brand' => 'New Balance', 'category' => 'Running', 'price' => 12995.00, 'color' => 'Grey'],
            ['name' => 'New Balance 1906R', 'brand' => 'New Balance', 'category' => 'Running', 'price' => 8895.00, 'color' => 'Metallic Silver'],
            ['name' => 'New Balance 574 Core', 'brand' => 'New Balance', 'category' => 'Lifestyle', 'price' => 4995.00, 'color' => 'Navy Blue'],

            // Puma
            ['name' => 'Puma Suede Classic XXI', 'brand' => 'Puma', 'category' => 'Lifestyle', 'price' => 4400.00, 'color' => 'Black/White'],
            ['name' => 'Puma MB.03', 'brand' => 'Puma', 'category' => 'Basketball', 'price' => 7900.00, 'color' => 'Toxic Green'],
            ['name' => 'Puma RS-X Efekt', 'brand' => 'Puma', 'category' => 'Lifestyle', 'price' => 6200.00, 'color' => 'Feather Grey'],
            ['name' => 'Puma Deviate Nitro 2', 'brand' => 'Puma', 'category' => 'Running', 'price' => 8900.00, 'color' => 'Sunset Red'],
            ['name' => 'Puma Palermo', 'brand' => 'Puma', 'category' => 'Lifestyle', 'price' => 5200.00, 'color' => 'Cobalt Blue'],

            // Asics
            ['name' => 'Asics GEL-Kayano 30', 'brand' => 'Asics', 'category' => 'Running', 'price' => 9890.00, 'color' => 'Black/Glow Yellow'],
            ['name' => 'Asics GEL-NYC', 'brand' => 'Asics', 'category' => 'Lifestyle', 'price' => 7990.00, 'color' => 'Cream/Oatmeal'],
            ['name' => 'Asics GT-2000 12', 'brand' => 'Asics', 'category' => 'Running', 'price' => 7490.00, 'color' => 'Deep Ocean'],
            ['name' => 'Asics GEL-1130', 'brand' => 'Asics', 'category' => 'Lifestyle', 'price' => 5990.00, 'color' => 'White/Mid Grey'],
            ['name' => 'Asics Novablast 4', 'brand' => 'Asics', 'category' => 'Running', 'price' => 7890.00, 'color' => 'Illuminating Yellow'],

            // Converse
            ['name' => 'Converse Chuck Taylor All Star High Top', 'brand' => 'Converse', 'category' => 'Lifestyle', 'price' => 3990.00, 'color' => 'Classic Black'],
            ['name' => 'Converse Chuck 70 Vintage Canvas', 'brand' => 'Converse', 'category' => 'Lifestyle', 'price' => 4990.00, 'color' => 'Parchment'],
            ['name' => 'Converse Run Star Hike Lugged', 'brand' => 'Converse', 'category' => 'Lifestyle', 'price' => 6190.00, 'color' => 'Black/White'],
            ['name' => 'Converse One Star Pro', 'brand' => 'Converse', 'category' => 'Skateboarding', 'price' => 4590.00, 'color' => 'Egret White'],

            // Vans
            ['name' => 'Vans Old Skool Core Classics', 'brand' => 'Vans', 'category' => 'Skateboarding', 'price' => 3998.00, 'color' => 'Black/White Sidestripe'],
            ['name' => 'Vans Knu Skool', 'brand' => 'Vans', 'category' => 'Skateboarding', 'price' => 4798.00, 'color' => 'Mega Check Black'],
            ['name' => 'Vans Sk8-Hi Classic', 'brand' => 'Vans', 'category' => 'Skateboarding', 'price' => 4498.00, 'color' => 'True White'],
            ['name' => 'Vans Authentic', 'brand' => 'Vans', 'category' => 'Lifestyle', 'price' => 3498.00, 'color' => 'Navy'],
            ['name' => 'Vans Slip-On Checkerboard', 'brand' => 'Vans', 'category' => 'Lifestyle', 'price' => 3798.00, 'color' => 'Black/White Checker'],

            // Reebok
            ['name' => 'Reebok Club C 85', 'brand' => 'Reebok', 'category' => 'Lifestyle', 'price' => 4795.00, 'color' => 'Chalk/Glen Green'],
            ['name' => 'Reebok Nano X4', 'brand' => 'Reebok', 'category' => 'Training', 'price' => 7295.00, 'color' => 'Black/Acid Yellow'],
            ['name' => 'Reebok Question Mid', 'brand' => 'Reebok', 'category' => 'Basketball', 'price' => 8995.00, 'color' => 'Red Toe'],
            ['name' => 'Reebok Classic Leather', 'brand' => 'Reebok', 'category' => 'Lifestyle', 'price' => 4495.00, 'color' => 'White/Gum'],

            // Under Armour
            ['name' => 'Under Armour Curry Flow 11', 'brand' => 'Under Armour', 'category' => 'Basketball', 'price' => 8995.00, 'color' => 'Future Curry'],
            ['name' => 'Under Armour Project Rock 6', 'brand' => 'Under Armour', 'category' => 'Training', 'price' => 8295.00, 'color' => 'Black/Pitch Grey'],
            ['name' => 'Under Armour HOVR Phantom 3', 'brand' => 'Under Armour', 'category' => 'Running', 'price' => 7495.00, 'color' => 'White/Red'],
            ['name' => 'Under Armour Lockdown 6', 'brand' => 'Under Armour', 'category' => 'Basketball', 'price' => 3995.00, 'color' => 'Black/Metallic Gold'],
        ];

        $sizes = ['EU 40', 'EU 41', 'EU 42', 'EU 43', 'EU 44'];

        // 4. Seed Products and Variants
        foreach ($shoes as $index => $shoe) {
            $brand = $brands[$shoe['brand']] ?? $brands['Nike'];
            $category = $categories[$shoe['category']] ?? $categories['Lifestyle'];
            
            // Unique slug handling
            $slug = Str::slug($shoe['name']);
            if (Product::where('slug', $slug)->exists()) {
                $slug .= '-' . Str::random(4);
            }

            // Create Product
            $product = Product::create([
                'category_id' => $category->id,
                'brand_id'    => $brand->id,
                'name'        => $shoe['name'],
                'slug'        => $slug,
                'description' => "Experience premium craft with the {$shoe['name']}. Featuring lightweight responsive cushioning, durable traction, and high-tier materials engineered for everyday comfort and athletic performance.",
                'price'       => $shoe['price'],
                'image_path'  => $images[$index % count($images)],
                'is_active'   => true,
            ]);

            // Create Variants (Sizes EU 40-44 with stock)
            foreach ($sizes as $size) {
                ProductVariant::create([
                    'product_id'     => $product->id,
                    'size'           => $size,
                    'color'          => $shoe['color'],
                    'stock_quantity' => rand(8, 25), // Random stock allocation
                ]);
            }
        }
    }
}