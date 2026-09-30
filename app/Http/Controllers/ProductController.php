<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    /**
     * Display a paginated list of active products for the storefront.
     */
    public function index(Request $request): Response
    {
        $products = Product::query()
            ->where('status', 'active')
            ->with(['category', 'primaryImage', 'variants'])
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('Products/Index', [
            'products' => $products,
        ]);
    }

    /**
     * Display a single active product with its full detail data.
     */
    public function show(Product $product): Response
    {
        abort_unless($product->status === 'active', 404);

        $product->load(['category', 'images', 'variants']);

        return Inertia::render('Products/Show', [
            'product' => $product,
        ]);
    }
}
