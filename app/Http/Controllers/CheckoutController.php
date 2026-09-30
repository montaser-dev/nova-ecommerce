<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Address;
use App\Models\CartItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class CheckoutController extends Controller
{
    public function index(): Response
    {
        $user = auth()->user();

        $cartItems = CartItem::query()
            ->where('user_id', $user->id)
            ->with(['product', 'productVariant'])
            ->get();

        if ($cartItems->isEmpty()) {
            return Inertia::render('Checkout/Index', [
                'items' => [],
                'addresses' => $user->addresses()->get(),
                'subtotal' => 0,
            ]);
        }

        $items = $cartItems->map(function (CartItem $item) {
            $unitPrice = $item->productVariant->price;

            return [
                'id' => $item->id,
                'product' => [
                    'name' => $item->product->name,
                ],
                'variant' => [
                    'size' => $item->productVariant->size,
                    'color' => $item->productVariant->color,
                    'price' => $unitPrice,
                ],
                'quantity' => $item->quantity,
                'line_total' => $unitPrice * $item->quantity,
            ];
        });

        return Inertia::render('Checkout/Index', [
            'items' => $items,
            'addresses' => $user->addresses()->get(),
            'subtotal' => $items->sum('line_total'),
        ]);
    }

public function store(CheckoutRequest $request): RedirectResponse
{
    $user = auth()->user();

    $address = Address::query()
        ->where('user_id', $user->id)
        ->findOrFail($request->integer('address_id'));

    $cartItems = CartItem::query()
        ->where('user_id', $user->id)
        ->with(['product', 'productVariant'])
        ->get();

    if ($cartItems->isEmpty()) {
        return back()->withErrors([
            'cart' => 'Your cart is empty.',
        ]);
    }

    $order = DB::transaction(function () use ($user, $address, $cartItems, $request) {
        $subtotal = 0;

        foreach ($cartItems as $cartItem) {
            $variant = $cartItem->productVariant;

            if (
                $cartItem->product->status !== 'active' ||
                $variant->stock < $cartItem->quantity
            ) {
                abort(422, 'One or more products are no longer available.');
            }

            $subtotal += $variant->price * $cartItem->quantity;
        }

        $shippingCost = 0;
        $total = $subtotal + $shippingCost;

        $addressSnapshot = [
            'first_name' => $address->first_name,
            'last_name' => $address->last_name,
            'phone' => $address->phone,
            'address_line' => $address->address_line,
            'city' => $address->city,
            'postal_code' => $address->postal_code,
            'country' => $address->country,
        ];

        $order = $user->orders()->create([
            'status' => 'pending',
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'total' => $total,
            'shipping_address' => $addressSnapshot,
            'billing_address' => $addressSnapshot,
        ]);

        foreach ($cartItems as $cartItem) {
            $variant = $cartItem->productVariant;
            $unitPrice = $variant->price;
            $quantity = $cartItem->quantity;

            $order->items()->create([
                'product_id' => $cartItem->product_id,
                'product_variant_id' => $variant->id,
                'product_name' => $cartItem->product->name,
                'variant_details' => [
                    'sku' => $variant->sku,
                    'size' => $variant->size,
                    'color' => $variant->color,
                ],
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total' => $unitPrice * $quantity,
            ]);

            $variant->decrement('stock', $quantity);
        }

        $order->payment()->create([
            'payment_method' => $request->payment_method,
            'transaction_id' => null,
            'amount' => $total,
            'status' => 'pending',
        ]);

        CartItem::query()
            ->where('user_id', $user->id)
            ->delete();

        return $order;
    });

    return redirect()->route('orders.show', $order);
}
}
