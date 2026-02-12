<script setup>
import { onMounted, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import Layout from './Layout.vue';
import axios from 'axios';

const migrating = ref(true);
const success = ref(false);
const message = ref('Running migrations and seeding the database...');
const error = ref(null);

const runMigration = async () => {
    try {
        const response = await axios.post('/install/run-migration');
        if (response.data.success) {
            success.value = true;
            message.value = 'Database optimized and tables created successfully.';
            setTimeout(() => {
                router.get('/install/account');
            }, 2000);
        } else {
            error.value = response.data.message;
        }
    } catch (e) {
        error.value = 'An error occurred during migration. Please check storage/logs/laravel.log';
    } finally {
        migrating.value = false;
    }
};

onMounted(() => {
    runMigration();
});
</script>

<template>
    <Layout 
        :current-step="3" 
        title="Database Migration" 
        description="Setting up your database schema and initializing system data."
    >
        <div class="flex flex-col items-center justify-center py-12">
            <div v-if="migrating" class="text-center">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-indigo-600 border-t-transparent mb-4"></div>
                <p class="text-slate-600 font-medium">{{ message }}</p>
                <p class="text-xs text-slate-400 mt-2">This may take a minute. Please don't close this window.</p>
            </div>

            <div v-else-if="success" class="text-center">
                <div class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-green-100 mb-4">
                    <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <p class="text-lg font-bold text-slate-900">{{ message }}</p>
                <p class="text-sm text-slate-500 mt-2">Redirecting to account setup...</p>
            </div>

            <div v-else class="text-center w-full max-w-lg">
                <div class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-red-100 mb-4">
                    <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <p class="text-lg font-bold text-slate-900">Migration Failed</p>
                <div class="mt-4 p-4 bg-red-50 text-red-700 text-sm rounded-xl text-left border border-red-100 font-mono">
                    {{ error }}
                </div>
                <button
                    @click="runMigration"
                    class="mt-6 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-xl text-white bg-red-600 hover:bg-red-700"
                >
                    Retry Migration
                </button>
            </div>
        </div>
    </Layout>
</template>
