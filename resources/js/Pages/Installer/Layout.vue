<script setup>
import ProgressBar from '../../Components/Installer/ProgressBar.vue';

defineProps({
    currentStep: Number,
    title: String,
    description: String
});
</script>

<template>
    <div class="min-h-screen bg-slate-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8 font-sans text-slate-900">
        <div class="sm:mx-auto sm:w-full sm:max-w-2xl">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-extrabold text-indigo-600 tracking-tight">
                    LogicDir CMS
                </h1>
                <p class="mt-2 text-sm text-slate-500">
                    Precision installation for premium experiences.
                </p>
            </div>

            <div class="bg-white py-8 px-4 shadow-xl shadow-slate-200/50 sm:rounded-2xl sm:px-10 border border-slate-100">
                <ProgressBar :current-step="currentStep" />
                
                <div class="mt-8">
                    <h2 class="text-xl font-bold border-b border-slate-100 pb-4 mb-6">
                        {{ title }}
                    </h2>
                    
                    <p v-if="description" class="text-sm text-slate-600 mb-8 leading-relaxed">
                        {{ description }}
                    </p>

                    <slot />
                </div>
            </div>

            <div class="mt-6 text-center text-xs text-slate-400">
                &copy; {{ new Date().getFullYear() }} LogicDir CMS. All rights reserved.
            </div>
        </div>
    </div>
</template>

<style>
@import 'https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap';

body {
    font-family: 'Outfit', sans-serif;
}
</style>
