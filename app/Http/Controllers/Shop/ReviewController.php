<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'rating'     => ['required', 'integer', 'min:1', 'max:5'],
            'title'      => ['nullable', 'string', 'max:100'],
            'body'       => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        // Prevent duplicate reviews
        $exists = Review::where('user_id', auth()->id())
                        ->where('product_id', $data['product_id'])
                        ->exists();

        if ($exists) {
            return back()->with('error', 'You have already reviewed this product.');
        }

        Review::create([
            ...$data,
            'user_id'     => auth()->id(),
            'is_approved' => false,
        ]);

        return back()->with('success', 'Your review has been submitted and is pending approval. Thank you!');
    }
}
