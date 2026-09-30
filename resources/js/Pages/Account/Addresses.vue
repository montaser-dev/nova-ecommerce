<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';

defineProps({
    addresses: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    first_name: '',
    last_name: '',
    phone: '',
    address_line: '',
    city: '',
    postal_code: '',
    country: 'Morocco',
    is_default: false,
});

const submit = () => {
    form.post('/account/addresses', {
        onSuccess: () => form.reset('first_name', 'last_name', 'phone', 'address_line', 'city', 'postal_code'),
    });
};
</script>

<template>
    <Head title="My Addresses" />

<div class="min-h-screen bg-gray-100">
    <Navbar />

    <main class="mx-auto max-w-5xl px-6 py-12">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    My Addresses
                </h1>

                <p class="mt-2 text-gray-600">
                    Manage your shipping addresses.
                </p>
            </div>

            <Link
                href="/account"
                class="text-sm text-gray-600 hover:text-gray-900"
            >
                Back to Account
            </Link>
        </div>

        <div class="mt-8 grid gap-8 lg:grid-cols-2">
            <!-- Add Address -->
            <section class="rounded-xl bg-white p-6 shadow">
                <h2 class="text-xl font-semibold text-gray-900">
                    Add Address
                </h2>

                <form class="mt-6 space-y-5" @submit.prevent="submit">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label
                                for="first_name"
                                class="block text-sm font-medium text-gray-700"
                            >
                                First Name
                            </label>

                            <input
                                id="first_name"
                                v-model="form.first_name"
                                type="text"
                                autocomplete="given-name"
                                class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-200"
                            />

                            <p
                                v-if="form.errors.first_name"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ form.errors.first_name }}
                            </p>
                        </div>

                        <div>
                            <label
                                for="last_name"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Last Name
                            </label>

                            <input
                                id="last_name"
                                v-model="form.last_name"
                                type="text"
                                autocomplete="family-name"
                                class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-200"
                            />

                            <p
                                v-if="form.errors.last_name"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ form.errors.last_name }}
                            </p>
                        </div>
                    </div>

                    <div>
                        <label
                            for="phone"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Phone
                        </label>

                        <input
                            id="phone"
                            v-model="form.phone"
                            type="tel"
                            autocomplete="tel"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-200"
                        />

                        <p
                            v-if="form.errors.phone"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.phone }}
                        </p>
                    </div>

                    <div>
                        <label
                            for="address_line"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Address
                        </label>

                        <input
                            id="address_line"
                            v-model="form.address_line"
                            type="text"
                            autocomplete="street-address"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-200"
                        />

                        <p
                            v-if="form.errors.address_line"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.address_line }}
                        </p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label
                                for="city"
                                class="block text-sm font-medium text-gray-700"
                            >
                                City
                            </label>

                            <input
                                id="city"
                                v-model="form.city"
                                type="text"
                                autocomplete="address-level2"
                                class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-200"
                            />

                            <p
                                v-if="form.errors.city"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ form.errors.city }}
                            </p>
                        </div>

                        <div>
                            <label
                                for="postal_code"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Postal Code
                            </label>

                            <input
                                id="postal_code"
                                v-model="form.postal_code"
                                type="text"
                                autocomplete="postal-code"
                                class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-200"
                            />

                            <p
                                v-if="form.errors.postal_code"
                                class="mt-1 text-sm text-red-600"
                            >
                                {{ form.errors.postal_code }}
                            </p>
                        </div>
                    </div>

                    <div>
                        <label
                            for="country"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Country
                        </label>

                        <input
                            id="country"
                            v-model="form.country"
                            type="text"
                            autocomplete="country-name"
                            class="mt-2 block w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-200"
                        />

                        <p
                            v-if="form.errors.country"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ form.errors.country }}
                        </p>
                    </div>

                    <label class="flex cursor-pointer items-center gap-3">
                        <input
                            v-model="form.is_default"
                            type="checkbox"
                            class="h-4 w-4 rounded border-gray-300"
                        />

                        <span class="text-sm text-gray-700">
                            Make this my default address
                        </span>
                    </label>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full rounded-lg bg-gray-900 px-5 py-3 font-medium text-white hover:bg-gray-800 disabled:cursor-not-allowed disabled:bg-gray-400"
                    >
                        {{ form.processing ? 'Saving...' : 'Add Address' }}
                    </button>
                </form>
            </section>

            <!-- Saved Addresses -->
            <section>
                <div
                    v-if="addresses.length === 0"
                    class="rounded-xl bg-white p-8 text-center shadow"
                >
                    <p class="text-gray-500">
                        You don't have any saved addresses yet.
                    </p>
                </div>

                <div v-else class="space-y-4">
                    <div
                        v-for="address in addresses"
                        :key="address.id"
                        class="rounded-xl bg-white p-6 shadow"
                    >
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-900">
                                    {{ address.first_name }}
                                    {{ address.last_name }}
                                </h3>

                                <p class="mt-2 text-sm text-gray-600">
                                    {{ address.address_line }}
                                </p>

                                <p class="text-sm text-gray-600">
                                    {{ address.city }}
                                    {{ address.postal_code }}
                                </p>

                                <p class="text-sm text-gray-600">
                                    {{ address.country }}
                                </p>

                                <p class="mt-2 text-sm text-gray-600">
                                    {{ address.phone }}
                                </p>
                            </div>

                            <span
                                v-if="address.is_default"
                                class="rounded-full bg-gray-900 px-3 py-1 text-xs font-medium text-white"
                            >
                                Default
                            </span>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
</div>

</template>
