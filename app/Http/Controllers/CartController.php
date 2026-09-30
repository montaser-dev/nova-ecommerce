<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartItemRequest;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function index(): Response
    {
        $cartItems = CartItem::query()
            ->where('user_id', auth()->id())
            ->with([
                'product.primaryImage',
                'productVariant',
            ])
            ->get();

        $items = $cartItems->map(function (CartItem $item) {
            $unitPrice = $item->productVariant->price;
            $lineTotal = $unitPrice * $item->quantity;

            return [
                'id' => $item->id,
                'product' => [
                    'id' => $item->product->id,
                    'name' => $item->product->name,
                    'slug' => $item->product->slug,
                    'image' => $item->product->primaryImage?->image,
                ],
                'variant' => [
                    'id' => $item->productVariant->id,
                    'sku' => $item->productVariant->sku,
                    'size' => $item->productVariant->size,
                    'color' => $item->productVariant->color,
                    'price' => $unitPrice,
                    'stock' => $item->productVariant->stock,
                ],
                'quantity' => $item->quantity,
                'line_total' => $lineTotal,
            ];
        });

        return Inertia::render('Cart/Index', [
            'items' => $items,
            'subtotal' => $items->sum('line_total'),
        ]);
    }

    public function store(StoreCartItemRequest $request): RedirectResponse
    {
        $variant = ProductVariant::query()
            ->with('product')
            ->findOrFail($request->integer('product_variant_id'));

        $cartItem = CartItem::query()
            ->where('user_id', auth()->id())
            ->where('product_variant_id', $variant->id)
            ->first();

        $newQuantity = ($cartItem?->quantity ?? 0) + $request->integer('quantity');

        if ($newQuantity > $variant->stock) {
            return back()->withErrors([
                'quantity' => 'The requested quantity exceeds available stock.',
            ]);
        }

        if ($cartItem) {
            $cartItem->update([
                'quantity' => $newQuantity,
            ]);
        } else {
            CartItem::create([
                'user_id' => auth()->id(),
                'product_id' => $variant->product_id,
                'product_variant_id' => $variant->id,
                'quantity' => $request->integer('quantity'),
            ]);
        }

        return back()->with('success', 'Product added to cart.');
    }

    public function update(Request $request, CartItem $cartItem): RedirectResponse
    {
        abort_unless($cartItem->user_id === auth()->id(), 403);

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cartItem->load('productVariant');

        if ($validated['quantity'] > $cartItem->productVariant->stock) {
            return back()->withErrors([
                'quantity' => 'The requested quantity exceeds available stock.',
            ]);
        }

        $cartItem->update([
            'quantity' => $validated['quantity'],
        ]);

        return back()->with('success', 'Cart updated.');
    }

    public function destroy(CartItem $cartItem): RedirectResponse
    {
        abort_unless($cartItem->user_id === auth()->id(), 403);

        $cartItem->delete();

        return back()->with('success', 'Product removed from cart.');
    }
}
