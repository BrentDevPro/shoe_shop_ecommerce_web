<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('store.index')->with('error', 'Your shopping bag is empty.');
        }

        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $shipping_fee = 150.00; // Flat rate for Philippines
        
        return view('store.checkout', compact('cart', 'total', 'shipping_fee'));
    }

    public function process(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('store.index');
        }

        $request->validate([
            'shipping_address' => 'required|string',
            'payment_method' => 'required|in:COD,GCash,Maya,Visa',
        ]);

        try {
            DB::beginTransaction();

            $total_amount = 0;
            $shipping_fee = 150.00;

            // 1. Verify stock and calculate exact total from DB (never trust browser/session prices)
            foreach ($cart as $id => $details) {
                // lockForUpdate() prevents another user from buying this exact shoe at the same millisecond
                $variant = ProductVariant::with('product')->lockForUpdate()->findOrFail($id);
                
                if ($variant->stock_quantity < $details['quantity']) {
                    throw new \Exception("Sorry, not enough stock left for " . $variant->product->name . " (Size: " . $variant->size . "). Please reduce quantity or remove.");
                }
                
                $total_amount += $variant->product->price * $details['quantity'];
            }

            // 2. Create the Order
            $order = Order::create([
                'user_id' => Auth::id(),
                'tracking_number' => 'TRK-' . strtoupper(Str::random(10)),
                'total_amount' => $total_amount + $shipping_fee,
                'shipping_fee' => $shipping_fee,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'shipping_address' => $request->shipping_address,
            ]);

            // 3. Create Order Items & Deduct Inventory Stock
            foreach ($cart as $id => $details) {
                $variant = ProductVariant::find($id);
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $variant->id,
                    'quantity' => $details['quantity'],
                    'price_at_purchase' => $variant->product->price,
                ]);

                $variant->decrement('stock_quantity', $details['quantity']);
            }

            DB::commit();
            
            // 4. Clear the cart and redirect
            session()->forget('cart');

            return redirect()->route('store.index')->with('success', 'Order placed successfully! Your Tracking Number is: ' . $order->tracking_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Checkout failed: ' . $e->getMessage());
        }
    }
}