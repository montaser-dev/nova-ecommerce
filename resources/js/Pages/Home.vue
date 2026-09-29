<script setup>
import { Head } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';
</script>

<template>
    <Head title="Home" />

    <div class="min-h-screen bg-gray-950">
        <Navbar />

        <main class="flex min-h-[calc(100vh-73px)] items-center justify-center">
            <div class="text-center">
                <h1 class="text-6xl font-bold text-white">
                    NOVA
                </h1>

                <p class="mt-4 text-xl text-gray-400">
                    Welcome to NOVA E-Commerce
                </p>
            </div>
        </main>
    </div>
</template>
