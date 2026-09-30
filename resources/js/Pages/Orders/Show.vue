<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';

defineProps({
    order: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Head :title="`Order #${order.id}`" />

```
<div class="min-h-screen bg-gray-100">
    <Navbar />

    <main class="mx-auto max-w-4xl px-6 py-12">
        <div class="rounded-xl bg-white p-8 shadow">
            <div class="text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-green-100">
                    <span class="text-2xl text-green-600">✓</span>
                </div>

                <h1 class="mt-4 text-3xl font-bold text-gray-900">
                    Order Confirmed
                </h1>

                <p class="mt-2 text-gray-600">
                    Thank you for your order.
                </p>

                <p class="mt-1 text-sm text-gray-500">
                    Order #{{ order.id }}
                </p>
            </div>

            <div class="mt-8 border-t border-gray-200 pt-8">
                <h2 class="text-xl font-semibold text-gray-900">
                    Order Items
                </h2>

                <div class="mt-4 divide-y divide-gray-200">
                    <div
                        v-for="item in order.items"
                        :key="item.id"
                        class="flex items-center justify-between py-4"
                    >
                        <div>
                            <p class="font-medium text-gray-900">
                                {{ item.product_name }}
                            </p>

                            <p class="mt-1 text-sm text-gray-500">
                                Quantity: {{ item.quantity }}
                            </p>

                            <p
                                v-if="item.variant_details"
                                class="text-sm text-gray-500"
                            >
                                <span v-if="item.variant_details.size">
                                    Size: {{ item.variant_details.size }}
                                </span>

                                <span
                                    v-if="
                                        item.variant_details.size &&
                                        item.variant_details.color
                                    "
                                >
                                    ·
                                </span>

                                <span v-if="item.variant_details.color">
                                    Color: {{ item.variant_details.color }}
                                </span>
                            </p>
                        </div>

                        <p class="font-medium text-gray-900">
                            ${{ Number(item.total).toFixed(2) }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-8 border-t border-gray-200 pt-6">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal</span>
                    <span>${{ Number(order.subtotal).toFixed(2) }}</span>
                </div>

                <div class="mt-2 flex justify-between text-gray-600">
                    <span>Shipping</span>
                    <span>${{ Number(order.shipping_cost).toFixed(2) }}</span>
                </div>

                <div class="mt-4 flex justify-between text-lg font-bold text-gray-900">
                    <span>Total</span>
                    <span>${{ Number(order.total).toFixed(2) }}</span>
                </div>
            </div>

            <div class="mt-8 border-t border-gray-200 pt-6">
                <h2 class="text-xl font-semibold text-gray-900">
                    Shipping Address
                </h2>

                <div class="mt-3 text-sm leading-6 text-gray-600">
                    <p>
                        {{ order.shipping_address.first_name }}
                        {{ order.shipping_address.last_name }}
                    </p>

                    <p>{{ order.shipping_address.address_line }}</p>

                    <p>
                        {{ order.shipping_address.city }}
                        {{ order.shipping_address.postal_code }}
                    </p>

                    <p>{{ order.shipping_address.country }}</p>

                    <p>{{ order.shipping_address.phone }}</p>
                </div>
            </div>

            <div class="mt-8 border-t border-gray-200 pt-6">
                <p class="text-sm text-gray-600">
                    Payment:
                    <span class="font-medium text-gray-900">
                        {{ order.payment?.payment_method === 'cash_on_delivery'
                            ? 'Cash on Delivery'
                            : 'Card' }}
                    </span>
                </p>

                <p class="mt-2 text-sm text-gray-600">
                    Order status:
                    <span class="font-medium capitalize text-gray-900">
                        {{ order.status }}
                    </span>
                </p>
            </div>

            <div class="mt-8 flex gap-4">
                <Link
                    href="/products"
                    class="flex-1 rounded-lg bg-gray-900 px-5 py-3 text-center font-medium text-white hover:bg-gray-800"
                >
                    Continue Shopping
                </Link>

                <Link
                    href="/account"
                    class="flex-1 rounded-lg border border-gray-300 px-5 py-3 text-center font-medium text-gray-700 hover:bg-gray-50"
                >
                    My Account
                </Link>
            </div>
        </div>
    </main>
</div>
```

</template>
