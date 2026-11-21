<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->text('customer_address');
            $table->string('delivery_area')->default('outside_dhaka'); // inside_dhaka, outside_dhaka
            $table->decimal('delivery_charge', 10, 2)->default(130.00);
            $table->decimal('subtotal', 10, 2)->default(0.00);
            $table->decimal('total_amount', 10, 2)->default(0.00);
            $table->string('payment_method')->default('cod'); // cod, bkash, nagad
            $table->string('payment_status')->default('unpaid'); // unpaid, paid
            $table->string('order_status')->default('pending'); // pending, processing, delivered, cancelled
            $table->text('customer_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
