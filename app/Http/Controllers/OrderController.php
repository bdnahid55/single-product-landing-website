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
            'customer_name'       => 'required|string|max:255',
            'customer_phone'      => 'required|string|max:20',
            'customer_address'    => 'required|string|max:1000',
            'delivery_area'       => 'required|in:inside_dhaka,outside_dhaka',
            'customer_notes'      => 'nullable|string|max:1000',
            'product_id'          => 'nullable|exists:products,id',
            'quantity'            => 'nullable|integer|min:1|max:50',
            'selected_products'   => 'nullable|array',
            'selected_products.*' => 'exists:products,id',
            'quantities'          => 'nullable|array',
            'items'               => 'nullable|array',
        ]);

        $orderItemsData = [];
        $subtotal = 0;

        // Case 1: Multi-Product Mode via selected_products[] and quantities[]
        if ($request->has('selected_products') && is_array($request->input('selected_products'))) {
            $selectedIds = $request->input('selected_products');
            $quantities = $request->input('quantities', []);

            foreach ($selectedIds as $pId) {
                $qty = isset($quantities[$pId]) ? max(1, min(50, intval($quantities[$pId]))) : 1;
                $product = Product::find($pId);
                if ($product && $product->is_active) {
                    $itemSubtotal = $product->sale_price * $qty;
                    $subtotal += $itemSubtotal;
                    $orderItemsData[] = [
                        'product'      => $product,
                        'product_id'   => $product->id,
                        'product_name' => $product->name,
                        'weight'       => $product->weight,
                        'unit_price'   => $product->sale_price,
                        'quantity'     => $qty,
                        'subtotal'     => $itemSubtotal,
                    ];
                }
            }
        }
        // Case 2: Multi-Product Mode via items[] array
        elseif ($request->has('items') && is_array($request->input('items'))) {
            foreach ($request->input('items') as $item) {
                if (!empty($item['product_id'])) {
                    $qty = isset($item['quantity']) ? max(1, min(50, intval($item['quantity']))) : 1;
                    $product = Product::find($item['product_id']);
                    if ($product && $product->is_active) {
                        $itemSubtotal = $product->sale_price * $qty;
                        $subtotal += $itemSubtotal;
                        $orderItemsData[] = [
                            'product'      => $product,
                            'product_id'   => $product->id,
                            'product_name' => $product->name,
                            'weight'       => $product->weight,
                            'unit_price'   => $product->sale_price,
                            'quantity'     => $qty,
                            'subtotal'     => $itemSubtotal,
                        ];
                    }
                }
            }
        }
        // Case 3: Single Product Mode (product_id and quantity)
        elseif (!empty($validated['product_id'])) {
            $qty = !empty($validated['quantity']) ? intval($validated['quantity']) : 1;
            $product = Product::findOrFail($validated['product_id']);
            $itemSubtotal = $product->sale_price * $qty;
            $subtotal += $itemSubtotal;
            $orderItemsData[] = [
                'product'      => $product,
                'product_id'   => $product->id,
                'product_name' => $product->name,
                'weight'       => $product->weight,
                'unit_price'   => $product->sale_price,
                'quantity'     => $qty,
                'subtotal'     => $itemSubtotal,
            ];
        }

        if (empty($orderItemsData)) {
            return back()->withInput()->with('error', 'অনুগ্রহ করে অন্তত একটি পণ্য নির্বাচন করুন।');
        }

        $deliveryCharge = $validated['delivery_area'] === 'inside_dhaka'
            ? floatval(Setting::get('delivery_inside_dhaka', 80))
            : floatval(Setting::get('delivery_outside_dhaka', 130));

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

            foreach ($orderItemsData as $item) {
                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $item['product_id'],
                    'product_name' => $item['product_name'],
                    'weight'       => $item['weight'],
                    'unit_price'   => $item['unit_price'],
                    'quantity'     => $item['quantity'],
                    'subtotal'     => $item['subtotal'],
                ]);

                // Adjust stock safely
                if ($item['product']->stock >= $item['quantity']) {
                    $item['product']->decrement('stock', $item['quantity']);
                }
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
