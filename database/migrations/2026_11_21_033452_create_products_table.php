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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('weight')->nullable(); // e.g. '১ কেজি', '৫০০ গ্রাম'
            $table->decimal('regular_price', 10, 2);
            $table->decimal('sale_price', 10, 2);
            $table->integer('stock')->default(100);
            $table->string('image')->nullable();
            $table->string('badge')->nullable(); // e.g. 'হট ডিল', 'বেস্ট সেলার'
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('tags')->nullable(); // e.g. '১০০% খাঁটি, সুগন্ধি, পুষ্টিকর'
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
