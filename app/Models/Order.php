<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_phone',
        'customer_address',
        'delivery_area',
        'delivery_charge',
        'subtotal',
        'total_amount',
        'currency',
        'payment_method',
        'payment_status',
        'transaction_id',
        'bank_tran_id',
        'card_type',
        'payment_details',
        'order_status',
        'customer_notes',
        'admin_notes',
    ];

    protected $casts = [
        'delivery_charge' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function generateOrderNumber(): string
    {
        $prefix = 'ORD-';
        $random = strtoupper(bin2hex(random_bytes(3))); // 6 hex chars
        $timestamp = now()->timezone('Asia/Dhaka')->format('ymd');
        return $prefix . $timestamp . '-' . $random;
    }
}
