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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('currency', 10)->default('BDT')->after('total_amount');
            $table->string('transaction_id')->nullable()->after('payment_status');
            $table->string('bank_tran_id')->nullable()->after('transaction_id');
            $table->string('card_type')->nullable()->after('bank_tran_id');
            $table->text('payment_details')->nullable()->after('card_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'currency',
                'transaction_id',
                'bank_tran_id',
                'card_type',
                'payment_details',
            ]);
        });
    }
};
