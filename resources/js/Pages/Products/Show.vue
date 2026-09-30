<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    product_id: props.product.id,
    product_variant_id: props.product.variants[0]?.id ?? '',
    quantity: 1,
});

const addToCart = () => {
    form.post('/cart');
};
</script>

<template>
    <Head :title="product.name" />

    <div class="min-h-screen bg-gray-100">
        <Navbar />

        <main class="mx-auto max-w-6xl px-6 py-12">
            <Link
                href="/products"
                class="text-sm text-gray-500 hover:text-gray-900"
            >
                ← Back to Products
            </Link>

            <div class="mt-8 grid gap-10 rounded-xl bg-white p-8 shadow md:grid-cols-2">
                <div>
                    <div class="aspect-square overflow-hidden rounded-xl bg-gray-100">
                        <img
                            v-if="product.images?.[0]?.image"
                            :src="`/storage/${product.images[0].image}`"
                            :alt="product.name"
                            class="h-full w-full object-cover"
                        />

                        <div
                            v-else
                            class="flex h-full items-center justify-center text-gray-400"
                        >
                            No image
                        </div>
                    </div>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        {{ product.category?.name }}
                    </p>

                    <h1 class="mt-2 text-4xl font-bold text-gray-900">
                        {{ product.name }}
                    </h1>

                    <p class="mt-6 text-2xl font-semibold text-gray-900">
                        ${{ Number(product.variants[0]?.price ?? product.price).toFixed(2) }}
                    </p>

                    <p class="mt-6 leading-7 text-gray-600">
                        {{ product.description }}
                    </p>

                    <form
                        class="mt-8 space-y-5"
                        @submit.prevent="addToCart"
                    >
                        <div>
                            <label
                                for="variant"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Variant
                            </label>

                            <select
                                id="variant"
                                v-model="form.product_variant_id"
                                class="mt-2 w-full rounded-lg border-gray-300"
                            >
                                <option
                                    v-for="variant in product.variants"
                                    :key="variant.id"
                                    :value="variant.id"
                                >
                                    {{ variant.sku }}
                                    <span v-if="variant.size">
                                        - {{ variant.size }}
                                    </span>
                                    <span v-if="variant.color">
                                        - {{ variant.color }}
                                    </span>
                                    - ${{ Number(variant.price).toFixed(2) }}
                                    - {{ variant.stock }} in stock
                                </option>
                            </select>

                            <p
                                v-if="form.errors.product_variant_id"
                                class="mt-2 text-sm text-red-600"
                            >
                                {{ form.errors.product_variant_id }}
                            </p>
                        </div>

                        <div>
                            <label
                                for="quantity"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Quantity
                            </label>

                            <input
                                id="quantity"
                                v-model.number="form.quantity"
                                type="number"
                                min="1"
                                :max="product.variants.find(
                                    (variant) => variant.id === form.product_variant_id
                                )?.stock ?? 1"
                                class="mt-2 w-24 rounded-lg border-gray-300"
                            />

                            <p
                                v-if="form.errors.quantity"
                                class="mt-2 text-sm text-red-600"
                            >
                                {{ form.errors.quantity }}
                            </p>
                        </div>

                        <button
                            type="submit"
                            :disabled="form.processing || !form.product_variant_id"
                            class="w-full rounded-lg bg-gray-900 px-5 py-3 font-medium text-white hover:bg-gray-800 disabled:cursor-not-allowed disabled:bg-gray-300"
                        >
                            {{ form.processing ? 'Adding...' : 'Add to Cart' }}
                        </button>
                    </form>
                </div>
            </div>
        </main>
    </div>
</template>
