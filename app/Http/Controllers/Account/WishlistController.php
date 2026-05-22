<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\WishlistItem;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlist = auth()->user()->wishlistItems()->with('product.images')->latest()->paginate(12);
        return view('account.wishlist', compact('wishlist'));
    }

    public function toggle(Request $request)
    {
        $request->validate(['product_id' => ['required', 'integer', 'exists:products,id']]);

        $existing = WishlistItem::where('user_id', auth()->id())
                                ->where('product_id', $request->product_id)
                                ->first();

        if ($existing) {
            $existing->delete();
            $message = 'Removed from wishlist.';
            $added   = false;
        } else {
            WishlistItem::create([
                'user_id'    => auth()->id(),
                'product_id' => $request->product_id,
                'created_at' => now(),
            ]);
            $message = 'Added to wishlist!';
            $added   = true;
        }

        if ($request->wantsJson()) {
            return response()->json(['added' => $added, 'message' => $message]);
        }

        return back()->with('success', $message);
    }
}
