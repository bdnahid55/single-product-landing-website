<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Requests\StoreOrderRequest;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Store a newly created order in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $validated = $request->validated();

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
            return back()->withInput()
                ->withErrors(['product' => 'অনুগ্রহ করে অর্ডার করার জন্য অন্তত একটি পণ্য নির্বাচন করুন।'])
                ->with('error', 'অনুগ্রহ করে অন্তত একটি পণ্য নির্বাচন করুন।');
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
        $settings = Setting::getAllSettings();

        return view('order-success', compact('order', 'settings'));
    }

    /**
     * Customer Order Tracking Page.
     */
    public function track(Request $request)
    {
        $settings = Setting::getAllSettings();
        $order = null;
        $searched = false;
        $errorMessage = null;

        $orderNumberInput = trim($request->input('order_number', ''));
        $phoneInput = trim($request->input('customer_phone', $request->input('phone', '')));

        if ($request->isMethod('post') || ($request->filled('order_number') && ($request->filled('customer_phone') || $request->filled('phone')))) {
            $searched = true;

            $request->merge([
                'order_number'   => $orderNumberInput,
                'customer_phone' => $phoneInput,
            ]);

            $request->validate([
                'order_number'   => 'required|string|max:50',
                'customer_phone' => 'required|string|max:30',
            ], [
                'order_number.required'   => 'অনুগ্রহ করে আপনার অর্ডার নম্বর (Order ID) প্রদান করুন।',
                'customer_phone.required' => 'অনুগ্রহ করে অর্ডার করার সময় ব্যবহৃত মোবাইল নম্বর প্রদান করুন।',
            ]);

            // Normalize order number (strip '#' and trim)
            $cleanOrderNumber = strtoupper(trim(str_replace('#', '', $orderNumberInput)));

            // Normalize phone: extract digits and last 10 digits
            $cleanPhone = preg_replace('/\D/', '', $phoneInput);
            $last10 = strlen($cleanPhone) >= 10 ? substr($cleanPhone, -10) : $cleanPhone;

            $order = Order::with('items.product')
                ->where(function ($q) use ($cleanOrderNumber) {
                    $q->where('order_number', $cleanOrderNumber)
                      ->orWhere('order_number', 'LIKE', '%' . $cleanOrderNumber . '%');
                })
                ->where(function ($q) use ($cleanPhone, $last10) {
                    $q->where('customer_phone', $cleanPhone)
                      ->orWhere('customer_phone', 'LIKE', '%' . $last10)
                      ->orWhere('customer_phone', 'LIKE', '%' . $cleanPhone . '%');
                })
                ->first();

            if (!$order) {
                $errorMessage = 'দুঃখিত! আপনার প্রদত্ত অর্ডার নম্বর বা ফোন নম্বরের সাথে কোনো অর্ডার পাওয়া যায়নি। অনুগ্রহ করে সঠিক তথ্য দিয়ে পুনরায় চেষ্টা করুন।';
            }
        }

        return view('order-track', compact('settings', 'order', 'searched', 'errorMessage', 'orderNumberInput', 'phoneInput'));
    }
}
