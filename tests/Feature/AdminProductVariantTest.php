<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProductVariantTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_variant_color_size_and_stock(): void
    {
        $brand = Brand::create(['name' => 'Jordan', 'slug' => 'jordan']);
        $category = Category::create(['name' => 'Basketball', 'slug' => 'basketball']);
        $product = Product::create([
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'name' => 'Jordan Air Max',
            'slug' => 'jordan-air-max',
            'description' => 'Sample shoe',
            'price' => 8200,
            'is_active' => true,
        ]);
        $variant = $product->variants()->create([
            'color' => 'Black / White',
            'size' => 'EU 40',
            'stock_quantity' => 4,
        ]);
        $admin = new User(User::factory()->raw(['role' => 'admin']));
        $admin->save();

        $this->actingAs($admin)
            ->put(route('admin.products.variants.update', [$product, $variant]), [
                'color' => 'University Red',
                'size' => 'EU 42',
                'stock_quantity' => 12,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('product_variants', [
            'id' => $variant->id,
            'product_id' => $product->id,
            'color' => 'University Red',
            'size' => 'EU 42',
            'stock_quantity' => 12,
        ]);
    }
}