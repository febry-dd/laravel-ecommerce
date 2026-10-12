<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $orders = Order::latest()->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order): View
    {
        $order->load('items');

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'processing', 'shipped', 'completed', 'cancelled'])],
            'payment_status' => ['required', Rule::in(['pending', 'paid', 'failed', 'refunded'])],
        ]);
        DB::transaction(function () use ($order, $validated): void {
            $lockedOrder = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();

            if ($lockedOrder->status === 'cancelled' && $validated['status'] !== 'cancelled') {
                throw ValidationException::withMessages([
                    'status' => 'Pesanan yang dibatalkan tidak dapat diaktifkan kembali.',
                ]);
            }

            if (
                $validated['status'] === 'cancelled'
                && ! in_array($lockedOrder->status, ['cancelled', 'shipped', 'completed'], true)
            ) {
                $lockedOrder->load('items');

                foreach ($lockedOrder->items as $item) {
                    if ($item->product_id !== null) {
                        Product::whereKey($item->product_id)->lockForUpdate()->increment('stock', $item->quantity);
                    }
                }
            }

            $lockedOrder->update($validated);
        });

        return redirect()->route('admin.orders.show', $order)->with('success', 'Status pesanan diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
