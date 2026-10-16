<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $variant = ProductVariant::with('product')->findOrFail($request->variant_id);

        // Server-side validation: Do not trust browser quantities
        if ($variant->stock_quantity < $request->quantity) {
            return back()->with('error', 'Sorry, we do not have enough stock for this size.');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$variant->id])) {
            $newQuantity = $cart[$variant->id]['quantity'] + $request->quantity;

            if ($variant->stock_quantity < $newQuantity) {
                return back()->with('error', 'Cannot add more. Stock limit reached.');
            }

            $cart[$variant->id]['quantity'] = $newQuantity;
        } else {
            $cart[$variant->id] = [
                'variant_id' => $variant->id,
                'product_name' => $variant->product->name,
                'color' => $variant->color,
                'size' => $variant->size,
                'price' => $variant->product->price,
                'image_path' => $variant->product->image_path,
                'quantity' => $request->quantity,
            ];
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Shoe added to your shopping bag!');
    }

    public function index()
    {
        $cart = session()->get('cart', []);

        $total = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        return view('store.cart', compact('cart', 'total'));
    }

    public function remove(Request $request)
    {
        $request->validate(['variant_id' => 'required']);

        $cart = session()->get('cart', []);

        if (isset($cart[$request->variant_id])) {
            unset($cart[$request->variant_id]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Item removed from shopping bag.');
    }
}
