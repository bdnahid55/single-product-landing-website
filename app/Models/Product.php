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

            $relativePath = str_starts_with($this->image, 'storage/')
                ? substr($this->image, strlen('storage/'))
                : $this->image;

            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($relativePath)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($relativePath);
            }
        }
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image && file_exists(public_path('storage/' . $this->image))) {
            return asset('storage/' . $this->image);
        }
        if ($this->image && file_exists(public_path($this->image))) {
            return asset($this->image);
        }
        return asset('assets/images/featured-honey-bottle.png');
    }
}
