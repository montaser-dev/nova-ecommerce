<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    email: '',
});

const submit = () => {
    form.post('/forgot-password');
};
</script>

<template>
    <Head title="Forgot Password" />

    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
        <div class="w-full max-w-md rounded-xl bg-white p-8 shadow">
            <h1 class="mb-2 text-2xl font-bold">Forgot your password?</h1>

            <p class="mb-6 text-gray-600">
                Enter your email and we'll send you a password reset link.
            </p>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label for="email" class="mb-1 block text-sm font-medium">
                        Email
                    </label>

                    <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        autocomplete="email"
                        required
                        class="w-full rounded-lg border px-3 py-2"
                    />

                    <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">
                        {{ form.errors.email }}
                    </p>
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full rounded-lg bg-gray-900 px-4 py-2 text-white disabled:opacity-50"
                >
                    {{ form.processing ? 'Sending...' : 'Send reset link' }}
                </button>
            </form>

            <p class="mt-6 text-center text-sm">
                <Link href="/login" class="font-medium text-gray-900 hover:underline">
                    Back to login
                </Link>
            </p>
        </div>
    </div>
</template>
