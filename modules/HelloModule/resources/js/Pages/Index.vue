<script setup>
import { Head } from '@inertiajs/vue3';

defineProps({
    message: String
});
</script>

<template>
    <Head title="Hello Module" />

    <div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden md:max-w-2xl">
            <div class="md:flex">
                <div class="p-8">
                    <div class="uppercase tracking-wide text-sm text-indigo-500 font-semibold">
                        LogicDir CMS Module
                    </div>
                    <h1 class="block mt-1 text-lg leading-tight font-medium text-black">
                        {{ message }}
                    </h1>
                    <p class="mt-2 text-gray-500">
                        This page is being rendered from a dynamically loaded module using Inertia.js and Vue 3.
                        The module system automatically discovered this route and correctly resolved this component.
                    </p>
                    <div class="mt-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            Active
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
