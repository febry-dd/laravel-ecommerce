<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $productCount = Product::count();
        $orderCount = Order::count();
        $pendingOrderCount = Order::where('status', 'pending')->count();
        $recentOrders = Order::latest()->limit(10)->get();

        return view('admin.dashboard', compact('productCount', 'orderCount', 'pendingOrderCount', 'recentOrders'));
    }
}
