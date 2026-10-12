<?php

namespace App\Http\Controllers;

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
    public function create(): View|RedirectResponse
    {
        $cart = session('cart', []);

        if ($cart === []) {
            return redirect()->route('cart')->withErrors(['cart' => 'Keranjang Anda masih kosong.']);
        }

        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');
        $checkoutItems = [];
        $total = 0;

        foreach ($cart as $productId => $item) {
            $product = $products->get($productId);

            if (! $product) {
                continue;
            }

            $subtotal = (float) $product->price * $item['quantity'];
            $total += $subtotal;
            $checkoutItems[] = compact('product', 'item', 'subtotal');
        }

        return view('checkout', compact('checkoutItems', 'total'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'shipping_address' => ['required', 'string', 'max:2000'],
            'payment_method' => ['required', Rule::in(['bank_transfer', 'cash_on_delivery'])],
        ]);

        $cart = session('cart', []);

        if ($cart === []) {
            return redirect()->route('cart')->withErrors(['cart' => 'Keranjang Anda masih kosong.']);
        }

        $order = DB::transaction(function () use ($cart, $validated, $request): Order {
            $orderLines = [];
            $totalCents = 0;

            foreach ($cart as $productId => $item) {
                $product = Product::whereKey($productId)->lockForUpdate()->first();

                if (! $product || $product->stock < $item['quantity']) {
                    throw ValidationException::withMessages([
                        'cart' => 'Stok produk berubah. Periksa kembali keranjang Anda.',
                    ]);
                }

                $unitPriceCents = (int) round((float) $product->price * 100);
                $lineTotalCents = $unitPriceCents * $item['quantity'];
                $totalCents += $lineTotalCents;
                $orderLines[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'unit_price' => number_format($unitPriceCents / 100, 2, '.', ''),
                    'subtotal' => number_format($lineTotalCents / 100, 2, '.', ''),
                ];

                $product->decrement('stock', $item['quantity']);
            }

            $order = Order::create([
                'user_id' => $request->user()?->id,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'shipping_address' => $validated['shipping_address'],
                'total_amount' => number_format($totalCents / 100, 2, '.', ''),
                'status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'pending',
            ]);

            foreach ($orderLines as $line) {
                $order->items()->create([
                    'product_id' => $line['product']->id,
                    'product_name' => $line['product']->name,
                    'quantity' => $line['quantity'],
                    'unit_price' => $line['unit_price'],
                    'subtotal' => $line['subtotal'],
                ]);
            }

            return $order;
        });

        $request->session()->forget('cart');

        return redirect()
            ->route('checkout')
            ->with('success', 'Pesanan berhasil dibuat. Nomor pesanan: '
                .$order->id);
    }
}
