<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post('/login');
};
</script>

<template>
    <Head title="Login" />

    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
        <div class="w-full max-w-md rounded-xl bg-white p-8 shadow">
            <h1 class="mb-2 text-2xl font-bold">Welcome back</h1>
            <p class="mb-6 text-gray-600">Sign in to your NOVA account.</p>

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
                        Password
                    </label>

                    <input
                        id="password"
                        v-model="form.password"
                        type="password"
                        autocomplete="current-password"
                        required
                        class="w-full rounded-lg border px-3 py-2"
                    />

                    <p v-if="form.errors.password" class="mt-1 text-sm text-red-600">
                        {{ form.errors.password }}
                    </p>
                </div>

                <label class="flex items-center gap-2 text-sm">
                    <input v-model="form.remember" type="checkbox" />
                    Remember me
                </label>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full rounded-lg bg-gray-900 px-4 py-2 text-white disabled:opacity-50"
                >
                    {{ form.processing ? 'Signing in...' : 'Sign in' }}
                </button>
            </form>

            <div class="mt-6 flex justify-between text-sm">
                <Link
                    href="/forgot-password"
                    class="text-gray-600 hover:text-gray-900"
                >
                    Forgot password?
                </Link>

                <Link
                    href="/register"
                    class="font-medium text-gray-900 hover:underline"
                >
                    Create account
                </Link>
            </div>
        </div>
    </div>
</template>
