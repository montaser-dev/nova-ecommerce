<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';

defineProps({
    items: {
        type: Array,
        required: true,
    },
    subtotal: {
        type: [Number, String],
        required: true,
    },
});

const updateForm = (item) => {
    router.patch(`/cart/${item.id}`, {
        quantity: item.quantity,
    });
};

const removeForm = useForm({});

const removeItem = (item) => {
    removeForm.delete(`/cart/${item.id}`);
};
</script>

<template>
    <Head title="Shopping Cart" />

    <div class="min-h-screen bg-gray-100">
        <Navbar />

        <main class="mx-auto max-w-6xl px-6 py-12">
            <h1 class="text-3xl font-bold text-gray-900">
                Shopping Cart
            </h1>

            <div v-if="items.length === 0" class="mt-8 rounded-xl bg-white p-10 text-center shadow">
                <p class="text-gray-600">
                    Your cart is empty.
                </p>

                <Link
                    href="/products"
                    class="mt-5 inline-block rounded-lg bg-gray-900 px-5 py-3 text-sm font-medium text-white hover:bg-gray-800"
                >
                    Continue Shopping
                </Link>
            </div>

            <div v-else class="mt-8 grid gap-8 lg:grid-cols-3">
                <section class="space-y-4 lg:col-span-2">
                    <div
                        v-for="item in items"
                        :key="item.id"
                        class="flex gap-5 rounded-xl bg-white p-5 shadow"
                    >
                        <div class="h-28 w-28 shrink-0 overflow-hidden rounded-lg bg-gray-100">
                            <img
                                v-if="item.product.image"
                                :src="`/storage/${item.product.image}`"
                                :alt="item.product.name"
                                class="h-full w-full object-cover"
                            />

                            <div
                                v-else
                                class="flex h-full items-center justify-center text-xs text-gray-400"
                            >
                                No image
                            </div>
                        </div>

                        <div class="flex flex-1 flex-col justify-between">
                            <div>
                                <Link
                                    :href="`/products/${item.product.slug}`"
                                    class="text-lg font-semibold text-gray-900 hover:underline"
                                >
                                    {{ item.product.name }}
                                </Link>

                                <p class="mt-1 text-sm text-gray-500">
                                    <span v-if="item.variant.size">
                                        Size: {{ item.variant.size }}
                                    </span>

                                    <span v-if="item.variant.size && item.variant.color">
                                        ·
                                    </span>

                                    <span v-if="item.variant.color">
                                        Color: {{ item.variant.color }}
                                    </span>
                                </p>
                            </div>

                            <div class="mt-4 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <input
                                        v-model.number="item.quantity"
                                        type="number"
                                        min="1"
                                        :max="item.variant.stock"
                                        class="w-20 rounded-lg border-gray-300 text-center"
                                        @change="updateForm(item)"
                                    />

                                    <button
                                        type="button"
                                        class="text-sm text-red-600 hover:text-red-700"
                                        @click="removeItem(item)"
                                    >
                                        Remove
                                    </button>
                                </div>

                                <p class="font-semibold text-gray-900">
                                    ${{ Number(item.line_total).toFixed(2) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <aside class="h-fit rounded-xl bg-white p-6 shadow">
                    <h2 class="text-xl font-semibold text-gray-900">
                        Order Summary
                    </h2>

                    <div class="mt-6 flex justify-between border-b pb-4">
                        <span class="text-gray-600">Subtotal</span>

                        <span class="font-semibold text-gray-900">
                            ${{ Number(subtotal).toFixed(2) }}
                        </span>
                    </div>

                    <div class="mt-4 flex justify-between">
                        <span class="font-semibold text-gray-900">Total</span>

                        <span class="text-xl font-bold text-gray-900">
                            ${{ Number(subtotal).toFixed(2) }}
                        </span>
                    </div>

<Link
    href="/checkout"
    class="mt-6 block w-full rounded-lg bg-gray-900 px-5 py-3 text-center font-medium text-white hover:bg-gray-800"
>
    Proceed to Checkout
</Link>
                </aside>
            </div>
        </main>
    </div>
</template>
