<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $cart = session('cart', []);
        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');
        $cartItems = [];
        $subtotal = 0;

        foreach ($cart as $productId => $item) {
            $product = $products->get($productId);

            if (! $product) {
                continue;
            }

            $itemSubtotal = (float) $product->price * $item['quantity'];
            $subtotal += $itemSubtotal;
            $cartItems[] = [
                'product' => $product,
                'quantity' => $item['quantity'],
                'subtotal' => $itemSubtotal,
            ];
        }

        return view('cart', compact('cartItems', 'subtotal'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);
        $product = Product::findOrFail($validated['product_id']);
        $cart = session('cart', []);
        $quantity = ($cart[$product->id]['quantity'] ?? 0) + $validated['quantity'];

        if ($quantity > $product->stock) {
            return back()->withErrors(['quantity' => 'Jumlah melebihi stok yang tersedia.']);
        }

        $cart[$product->id] = ['quantity' => $quantity];
        session(['cart' => $cart]);

        return redirect()->route('cart')->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        if ($validated['quantity'] > $product->stock) {
            return back()->withErrors(['quantity' => 'Jumlah melebihi stok yang tersedia.']);
        }

        $cart = session('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] = $validated['quantity'];
            session(['cart' => $cart]);
        }

        return redirect()->route('cart')->with('success', 'Jumlah produk diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $cart = session('cart', []);
        unset($cart[$product->id]);
        session(['cart' => $cart]);

        return redirect()->route('cart')->with('success', 'Produk dihapus dari keranjang.');
    }
}
