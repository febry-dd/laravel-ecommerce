<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $search = $request->string('search')->trim()->toString();
            $query->where(function ($products) use ($search): void {
                $products->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->string('category')->toString());
        }

        $products = $query->latest()->paginate(12)->withQueryString();
        $categories = Product::query()
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('products.index', compact('products', 'categories'));
    }
}
