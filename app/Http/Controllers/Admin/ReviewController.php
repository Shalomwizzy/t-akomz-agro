<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        $pending  = Review::with('product', 'user')->where('is_approved', false)->latest()->get();
        $approved = Review::with('product', 'user')->where('is_approved', true)->latest()->paginate(20);

        return view('admin.reviews.index', compact('pending', 'approved'));
    }

    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);
        return back()->with('success', 'Review approved.');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Review deleted.');
    }
}
