<?php

namespace App\Http\Controllers;

use App\Library\SslCommerz\SslCommerzNotification;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SSLCommerzPaymentController extends Controller
{
    /**
     * Initiate SSLCommerz hosted payment gateway session for an order.
     */
    public function initiate(Order $order)
    {
        $enabled = Setting::get('sslcommerz_enabled', '1');
        if ($enabled === '0') {
            return redirect()->route('orders.payment_status', $order->id)
                ->with('error', 'অনলাইন পেমেন্ট গেটওয়ে বর্তমানে নিষ্ক্রিয় রয়েছে। অনুগ্রহ করে ক্যাশ অন ডেলিভারি (COD) নির্বাচন করুন।');
        }

        // If order is already paid, go straight to success page
        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.success', $order->id)
                ->with('info', 'আপনার এই অর্ডারটি ইতিমধ্যেই পরিশোধিত হয়েছে।');
        }

        $order->load('items');

        $productNames = $order->items->pluck('product_name')->implode(', ');
        if (empty($productNames)) {
            $productNames = 'সুন্দরবনের খাঁটি মধু';
        }

        $city = $order->delivery_area === 'inside_dhaka' ? 'Dhaka' : 'Outside Dhaka';
        $address = $order->customer_address ?: 'Dhaka, Bangladesh';
        $phone = $order->customer_phone ?: '01711111111';
        $name = $order->customer_name ?: 'Customer';

        // Full payload structured exactly as required by official SSLCommerz PHP sample
        $postData = [
            'total_amount'     => (string) number_format($order->total_amount, 2, '.', ''),
            'currency'         => $order->currency ?: 'BDT',
            'tran_id'          => $order->order_number,
            'success_url'      => route('sslcommerz.success'),
            'fail_url'         => route('sslcommerz.fail'),
            'cancel_url'       => route('sslcommerz.cancel'),
            'ipn_url'          => route('sslcommerz.ipn'),

            # Customer Information
            'cus_name'         => $name,
            'cus_email'        => 'customer@modhu.com',
            'cus_add1'         => $address,
            'cus_add2'         => $address,
            'cus_city'         => $city,
            'cus_state'        => $city,
            'cus_postcode'     => '1000',
            'cus_country'      => 'Bangladesh',
            'cus_phone'        => $phone,
            'cus_fax'          => $phone,

            # Shipment & Order Info
            'shipping_method'  => 'YES',
            'num_of_item'      => max(1, $order->items->count()),
            'ship_name'        => $name,
            'ship_add1'        => $address,
            'ship_add2'        => $address,
            'ship_city'        => $city,
            'ship_state'       => $city,
            'ship_postcode'    => '1000',
            'ship_country'     => 'Bangladesh',

            # Product Information
            'product_name'     => mb_substr($productNames, 0, 255),
            'product_category' => 'Honey & Natural Foods',
            'product_profile'  => 'physical-goods',
            'emi_option'       => '0',
        ];

        $sslc = app(SslCommerzNotification::class);
        $paymentResponse = $sslc->makePayment($postData, 'hosted');

        if (!empty($paymentResponse['status']) && $paymentResponse['status'] === 'SUCCESS' && !empty($paymentResponse['GatewayPageURL'])) {
            return redirect()->away($paymentResponse['GatewayPageURL']);
        }

        $reason = $paymentResponse['failedreason'] ?? 'গেটওয়ে ইনিশিয়ালাইজেশন ব্যর্থ হয়েছে।';
        Log::error("SSLCommerz initiation failed for {$order->order_number}: {$reason}");

        $order->update([
            'payment_status' => 'failed',
            'admin_notes'    => ($order->admin_notes ? $order->admin_notes . "\n" : '') . "SSLCommerz initiation failed: {$reason}",
        ]);

        return redirect()->route('orders.payment_status', $order->id)
            ->with('error', "অনলাইন পেমেন্ট গেটওয়েতে সংযোগ করতে সমস্যা হয়েছে ({$reason})। অনুগ্রহ করে আবার চেষ্টা করুন অথবা ক্যাশ অন ডেলিভারিতে অর্ডার নিশ্চিত করুন।");
    }

    /**
     * Display order payment retry / status page (for pending, failed, or cancelled online payments).
     */
    public function paymentStatus(Order $order)
    {
        // If already paid, go directly to order success
        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.success', $order->id);
        }

        $order->load('items');
        $settings = Setting::getAllSettings();

        return view('order-payment-status', compact('order', 'settings'));
    }

    /**
     * Allow customer to convert an unpaid online order to Cash on Delivery (COD).
     */
    public function convertToCod(Order $order)
    {
        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.success', $order->id)
                ->with('info', 'অর্ডারটির পেমেন্ট ইতিমধ্যেই পরিশোধিত রয়েছে।');
        }

        $order->update([
            'payment_method' => 'cod',
            'payment_status' => 'unpaid',
            'admin_notes'    => ($order->admin_notes ? $order->admin_notes . "\n" : '') . 'গ্রাহক অনলাইন পেমেন্ট অপশন থেকে ক্যাশ অন ডেলিভারিতে (COD) রূপান্তর করেছেন।',
        ]);

        return redirect()->route('orders.success', $order->id)
            ->with('success', 'আপনার অর্ডারটি সফলভাবে ক্যাশ অন ডেলিভারি (COD) হিসেবে নিশ্চিত করা হয়েছে! পণ্য গ্রহণের সময় মূল্য পরিশোধ করুন।');
    }

    /**
     * SSLCommerz Success Callback.
     */
    public function success(Request $request)
    {
        $tranId = trim($request->input('tran_id', ''));
        $valId = trim($request->input('val_id', ''));
        $amount = floatval($request->input('amount', 0));
        $currency = $request->input('currency', 'BDT');

        if (empty($tranId)) {
            return redirect()->route('home')->with('error', 'অকার্যকর ট্রানজেকশন আইডি।');
        }

        $order = Order::where('order_number', $tranId)->first();

        if (!$order) {
            Log::error("SSLCommerz success callback: Order {$tranId} not found.");
            return redirect()->route('home')->with('error', 'অর্ডার খুঁজে পাওয়া যায়নি।');
        }

        // If already paid (e.g. by fast IPN), redirect directly
        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.success', $order->id)
                ->with('success', 'আপনার পেমেন্ট সফলভাবে যাচাই করা হয়েছে!');
        }

        $sslc = app(SslCommerzNotification::class);
        $isValid = $sslc->orderValidate($tranId, $order->total_amount, $order->currency ?: 'BDT', $request->all());

        if ($isValid) {
            $cardType = $request->input('card_type', 'SSLCommerz');
            $bankTranId = $request->input('bank_tran_id');

            $order->update([
                'payment_status'  => 'paid',
                'transaction_id'  => $valId ?: ($bankTranId ?: $tranId),
                'bank_tran_id'    => $bankTranId,
                'card_type'       => $cardType,
                'payment_details' => json_encode($request->all()),
                'admin_notes'     => ($order->admin_notes ? $order->admin_notes . "\n" : '') . "Online payment successful via SSLCommerz [Card/Method: {$cardType}, ValID: {$valId}]",
            ]);

            Log::info("SSLCommerz payment validated successfully for Order #{$order->order_number} [ValID: {$valId}]");

            return redirect()->route('orders.success', $order->id)
                ->with('success', 'ধন্যবাদ! আপনার অনলাইন পেমেন্ট সফল হয়েছে এবং অর্ডারটি নিশ্চিত করা হয়েছে।');
        }

        $validationError = $sslc->error ?: 'পেমেন্ট ভেরিফিকেশন ব্যর্থ হয়েছে।';
        Log::warning("SSLCommerz payment verification failed for Order #{$order->order_number}: {$validationError}");

        $order->update([
            'payment_status' => 'failed',
            'admin_notes'    => ($order->admin_notes ? $order->admin_notes . "\n" : '') . "SSLCommerz validation failed: {$validationError}",
        ]);

        return redirect()->route('orders.payment_status', $order->id)
            ->with('error', "পেমেন্ট সম্পন্ন হয়েছে কিন্তু ভ্যালিডেশনে সমস্যা দেখা দিয়েছে ({$validationError})। অনুগ্রহ করে পুনরায় চেষ্টা করুন অথবা ক্যাশ অন ডেলিভারি বেছে নিন।");
    }

    /**
     * SSLCommerz Fail Callback.
     */
    public function fail(Request $request)
    {
        $tranId = trim($request->input('tran_id', ''));
        $order = !empty($tranId) ? Order::where('order_number', $tranId)->first() : null;

        if ($order) {
            $reason = $request->input('error') ?: 'পেমেন্ট ব্যর্থ হয়েছে।';
            $order->update([
                'payment_status' => 'failed',
                'admin_notes'    => ($order->admin_notes ? $order->admin_notes . "\n" : '') . "SSLCommerz payment failed: {$reason}",
            ]);

            Log::info("SSLCommerz payment failed for Order #{$order->order_number}: {$reason}");

            return redirect()->route('orders.payment_status', $order->id)
                ->with('error', 'আপনার অনলাইন পেমেন্টটি সম্পন্ন হতে পারেনি (' . $reason . ')। অনুগ্রহ করে আবার চেষ্টা করুন অথবা ক্যাশ অন ডেলিভারিতে সম্পন্ন করুন।');
        }

        return redirect()->route('home')->with('error', 'পেমেন্ট সম্পন্ন করা সম্ভব হয়নি। অনুগ্রহ করে পুনরায় চেষ্টা করুন।');
    }

    /**
     * SSLCommerz Cancel Callback.
     */
    public function cancel(Request $request)
    {
        $tranId = trim($request->input('tran_id', ''));
        $order = !empty($tranId) ? Order::where('order_number', $tranId)->first() : null;

        if ($order) {
            $order->update([
                'payment_status' => 'cancelled',
                'admin_notes'    => ($order->admin_notes ? $order->admin_notes . "\n" : '') . "SSLCommerz payment was cancelled by customer.",
            ]);

            Log::info("SSLCommerz payment cancelled for Order #{$order->order_number}");

            return redirect()->route('orders.payment_status', $order->id)
                ->with('warning', 'আপনি অনলাইন পেমেন্ট প্রক্রিয়াটি বাতিল করেছেন। আপনি চাইলে আবার পেমেন্ট করতে পারেন অথবা ক্যাশ অন ডেলিভারিতে অর্ডার নিশ্চিত করতে পারেন।');
        }

        return redirect()->route('home')->with('info', 'পেমেন্ট প্রক্রিয়া বাতিল করা হয়েছে।');
    }

    /**
     * SSLCommerz Instant Payment Notification (IPN) Webhook.
     */
    public function ipn(Request $request)
    {
        $tranId = trim($request->input('tran_id', ''));
        $valId = trim($request->input('val_id', ''));

        if (empty($tranId)) {
            return response()->json(['status' => 'FAILED', 'message' => 'Missing tran_id'], 400);
        }

        $order = Order::where('order_number', $tranId)->first();
        if (!$order) {
            return response()->json(['status' => 'FAILED', 'message' => 'Order not found'], 404);
        }

        if ($order->payment_status === 'paid') {
            return response()->json(['status' => 'ALREADY_PAID'], 200);
        }

        $sslc = app(SslCommerzNotification::class);
        $isValid = $sslc->orderValidate($tranId, $order->total_amount, $order->currency ?: 'BDT', $request->all());

        if ($isValid) {
            $cardType = $request->input('card_type', 'SSLCommerz');
            $bankTranId = $request->input('bank_tran_id');

            $order->update([
                'payment_status'  => 'paid',
                'transaction_id'  => $valId ?: $tranId,
                'bank_tran_id'    => $bankTranId,
                'card_type'       => $cardType,
                'payment_details' => json_encode($request->all()),
                'admin_notes'     => ($order->admin_notes ? $order->admin_notes . "\n" : '') . "Online payment verified via SSLCommerz IPN [Card: {$cardType}]",
            ]);

            Log::info("SSLCommerz IPN verified Order #{$order->order_number}");
            return response()->json(['status' => 'SUCCESS'], 200);
        }

        Log::warning("SSLCommerz IPN validation failed for Order #{$order->order_number}: " . $sslc->error);
        return response()->json(['status' => 'FAILED', 'error' => $sslc->error], 400);
    }
}
