<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminProductImageTest extends TestCase
{
    use RefreshDatabase;

    public function test_external_product_image_urls_are_preserved(): void
    {
        $url = 'https://images.example.test/shoe.jpg';

        $this->assertSame($url, (new Product(['image_path' => $url]))->image_url);
    }

    public function test_admin_can_select_existing_or_uploaded_photo_as_main(): void
    {
        Storage::fake('public');
        $oldImagePath = 'products/old-main.jpg';
        Storage::disk('public')->put($oldImagePath, 'old image');

        $brand = Brand::create(['name' => 'Jordan', 'slug' => 'jordan']);
        $category = Category::create(['name' => 'Basketball', 'slug' => 'basketball']);
        $product = Product::create([
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'name' => 'Jordan Air Max',
            'slug' => 'jordan-air-max',
            'description' => 'Sample shoe',
            'price' => 8200,
            'image_path' => $oldImagePath,
            'is_active' => true,
        ]);
        $admin = new User(User::factory()->raw(['role' => 'admin']));
        $admin->save();

        $this->actingAs($admin)
            ->get(route('admin.products.edit', $product))
            ->assertOk()
            ->assertSee(asset('storage/' . $oldImagePath), false)
            ->assertSee('name="images[]"', false)
            ->assertDontSee('name="main_image"', false);

        $this->actingAs($admin)
            ->put(route('admin.products.update', $product), [
                'name' => 'Jordan Air Max',
                'price' => 8200,
                'brand_id' => $brand->id,
                'category_id' => $category->id,
                'description' => 'Sample shoe',
                'images' => [
                    UploadedFile::fake()->create('replacement.jpg', 100, 'image/jpeg'),
                    UploadedFile::fake()->create('gallery.jpg', 100, 'image/jpeg'),
                ],
                'main_image_index' => 0,
            ])
            ->assertRedirect(route('admin.products.index'));

        $product->refresh();
        $this->assertNotSame($oldImagePath, $product->image_path);
        $this->assertTrue(Storage::disk('public')->exists($product->image_path));
        $this->assertTrue(Storage::disk('public')->exists($oldImagePath));
        $oldMainGalleryImage = $product->images()->where('image_path', $oldImagePath)->firstOrFail();
        $this->assertCount(2, $product->images);

        $this->actingAs($admin)
            ->put(route('admin.products.update', $product), [
                'name' => 'Jordan Air Max',
                'price' => 8200,
                'brand_id' => $brand->id,
                'category_id' => $category->id,
                'description' => 'Sample shoe',
                'main_image_id' => $oldMainGalleryImage->id,
            ])
            ->assertRedirect(route('admin.products.index'));

        $product->refresh();
        $this->assertSame($oldImagePath, $product->image_path);
        $this->assertTrue($product->images()->where('image_path', $oldImagePath)->doesntExist());
        $this->assertCount(2, $product->images);
    }
}