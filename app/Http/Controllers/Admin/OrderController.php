<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        $query = Order::query()->latest();

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        $orders = $query->get();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified order details.
     */
    public function show(Order $order)
    {
        $order->load('items.product');
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the order fulfillment and payment status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        if (!auth()->user()->can('update-order-status')) {
            abort(403, 'আপনার এই অর্ডারের স্ট্যাটাস পরিবর্তন করার অনুমতি নেই (Unauthorized).');
        }

        $validated = $request->validate([
            'order_status'   => 'required|in:pending,processing,delivered,cancelled',
            'payment_status' => 'required|in:unpaid,paid',
            'admin_notes'    => 'nullable|string',
        ]);

        $order->update($validated);

        return back()->with('success', 'Order #' . $order->order_number . ' status updated successfully!');
    }

    /**
     * Print the invoice for the order.
     */
    public function invoice(Order $order)
    {
        $order->load('items');
        $settings = Setting::getAllSettings();
        return view('admin.orders.invoice', compact('order', 'settings'));
    }
}
