<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index()
    {
        $products = Product::latest()->get();
        $allProducts = Product::orderBy('name')->get();
        $storeMode = Setting::get('store_mode', 'multiple');
        $singleProductId = Setting::get('single_product_id');

        $showPackagesGrid = Setting::get('show_packages_grid', 'all');
        if ($showPackagesGrid === '1') $showPackagesGrid = 'all';
        if ($showPackagesGrid === '0') $showPackagesGrid = 'none';

        $selectedGridProductIds = json_decode(Setting::get('grid_product_ids', '[]'), true) ?: [];

        return view('admin.products.index', compact('products', 'allProducts', 'storeMode', 'singleProductId', 'showPackagesGrid', 'selectedGridProductIds'));
    }

    /**
     * Update website sales mode (single product vs multiple products) and package grid visibility.
     */
    public function updateMode(Request $request)
    {
        $validated = $request->validate([
            'store_mode'          => 'required|in:single,multiple',
            'single_product_id'   => 'nullable|exists:products,id',
            'show_packages_grid'  => 'nullable|in:all,none,selected,0,1',
            'grid_product_ids'    => 'nullable|array',
            'grid_product_ids.*'  => 'exists:products,id',
        ]);

        Setting::set('store_mode', $validated['store_mode'], 'store_mode');

        if (!empty($validated['single_product_id'])) {
            Setting::set('single_product_id', $validated['single_product_id'], 'store_mode');
        }

        $showPackagesGrid = $validated['show_packages_grid'] ?? 'all';
        if ($showPackagesGrid === '1') $showPackagesGrid = 'all';
        if ($showPackagesGrid === '0') $showPackagesGrid = 'none';
        Setting::set('show_packages_grid', $showPackagesGrid, 'store_mode');

        $gridProductIds = $request->input('grid_product_ids', []);
        Setting::set('grid_product_ids', json_encode(array_values(array_map('intval', (array)$gridProductIds))), 'store_mode');

        $modeLabel = $validated['store_mode'] === 'single'
            ? 'Single Product Funnel (একক পণ্য মোড)'
            : 'Multi-Product Store (একাধিক পণ্য / প্যাকেজ মোড)';

        return back()->with('success', "ওয়েবসাইট বিক্রয় মোড ও প্যাকেজ গ্রিড সেটিংস সফলভাবে সংরক্ষণ করা হয়েছে: {$modeLabel}!");
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'weight'            => 'nullable|string|max:50',
            'regular_price'     => 'required|numeric|min:0|gte:sale_price',
            'sale_price'        => 'required|numeric|min:0|lte:regular_price',
            'stock'             => 'required|integer|min:0',
            'badge'             => 'nullable|string|max:50',
            'short_description' => 'nullable|string',
            'description'       => 'nullable|string',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'is_featured'       => 'nullable|boolean',
            'is_active'         => 'nullable|boolean',
        ], [
            'regular_price.gte' => 'নিয়মিত মূল্য (Regular Price) অবশ্যই বিক্রয় মূল্য (Sale Price) এর সমান বা বেশি হতে হবে।',
            'sale_price.lte'    => 'বিক্রয় মূল্য (Sale Price) নিয়মিত মূল্যের (Regular Price) চেয়ে বেশি হতে পারবে না।',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $imagePath = 'uploads/' . $path;
        }

        $isFeatured = $request->boolean('is_featured');
        if ($isFeatured) {
            Product::where('is_featured', true)->update(['is_featured' => false]);
        }

        Product::create([
            'name'              => $validated['name'],
            'slug'              => Str::slug($validated['name']) . '-' . rand(100, 999),
            'weight'            => $validated['weight'] ?? null,
            'regular_price'     => $validated['regular_price'],
            'sale_price'        => $validated['sale_price'],
            'stock'             => $validated['stock'],
            'badge'             => $validated['badge'] ?? null,
            'short_description' => $validated['short_description'] ?? null,
            'description'       => $validated['description'] ?? null,
            'image'             => $imagePath,
            'is_featured'       => $isFeatured,
            'is_active'         => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully!');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'weight'            => 'nullable|string|max:50',
            'regular_price'     => 'required|numeric|min:0|gte:sale_price',
            'sale_price'        => 'required|numeric|min:0|lte:regular_price',
            'stock'             => 'required|integer|min:0',
            'badge'             => 'nullable|string|max:50',
            'short_description' => 'nullable|string',
            'description'       => 'nullable|string',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'is_featured'       => 'nullable|boolean',
            'is_active'         => 'nullable|boolean',
        ], [
            'regular_price.gte' => 'নিয়মিত মূল্য (Regular Price) অবশ্যই বিক্রয় মূল্য (Sale Price) এর সমান বা বেশি হতে হবে।',
            'sale_price.lte'    => 'বিক্রয় মূল্য (Sale Price) নিয়মিত মূল্যের (Regular Price) চেয়ে বেশি হতে পারবে না।',
        ]);

        $data = [
            'name'              => $validated['name'],
            'weight'            => $validated['weight'] ?? null,
            'regular_price'     => $validated['regular_price'],
            'sale_price'        => $validated['sale_price'],
            'stock'             => $validated['stock'],
            'badge'             => $validated['badge'] ?? null,
            'short_description' => $validated['short_description'] ?? null,
            'description'       => $validated['description'] ?? null,
            'is_active'         => $request->boolean('is_active'),
        ];

        if ($request->hasFile('image')) {
            // Delete old uploaded image file before storing new one
            $product->deleteImageFile();
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = 'uploads/' . $path;
        }

        $isFeatured = $request->boolean('is_featured');
        if ($isFeatured) {
            Product::where('id', '!=', $product->id)->update(['is_featured' => false]);
        }
        $data['is_featured'] = $isFeatured;

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Quick toggle for product active status.
     */
    public function toggleStatus(Product $product)
    {
        $newStatus = !$product->is_active;
        $product->update(['is_active' => $newStatus]);

        $statusLabel = $newStatus ? 'Published (Active)' : 'Unpublished (Inactive)';
        return back()->with('success', "Product '{$product->name}' is now {$statusLabel}!");
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }
}
