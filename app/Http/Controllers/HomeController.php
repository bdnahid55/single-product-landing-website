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
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        $storeMode = $settings['store_mode'] ?? 'multiple';
        $singleProductId = $settings['single_product_id'] ?? null;

        if ($storeMode === 'single') {
            $singleProduct = null;
            if ($singleProductId) {
                $singleProduct = Product::where('is_active', true)->find($singleProductId);
            }
            if (!$singleProduct) {
                $singleProduct = Product::where('is_active', true)->where('is_featured', true)->first()
                    ?? Product::where('is_active', true)->first();
            }

            $products = $singleProduct ? collect([$singleProduct]) : collect();
            $featuredProduct = $singleProduct;
        } else {
            $products = Product::where('is_active', true)
                ->orderBy('is_featured', 'desc')
                ->orderBy('id', 'asc')
                ->get();

            $featuredProduct = $products->firstWhere('is_featured', true) ?? $products->first();
        }

        $reviews = Review::where('is_approved', true)
            ->latest()
            ->take(6)
            ->get();

        return view('home', compact('products', 'featuredProduct', 'settings', 'reviews', 'storeMode'));
    }
}
