<?php

namespace App\Http\Controllers;

use App\Models\GalleryImage;
use Illuminate\Support\Facades\Artisan;

class PageController extends Controller
{
    public function offline() { return view('offline'); }

    public function sitemap()
    {
        $path = public_path('sitemap.xml');
        if (!file_exists($path)) {
            Artisan::call('sitemap:generate');
        }
        return response()->file($path, ['Content-Type' => 'application/xml']);
    }

    public function downloadGuide()
    {
        return response()->download(public_path('client-guide.html'), 'T-Akomz-Website-Guide.html', [
            'Content-Type' => 'text/html',
        ]);
    }

    public function faq()    { return view('pages.faq'); }
    public function services() { return view('pages.services'); }
    public function gallery()
    {
        $images     = GalleryImage::active()->orderBy('sort_order')->orderByDesc('created_at')->get();
        $categories = $images->pluck('category')->unique()->filter()->values();
        return view('pages.gallery', compact('images', 'categories'));
    }
    public function privacy() { return view('pages.privacy'); }
    public function terms()  { return view('pages.terms'); }
    public function refund() { return view('pages.refund'); }
    public function shipping() { return view('pages.shipping'); }
}
