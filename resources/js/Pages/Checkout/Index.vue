<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';

const props = defineProps({
    items: {
        type: Array,
        required: true,
    },
    addresses: {
        type: Array,
        required: true,
    },
    subtotal: {
        type: [Number, String],
        required: true,
    },
});

const form = useForm({
    address_id: props.addresses.find((address) => address.is_default)?.id
        ?? props.addresses[0]?.id
        ?? '',
    payment_method: 'cash_on_delivery',
});

const submit = () => {
    form.post('/checkout');
};
</script>

<template>
    <Head title="Checkout" />

<div class="min-h-screen bg-gray-100">
    <Navbar />

    <main class="mx-auto max-w-6xl px-6 py-12">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                Checkout
            </h1>

            <Link
                href="/cart"
                class="mt-2 inline-block text-sm text-gray-600 hover:text-gray-900"
            >
                ← Back to Cart
            </Link>
        </div>

        <div
            v-if="items.length === 0"
            class="rounded-xl bg-white p-8 text-center shadow"
        >
            <h2 class="text-xl font-semibold text-gray-900">
                Your cart is empty
            </h2>

            <Link
                href="/products"
                class="mt-6 inline-block rounded-lg bg-gray-900 px-5 py-3 font-medium text-white hover:bg-gray-800"
            >
                Continue Shopping
            </Link>
        </div>

        <div v-else class="grid gap-8 lg:grid-cols-3">
            <div class="space-y-8 lg:col-span-2">
                <!-- Shipping Address -->
                <section class="rounded-xl bg-white p-6 shadow">
                    <h2 class="text-xl font-semibold text-gray-900">
                        Shipping Address
                    </h2>

                    <div
                        v-if="addresses.length === 0"
                        class="mt-4 rounded-lg bg-yellow-50 p-4 text-sm text-yellow-800"
                    >
                        <p>
                            You need to add an address before placing an
                            order.
                        </p>

                        <Link
                            href="/account/addresses"
                            class="mt-3 inline-block font-medium underline"
                        >
                            Add an address
                        </Link>
                    </div>

                    <div v-else class="mt-4 space-y-3">
                        <label
                            v-for="address in addresses"
                            :key="address.id"
                            class="flex cursor-pointer gap-4 rounded-lg border p-4"
                            :class="
                                form.address_id === address.id
                                    ? 'border-gray-900 bg-gray-50'
                                    : 'border-gray-200'
                            "
                        >
                            <input
                                v-model="form.address_id"
                                type="radio"
                                :value="address.id"
                                class="mt-1"
                            />

                            <div>
                                <p class="font-medium text-gray-900">
                                    {{ address.first_name }}
                                    {{ address.last_name }}
                                </p>

                                <p class="mt-1 text-sm text-gray-600">
                                    {{ address.address_line }}
                                </p>

                                <p class="text-sm text-gray-600">
                                    {{ address.city }}
                                    {{ address.postal_code }}
                                </p>

                                <p class="text-sm text-gray-600">
                                    {{ address.country }}
                                </p>

                                <p class="mt-1 text-sm text-gray-600">
                                    {{ address.phone }}
                                </p>

                                <span
                                    v-if="address.is_default"
                                    class="mt-2 inline-block rounded-full bg-gray-900 px-2 py-1 text-xs font-medium text-white"
                                >
                                    Default
                                </span>
                            </div>
                        </label>
                    </div>

                    <p
                        v-if="form.errors.address_id"
                        class="mt-3 text-sm text-red-600"
                    >
                        {{ form.errors.address_id }}
                    </p>
                </section>

                <!-- Payment -->
                <section class="rounded-xl bg-white p-6 shadow">
                    <h2 class="text-xl font-semibold text-gray-900">
                        Payment Method
                    </h2>

                    <div class="mt-4 space-y-3">
                        <label
                            class="flex cursor-pointer items-start gap-4 rounded-lg border p-4"
                            :class="
                                form.payment_method === 'cash_on_delivery'
                                    ? 'border-gray-900 bg-gray-50'
                                    : 'border-gray-200'
                            "
                        >
                            <input
                                v-model="form.payment_method"
                                type="radio"
                                value="cash_on_delivery"
                                class="mt-1"
                            />

                            <div>
                                <p class="font-medium text-gray-900">
                                    Cash on Delivery
                                </p>

                                <p class="mt-1 text-sm text-gray-600">
                                    Pay when your order is delivered.
                                </p>
                            </div>
                        </label>

                        <label
                            class="flex cursor-pointer items-start gap-4 rounded-lg border p-4"
                            :class="
                                form.payment_method === 'card'
                                    ? 'border-gray-900 bg-gray-50'
                                    : 'border-gray-200'
                            "
                        >
                            <input
                                v-model="form.payment_method"
                                type="radio"
                                value="card"
                                class="mt-1"
                            />

                            <div>
                                <p class="font-medium text-gray-900">
                                    Card
                                </p>

                                <p class="mt-1 text-sm text-gray-600">
                                    Card payment will be simulated for now.
                                </p>
                            </div>
                        </label>
                    </div>

                    <p
                        v-if="form.errors.payment_method"
                        class="mt-3 text-sm text-red-600"
                    >
                        {{ form.errors.payment_method }}
                    </p>
                </section>
            </div>

            <!-- Order Summary -->
            <section class="h-fit rounded-xl bg-white p-6 shadow">
                <h2 class="text-xl font-semibold text-gray-900">
                    Order Summary
                </h2>

                <div class="mt-6 divide-y divide-gray-200">
                    <div
                        v-for="item in items"
                        :key="item.id"
                        class="flex justify-between gap-4 py-4"
                    >
                        <div>
                            <p class="font-medium text-gray-900">
                                {{ item.product.name }}
                                × {{ item.quantity }}
                            </p>

                            <p class="mt-1 text-sm text-gray-500">
                                <span v-if="item.variant.size">
                                    Size: {{ item.variant.size }}
                                </span>

                                <span
                                    v-if="
                                        item.variant.size &&
                                        item.variant.color
                                    "
                                >
                                    ·
                                </span>

                                <span v-if="item.variant.color">
                                    Color: {{ item.variant.color }}
                                </span>
                            </p>
                        </div>

                        <p class="font-medium text-gray-900">
                            ${{ Number(item.line_total).toFixed(2) }}
                        </p>
                    </div>
                </div>

                <div class="mt-6 border-t border-gray-200 pt-6">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span>
                            ${{ Number(subtotal).toFixed(2) }}
                        </span>
                    </div>

                    <div class="mt-2 flex justify-between text-gray-600">
                        <span>Shipping</span>
                        <span>$0.00</span>
                    </div>

                    <div
                        class="mt-4 flex justify-between text-lg font-bold text-gray-900"
                    >
                        <span>Total</span>
                        <span>
                            ${{ Number(subtotal).toFixed(2) }}
                        </span>
                    </div>
                </div>

                <p
                    v-if="form.errors.cart"
                    class="mt-4 text-sm text-red-600"
                >
                    {{ form.errors.cart }}
                </p>

                <button
                    type="button"
                    @click="submit"
                    :disabled="
                        form.processing ||
                        addresses.length === 0 ||
                        !form.address_id
                    "
                    class="mt-6 w-full rounded-lg bg-gray-900 px-5 py-3 font-medium text-white hover:bg-gray-800 disabled:cursor-not-allowed disabled:bg-gray-300"
                >
                    {{
                        form.processing
                            ? 'Placing Order...'
                            : 'Place Order'
                    }}
                </button>
            </section>
        </div>
    </main>
</div>

</template>
