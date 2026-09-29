<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    request: Object,
});

const form = useForm({
    token: props.request?.route?.token ?? '',
    email: props.request?.query?.email ?? '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post('/reset-password');
};
</script>

<template>
    <Head title="Reset Password" />

    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
        <div class="w-full max-w-md rounded-xl bg-white p-8 shadow">
            <h1 class="mb-2 text-2xl font-bold">Reset your password</h1>

            <p class="mb-6 text-gray-600">
                Enter your new password below.
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

                <div>
                    <label for="password" class="mb-1 block text-sm font-medium">
                        New password
                    </label>

                    <input
                        id="password"
                        v-model="form.password"
                        type="password"
                        autocomplete="new-password"
                        required
                        class="w-full rounded-lg border px-3 py-2"
                    />

                    <p v-if="form.errors.password" class="mt-1 text-sm text-red-600">
                        {{ form.errors.password }}
                    </p>
                </div>

                <div>
                    <label
                        for="password_confirmation"
                        class="mb-1 block text-sm font-medium"
                    >
                        Confirm new password
                    </label>

                    <input
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        autocomplete="new-password"
                        required
                        class="w-full rounded-lg border px-3 py-2"
                    />
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full rounded-lg bg-gray-900 px-4 py-2 text-white disabled:opacity-50"
                >
                    {{ form.processing ? 'Resetting...' : 'Reset password' }}
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
