<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Setting;
use App\Models\Review;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the landing page.
     */
    public function index()
    {
        $products = Product::where('is_active', true)
            ->orderBy('is_featured', 'desc')
            ->orderBy('id', 'asc')
            ->get();

        $featuredProduct = $products->firstWhere('is_featured', true) ?? $products->first();

        $settings = Setting::all()->pluck('value', 'key')->toArray();

        $reviews = Review::where('is_approved', true)
            ->latest()
            ->take(6)
            ->get();

        return view('home', compact('products', 'featuredProduct', 'settings', 'reviews'));
    }
}
