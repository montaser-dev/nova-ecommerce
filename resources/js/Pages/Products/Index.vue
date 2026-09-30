<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';

defineProps({
    products: {
        type: Object,
        required: true,
    },
});

const formatPrice = (value) => `$${Number(value).toFixed(2)}`;
</script>

<template>
    <Head title="Shop" />

    <Navbar />

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <h1 class="mb-8 text-2xl font-semibold tracking-tight text-gray-900">Shop</h1>

        <div v-if="products.data.length === 0" class="rounded-lg border border-dashed border-gray-300 p-12 text-center text-gray-500">
            No products available right now.
        </div>

        <div v-else class="grid grid-cols-2 gap-6 sm:grid-cols-3 lg:grid-cols-4">
            <Link
                v-for="product in products.data"
                :key="product.id"
                :href="`/products/${product.slug}`"
                class="group flex flex-col overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm transition hover:shadow-md"
            >
                <div class="aspect-square w-full overflow-hidden bg-gray-100">
                    <img
                        v-if="product.primary_image"
                        :src="`/storage/${product.primary_image.image}`"
                        :alt="product.name"
                        class="h-full w-full object-cover transition group-hover:scale-105"
                    />
                    <div v-else class="flex h-full w-full items-center justify-center text-sm text-gray-400">
                        No image
                    </div>
                </div>

                <div class="flex flex-1 flex-col gap-1 p-4">
                    <p v-if="product.category" class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        {{ product.category.name }}
                    </p>

                    <h2 class="text-sm font-medium text-gray-900">{{ product.name }}</h2>

                    <div class="mt-auto flex items-baseline gap-2 pt-2">
                        <span class="text-sm font-semibold text-gray-900">
                            {{ formatPrice(product.price) }}
                        </span>
                        <span
                            v-if="product.compare_price"
                            class="text-xs text-gray-400 line-through"
                        >
                            {{ formatPrice(product.compare_price) }}
                        </span>
                    </div>
                </div>
            </Link>
        </div>

        <nav v-if="products.links.length > 3" class="mt-10 flex flex-wrap items-center justify-center gap-1">
            <template v-for="(link, index) in products.links" :key="index">
                <Link
                    v-if="link.url"
                    :href="link.url"
                    preserve-scroll
                    class="rounded-md px-3 py-2 text-sm"
                    :class="link.active
                        ? 'bg-indigo-600 text-white'
                        : 'text-gray-600 hover:bg-gray-100'"
                    v-html="link.label"
                />
                <span
                    v-else
                    class="rounded-md px-3 py-2 text-sm text-gray-300"
                    v-html="link.label"
                />
            </template>
        </nav>
    </div>
</template>
