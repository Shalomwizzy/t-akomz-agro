<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        [$items, $subtotal] = $this->getCartData();

        $coupon   = session('coupon');
        $discount = session('discount', 0);

        $deliveryFee = (float) SiteSetting::get('delivery_fee_standard', 1500);
        if ($coupon && $coupon['type'] === 'FREE_DELIVERY') {
            $deliveryFee = 0;
        }

        $total = max(0, $subtotal - $discount + $deliveryFee);

        return view('cart.index', compact('items', 'subtotal', 'discount', 'deliveryFee', 'total', 'coupon'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity'   => ['integer', 'min:1', 'max:100'],
        ]);

        $product  = Product::findOrFail($request->product_id);
        $quantity = (int) $request->input('quantity', 1);

        if (!$product->is_active || $product->stock < 1) {
            return back()->with('error', 'This product is not available.');
        }

        // Session cart
        $cart = session('cart', []);
        $current = $cart[$product->id] ?? 0;
        $cart[$product->id] = min($current + $quantity, $product->stock);
        session(['cart' => $cart]);

        // Sync to DB for logged-in users
        if (auth()->check()) {
            CartItem::updateOrCreate(
                ['user_id' => auth()->id(), 'product_id' => $product->id],
                ['quantity' => $cart[$product->id]]
            );
        }

        $cartCount = array_sum(session('cart', []));

        if ($request->wantsJson()) {
            return response()->json([
                'success'   => true,
                'message'   => "\"{$product->name}\" added to cart.",
                'cartCount' => $cartCount,
            ]);
        }

        return back()->with('success', "\"{$product->name}\" added to cart.");
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity'   => ['required', 'integer', 'min:0'],
        ]);

        $productId = $request->product_id;
        $quantity  = (int) $request->quantity;
        $cart      = session('cart', []);

        if ($quantity <= 0) {
            unset($cart[$productId]);
            if (auth()->check()) {
                CartItem::where('user_id', auth()->id())->where('product_id', $productId)->delete();
            }
        } else {
            $product = Product::find($productId);
            $cart[$productId] = $product ? min($quantity, $product->stock) : $quantity;
            if (auth()->check()) {
                CartItem::updateOrCreate(
                    ['user_id' => auth()->id(), 'product_id' => $productId],
                    ['quantity' => $cart[$productId]]
                );
            }
        }

        session(['cart' => $cart]);

        return back();
    }

    public function remove(Request $request)
    {
        $request->validate(['product_id' => ['required', 'integer']]);

        $cart = session('cart', []);
        unset($cart[$request->product_id]);
        session(['cart' => $cart]);

        if (auth()->check()) {
            CartItem::where('user_id', auth()->id())->where('product_id', $request->product_id)->delete();
        }

        return back()->with('success', 'Item removed from cart.');
    }

    public function removeCoupon()
    {
        session()->forget(['coupon', 'discount']);
        return back()->with('success', 'Coupon removed.');
    }

    public function clear()
    {
        session()->forget(['cart', 'coupon', 'discount']);
        if (auth()->check()) {
            CartItem::where('user_id', auth()->id())->delete();
        }
        return back()->with('success', 'Cart cleared.');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => ['required', 'string']]);

        [, $subtotal] = $this->getCartData();

        $coupon = Coupon::where('code', strtoupper($request->code))->first();

        if (!$coupon || !$coupon->isValid($subtotal)) {
            return back()->with('error', 'Invalid or expired coupon code.');
        }

        $deliveryFee = (float) SiteSetting::get('delivery_fee_standard', 1500);
        $discount    = $coupon->calculateDiscount($subtotal, $deliveryFee);

        session([
            'coupon'   => ['code' => $coupon->code, 'type' => $coupon->type, 'value' => $coupon->value],
            'discount' => $discount,
        ]);

        return back()->with('success', "Coupon \"{$coupon->code}\" applied! You save ₦" . number_format($discount, 2));
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    private function getCartData(): array
    {
        $cart     = session('cart', []);
        $items    = [];
        $subtotal = 0;

        if (!empty($cart)) {
            $products = Product::with('images')->whereIn('id', array_keys($cart))->get()->keyBy('id');
            foreach ($cart as $productId => $qty) {
                if ($products->has($productId)) {
                    $product  = $products[$productId];
                    $lineTotal = $product->price * $qty;
                    $items[]   = compact('product', 'qty', 'lineTotal');
                    $subtotal += $lineTotal;
                }
            }
        }

        return [$items, $subtotal];
    }
}
