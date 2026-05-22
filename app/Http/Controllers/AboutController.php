<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;

class AboutController extends Controller
{
    public function index()
    {
        $stats = SiteSetting::getMany([
            'about_founding_year', 'about_acres',
            'about_chickens', 'about_eggs_daily', 'about_customers',
        ]);

        $seoTitle       = 'About Us — Our Farm Story';
        $seoDescription = 'Learn about T-Akomz Agro Estates — founded on the belief that Nigerians deserve the freshest farm produce, raised with care on our 50-acre estate in Ekiti State.';

        return view('pages.about', compact('stats', 'seoTitle', 'seoDescription'));
    }
}
