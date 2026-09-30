<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3';

const page = usePage();
const form = useForm({});

const logout = () => {
    form.post('/logout');
};
</script>

<template>
    <nav class="border-b bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
            <Link
                href="/home"
                class="text-2xl font-bold tracking-tight text-gray-900"
            >
                NOVA
            </Link>

            <div class="flex items-center gap-6">
                <Link
                    href="/home"
                    class="text-sm font-medium text-gray-600 hover:text-gray-900"
                >
                    Home
                </Link>
                <Link
    href="/cart"
    class="text-sm font-medium text-gray-600 hover:text-gray-900"
>
    Cart
</Link>

                <Link
                    href="/account"
                    class="text-sm font-medium text-gray-600 hover:text-gray-900"
                >
                    My Account
                </Link>

                <button
                    @click="logout"
                    :disabled="form.processing"
                    class="text-sm font-medium text-red-600 hover:text-red-700 disabled:opacity-50"
                >
                    {{ form.processing ? 'Logging out...' : 'Logout' }}
                </button>
            </div>
        </div>
    </nav>
</template>
