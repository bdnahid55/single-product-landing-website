<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $totalOrders   = Order::count();
        $pendingOrders = Order::where('order_status', 'pending')->count();
        $totalRevenue  = Order::where('order_status', 'delivered')->sum('total_amount');
        $totalProducts = Product::where('is_active', true)->count();

        $recentOrders  = Order::latest()->take(8)->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'totalRevenue',
            'totalProducts',
            'recentOrders'
        ));
    }
}
