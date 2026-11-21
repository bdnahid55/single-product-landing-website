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
        $settings = Setting::getAllSettings();
        $storeMode = $settings['store_mode'] ?? 'multiple';
        $singleProductId = $settings['single_product_id'] ?? null;

        $showPackagesGrid = $settings['show_packages_grid'] ?? 'all';
        if ($showPackagesGrid === '1') $showPackagesGrid = 'all';
        if ($showPackagesGrid === '0') $showPackagesGrid = 'none';

        $selectedGridProductIds = json_decode($settings['grid_product_ids'] ?? '[]', true) ?: [];

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
            $gridProducts = collect();
        } else {
            $products = Product::where('is_active', true)
                ->orderBy('is_featured', 'desc')
                ->orderBy('id', 'asc')
                ->get();

            $featuredProduct = $products->firstWhere('is_featured', true) ?? $products->first();

            if ($showPackagesGrid === 'none') {
                $gridProducts = collect();
            } elseif ($showPackagesGrid === 'selected' && !empty($selectedGridProductIds)) {
                $gridProducts = $products->whereIn('id', $selectedGridProductIds)->values();
            } else {
                $gridProducts = $products;
            }
        }

        $reviews = Review::where('is_approved', true)
            ->latest()
            ->take(6)
            ->get();

        return view('home', compact('products', 'featuredProduct', 'settings', 'reviews', 'storeMode', 'gridProducts', 'showPackagesGrid'));
    }
}
