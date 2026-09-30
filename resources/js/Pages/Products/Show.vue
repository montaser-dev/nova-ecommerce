<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
});

const formatPrice = (value) => `$${Number(value).toFixed(2)}`;
</script>

<template>
    <Head :title="product.name" />

    <Navbar />

    <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
        <Link href="/products" class="mb-6 inline-block text-sm text-indigo-600 hover:underline">
            &larr; Back to shop
        </Link>

        <div class="grid grid-cols-1 gap-10 lg:grid-cols-2">
            <div>
                <div class="grid grid-cols-2 gap-3">
                    <div
                        v-for="image in product.images"
                        :key="image.id"
                        class="aspect-square overflow-hidden rounded-lg bg-gray-100"
                    >
                        <img
                            :src="`/storage/${image.image}`"
                            :alt="product.name"
                            class="h-full w-full object-cover"
                        />
                    </div>

                    <div
                        v-if="product.images.length === 0"
                        class="col-span-2 flex aspect-square items-center justify-center rounded-lg bg-gray-100 text-sm text-gray-400"
                    >
                        No images available
                    </div>
                </div>
            </div>

            <div>
                <p v-if="product.category" class="text-xs font-medium uppercase tracking-wide text-gray-400">
                    {{ product.category.name }}
                </p>

                <h1 class="mt-1 text-2xl font-semibold tracking-tight text-gray-900">
                    {{ product.name }}
                </h1>

                <div class="mt-4 flex items-baseline gap-3">
                    <span class="text-xl font-semibold text-gray-900">
                        {{ formatPrice(product.price) }}
                    </span>
                    <span
                        v-if="product.compare_price"
                        class="text-base text-gray-400 line-through"
                    >
                        {{ formatPrice(product.compare_price) }}
                    </span>
                </div>

                <p class="mt-6 whitespace-pre-line text-sm leading-relaxed text-gray-600">
                    {{ product.description }}
                </p>

                <div v-if="product.variants.length > 0" class="mt-8">
                    <h2 class="mb-3 text-sm font-semibold text-gray-900">Available options</h2>

                    <div class="overflow-hidden rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500">Size</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500">Color</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500">Price</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500">Stock</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr v-for="variant in product.variants" :key="variant.id">
                                    <td class="px-4 py-2 text-gray-700">{{ variant.size ?? '—' }}</td>
                                    <td class="px-4 py-2 text-gray-700">{{ variant.color ?? '—' }}</td>
                                    <td class="px-4 py-2 text-gray-700">{{ formatPrice(variant.price) }}</td>
                                    <td class="px-4 py-2">
                                        <span
                                            v-if="variant.stock > 0"
                                            class="text-gray-700"
                                        >
                                            {{ variant.stock }} in stock
                                        </span>
                                        <span v-else class="text-red-500">Out of stock</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
