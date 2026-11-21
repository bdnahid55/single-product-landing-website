<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name'    => 'required|string|max:255',
            'customer_phone'   => 'required|string|max:20',
            'customer_address' => 'required|string|max:1000',
            'product_id'       => 'required|exists:products,id',
            'quantity'         => 'required|integer|min:1|max:50',
            'delivery_area'    => 'required|in:inside_dhaka,outside_dhaka',
            'customer_notes'   => 'nullable|string|max:1000',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        $deliveryCharge = $validated['delivery_area'] === 'inside_dhaka'
            ? floatval(Setting::get('delivery_inside_dhaka', 80))
            : floatval(Setting::get('delivery_outside_dhaka', 130));

        $subtotal = $product->sale_price * $validated['quantity'];
        $totalAmount = $subtotal + $deliveryCharge;

        DB::beginTransaction();
        try {
            $order = Order::create([
                'order_number'    => Order::generateOrderNumber(),
                'customer_name'   => $validated['customer_name'],
                'customer_phone'  => $validated['customer_phone'],
                'customer_address'=> $validated['customer_address'],
                'delivery_area'   => $validated['delivery_area'],
                'delivery_charge' => $deliveryCharge,
                'subtotal'        => $subtotal,
                'total_amount'    => $totalAmount,
                'payment_method'  => 'cod',
                'payment_status'  => 'unpaid',
                'order_status'    => 'pending',
                'customer_notes'  => $validated['customer_notes'] ?? null,
            ]);

            OrderItem::create([
                'order_id'     => $order->id,
                'product_id'   => $product->id,
                'product_name' => $product->name,
                'weight'       => $product->weight,
                'unit_price'   => $product->sale_price,
                'quantity'     => $validated['quantity'],
                'subtotal'     => $subtotal,
            ]);

            // Adjust stock safely
            if ($product->stock >= $validated['quantity']) {
                $product->decrement('stock', $validated['quantity']);
            }

            DB::commit();

            return redirect()->route('orders.success', $order->id)
                ->with('success', 'আপনার অর্ডারটি সফলভাবে সম্পন্ন হয়েছে!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'অর্ডার করতে সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }

    /**
     * Display order success confirmation page.
     */
    public function success($id)
    {
        $order = Order::with('items')->findOrFail($id);
        $settings = Setting::all()->pluck('value', 'key')->toArray();

        return view('order-success', compact('order', 'settings'));
    }
}
