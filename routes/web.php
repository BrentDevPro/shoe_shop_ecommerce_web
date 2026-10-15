<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\AdminRegisterController;
use App\Http\Controllers\Customer\CatalogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\OrderHistoryController;

// Public Routes
Route::get('/', [CatalogController::class, 'index'])->name('store.index');
Route::get('/product/{slug}', [CatalogController::class, 'show'])->name('store.product');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');


Route::get('/admin/register', [AdminRegisterController::class, 'showRegistrationForm'])->name('admin.register');
Route::post('/admin/register', [AdminRegisterController::class, 'register'])->name('admin.register.submit');
// Auth Routes (Login, Register, Password Reset provided by laravel/ui)
Auth::routes();

// Customer Routes (Requires normal login)
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Customer Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Checkout & Orders
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/orders', [OrderHistoryController::class, 'index'])->name('orders.index');

    Route::get('/orders/{order}', [OrderHistoryController::class, 'show'])->name('orders.show');
});

// Admin Routes (Requires login AND admin role)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');

    // Products & Categories
    Route::resource('products', ProductController::class);
    
    // Delete individual gallery image
    Route::delete('/products/gallery-image/{id}', [App\Http\Controllers\Admin\ProductController::class, 'destroyGalleryImage'])->name('products.gallery.destroy');
    // Products, Categories & Brands
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);

    Route::resource('categories', CategoryController::class)->only(['index', 'store', 'update', 'destroy']);

    
    //Brands Routes
    Route::resource('brands', App\Http\Controllers\Admin\BrandController::class)->only(['index', 'store', 'update', 'destroy']);

    // Product Variants
    Route::get('/products/{product}/variants', [ProductVariantController::class, 'index'])->name('products.variants.index');
    Route::post('/products/{product}/variants', [ProductVariantController::class, 'store'])->name('products.variants.store');
    Route::put('/products/{product}/variants/{variant}', [ProductVariantController::class, 'update'])->name('products.variants.update');
    Route::delete('/products/{product}/variants/{variant}', [ProductVariantController::class, 'destroy'])->name('products.variants.destroy');

    // Order Management
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
});
