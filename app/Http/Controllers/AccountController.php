<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(Request $request): View
    {
        $orders = $request->user()->orders()->latest()->paginate(15);

        return view('account.orders.index', compact('orders'));
    }

    public function show(Request $request, Order $order): View
    {
        $order = $request->user()->orders()->with('items')->findOrFail($order->id);

        return view('account.orders.show', compact('order'));
    }
}
