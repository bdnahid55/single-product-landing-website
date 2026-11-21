<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
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
        return view('admin.products.index', compact('products'));
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
            'regular_price'     => 'required|numeric|min:0',
            'sale_price'        => 'required|numeric|min:0',
            'stock'             => 'required|integer|min:0',
            'badge'             => 'nullable|string|max:50',
            'short_description' => 'nullable|string',
            'description'       => 'nullable|string',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'is_featured'       => 'nullable|boolean',
            'is_active'         => 'nullable|boolean',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
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
            'is_active'         => $request->boolean('is_active', true),
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
            'regular_price'     => 'required|numeric|min:0',
            'sale_price'        => 'required|numeric|min:0',
            'stock'             => 'required|integer|min:0',
            'badge'             => 'nullable|string|max:50',
            'short_description' => 'nullable|string',
            'description'       => 'nullable|string',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'is_featured'       => 'nullable|boolean',
            'is_active'         => 'nullable|boolean',
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
            'is_active'         => $request->boolean('is_active', true),
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
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
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }
}
