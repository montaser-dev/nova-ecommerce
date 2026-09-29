<script setup>
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({});

const logout = () => {
    form.post('/logout');
};
</script>

<template>
    <Head title="Home" />

    <div class="min-h-screen bg-gray-950 flex items-center justify-center">
        <div class="text-center">
            <h1 class="text-6xl font-bold text-white">
                NOVA
            </h1>

            <p class="mt-4 text-xl text-gray-400">
                Vue + Inertia + Tailwind is working.
            </p>

            <button
                @click="logout"
                :disabled="form.processing"
                class="mt-8 rounded-lg bg-white px-6 py-3 font-medium text-gray-950 hover:bg-gray-200 disabled:opacity-50"
            >
                {{ form.processing ? 'Logging out...' : 'Logout' }}
            </button>
        </div>
    </div>
</template
