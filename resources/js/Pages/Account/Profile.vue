<script setup>
import { Head, usePage } from '@inertiajs/vue3';

const page = usePage();
const user = page.props.auth.user;
</script>

<template>
    <Head title="My Account" />

    <div class="min-h-screen bg-gray-100 px-4 py-12">
        <div class="mx-auto max-w-2xl">
            <div class="rounded-xl bg-white p-8 shadow">
                <h1 class="text-3xl font-bold text-gray-900">
                    My Account
                </h1>

                <div class="mt-8 space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Name</p>
                        <p class="font-medium text-gray-900">
                            {{ user.name }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium text-gray-900">
                            {{ user.email }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Role</p>
                        <p class="font-medium capitalize text-gray-900">
                            {{ user.role }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
