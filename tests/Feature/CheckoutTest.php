<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_checkout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/checkout')
            ->assertOk()
            ->assertInertia(fn ($page) =>
                $page->component('Checkout/Index')
                    ->has('items')
                    ->has('addresses')
                    ->where('subtotal', 0)
            );
    }

    public function test_unauthenticated_user_cannot_access_checkout(): void
    {
        $this->get('/checkout')
            ->assertRedirect();
    }

    public function test_user_cannot_checkout_with_another_users_address(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $this->actingAs($user)
            ->post('/checkout', [
                'address_id' => $address->id,
                'payment_method' => 'cash_on_delivery',
            ])
            ->assertSessionHasErrors('address_id');
    }

    public function test_user_cannot_checkout_with_empty_cart(): void
    {
        $user = User::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)
            ->post('/checkout', [
                'address_id' => $address->id,
                'payment_method' => 'cash_on_delivery',
            ])
            ->assertSessionHasErrors('cart');
    }

    public function test_checkout_creates_order_items_payment_clears_cart_and_decrements_stock(): void
    {
        $user = User::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $user->id,
        ]);

        $category = Category::factory()->create();

        $product = Product::factory()->create([
            'category_id' => $category->id,
            'status' => 'active',
            'price' => 19.99,
        ]);

        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'price' => 19.99,
            'stock' => 10,
        ]);

        CartItem::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($user)
            ->post('/checkout', [
                'address_id' => $address->id,
                'payment_method' => 'cash_on_delivery',
            ]);

        $order = $user->orders()->first();

        $response->assertRedirect(route('orders.show', $order));

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'user_id' => $user->id,
            'status' => 'pending',
            'subtotal' => '39.98',
            'shipping_cost' => '0.00',
            'total' => '39.98',
        ]);

        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_variant_id' => $variant->id,
            'product_name' => $product->name,
            'quantity' => 2,
            'unit_price' => '19.99',
            'total' => '39.98',
        ]);

        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'payment_method' => 'cash_on_delivery',
            'amount' => '39.98',
            'status' => 'pending',
        ]);

        $this->assertDatabaseMissing('cart_items', [
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('product_variants', [
            'id' => $variant->id,
            'stock' => 8,
        ]);
    }

    public function test_checkout_fails_when_stock_is_insufficient(): void
    {
        $user = User::factory()->create();

        $address = Address::factory()->create([
            'user_id' => $user->id,
        ]);

        $category = Category::factory()->create();

        $product = Product::factory()->create([
            'category_id' => $category->id,
            'status' => 'active',
        ]);

        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'price' => 20,
            'stock' => 1,
        ]);

        CartItem::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $this->actingAs($user)
            ->post('/checkout', [
                'address_id' => $address->id,
                'payment_method' => 'cash_on_delivery',
            ])
            ->assertStatus(422);

        $this->assertDatabaseMissing('orders', [
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('product_variants', [
            'id' => $variant->id,
            'stock' => 1,
        ]);

        $this->assertDatabaseHas('cart_items', [
            'user_id' => $user->id,
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ]);
    }

    public function test_user_can_view_their_order(): void
    {
        $user = User::factory()->create();

        $order = $user->orders()->create([
            'status' => 'pending',
            'subtotal' => 39.98,
            'shipping_cost' => 0,
            'total' => 39.98,
            'shipping_address' => [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'phone' => '123456789',
                'address_line' => '123 Main Street',
                'city' => 'Agadir',
                'postal_code' => '90000',
                'country' => 'Morocco',
            ],
            'billing_address' => [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'phone' => '123456789',
                'address_line' => '123 Main Street',
                'city' => 'Agadir',
                'postal_code' => '90000',
                'country' => 'Morocco',
            ],
        ]);

        $this->actingAs($user)
            ->get(route('orders.show', $order))
            ->assertOk()
            ->assertInertia(fn ($page) =>
                $page->component('Orders/Show')
                    ->where('order.id', $order->id)
                    ->where('order.status', 'pending')
            );
    }

    public function test_user_cannot_view_another_users_order(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $order = $otherUser->orders()->create([
            'status' => 'pending',
            'subtotal' => 20,
            'shipping_cost' => 0,
            'total' => 20,
            'shipping_address' => [],
            'billing_address' => [],
        ]);

        $this->actingAs($user)
            ->get(route('orders.show', $order))
            ->assertForbidden();
    }
}
