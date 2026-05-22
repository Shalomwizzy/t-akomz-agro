<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\TeamMember;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::active()->withCount('activeProducts')->get();

        $featured = Product::active()
            ->featured()
            ->with('images', 'category')
            ->inStock()
            ->limit(8)
            ->get();

        $latestPosts = BlogPost::published()
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        $stats = SiteSetting::getMany([
            'about_founding_year', 'about_acres',
            'about_chickens', 'about_eggs_daily', 'about_customers',
        ]);

        $seoTitle       = 'Nigeria\'s Premier Farm Estate';
        $seoDescription = 'T-Akomz Agro Estates — premium farm-fresh poultry, eggs, livestock and organic produce. Delivered from our 50-acre estate in Ekiti State to your door.';

        $team = TeamMember::active()->get();

        return view('pages.home', compact('categories', 'featured', 'latestPosts', 'stats', 'seoTitle', 'seoDescription', 'team'));
    }
}
