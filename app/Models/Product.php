<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'weight',
        'regular_price',
        'sale_price',
        'stock',
        'image',
        'badge',
        'short_description',
        'description',
        'tags',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'regular_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'stock' => 'integer',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name) . '-' . rand(100, 999);
            }
        });

        static::deleting(function ($product) {
            $product->deleteImageFile();
        });
    }

    /**
     * Delete the associated uploaded image file from disk (guarding default assets).
     */
    public function deleteImageFile(): void
    {
        if (!empty($this->image)) {
            // Never delete default template or seed assets
            if (str_starts_with($this->image, 'assets/')) {
                return;
            }

            $cleanPath = ltrim(str_replace(['storage/', 'uploads/'], '', $this->image), '/');

            // 1. Delete via Storage disk 'public' (which points to public/uploads)
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($cleanPath)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($cleanPath);
            }

            // 2. Direct unlink inside public/uploads/
            $inUploads = public_path('uploads/' . $cleanPath);
            if (file_exists($inUploads) && is_file($inUploads)) {
                @unlink($inUploads);
            }

            // 3. Direct unlink inside public/
            $inPublic = public_path($this->image);
            if (file_exists($inPublic) && is_file($inPublic)) {
                @unlink($inPublic);
            }
        }
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            // If already a full URL
            if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
                return $this->image;
            }
            // Direct path in public folder (e.g. assets/images/... or uploads/products/...)
            if (file_exists(public_path($this->image))) {
                return asset($this->image);
            }
            // File in public/uploads/
            if (file_exists(public_path('uploads/' . $this->image))) {
                return asset('uploads/' . $this->image);
            }
            // Starts with uploads/ (serve via asset)
            if (str_starts_with($this->image, 'uploads/')) {
                return asset($this->image);
            }
            // Legacy fallback if in public/storage/
            if (file_exists(public_path('storage/' . $this->image))) {
                return asset('storage/' . $this->image);
            }
            // Fallback: public/uploads link
            return asset('uploads/' . ltrim($this->image, '/'));
        }
        return asset('assets/images/featured-honey-bottle.png');
    }
}
