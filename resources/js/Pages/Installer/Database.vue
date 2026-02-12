<script setup>
import { reactive, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import Layout from './Layout.vue';
import axios from 'axios';

const props = defineProps({
    current_config: Object
});

const form = reactive({
    host: props.current_config.host,
    port: props.current_config.port,
    database: props.current_config.database,
    username: props.current_config.username,
    password: '',
    prefix: props.current_config.prefix,
});

const testing = ref(false);
const testResult = ref(null);
const errors = ref({});

const testConnection = async () => {
    testing.ref = true;
    testResult.value = null;
    errors.value = {};

    try {
        const response = await axios.post(route('install.test'), form);
        testResult.value = response.data;
    } catch (e) {
        if (e.response && e.response.status === 422) {
            errors.value = e.response.data.errors;
        } else {
            testResult.value = { passed: false, message: 'An unexpected error occurred.' };
        }
    } finally {
        testing.value = false;
    }
};

const next = () => {
    if (testResult.value?.passed) {
        router.get(route('install.migration'));
    }
};
</script>

<template>
    <Layout 
        :current-step="2" 
        title="Database Configuration" 
        description="Configure your database settings. We'll automatically update your .env file."
    >
        <div class="space-y-6">
            <div class="grid grid-cols-6 gap-6">
                <div class="col-span-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Database Host</label>
                    <input v-model="form.host" type="text" class="block w-full border-slate-200 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                    <p v-if="errors.host" class="mt-1 text-xs text-red-500">{{ errors.host[0] }}</p>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Port</label>
                    <input v-model="form.port" type="text" class="block w-full border-slate-200 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                    <p v-if="errors.port" class="mt-1 text-xs text-red-500">{{ errors.port[0] }}</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Database Name</label>
                <input v-model="form.database" type="text" class="block w-full border-slate-200 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                <p v-if="errors.database" class="mt-1 text-xs text-red-500">{{ errors.database[0] }}</p>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Username</label>
                    <input v-model="form.username" type="text" class="block w-full border-slate-200 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                    <p v-if="errors.username" class="mt-1 text-xs text-red-500">{{ errors.username[0] }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                    <input v-model="form.password" type="password" class="block w-full border-slate-200 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                    <p v-if="errors.password" class="mt-1 text-xs text-red-500">{{ errors.password[0] }}</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Table Prefix</label>
                <input v-model="form.prefix" type="text" class="block w-full border-slate-200 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="ld_" />
            </div>

            <div v-if="testResult" :class="['p-4 rounded-xl text-sm mb-4', testResult.passed ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700']">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg v-if="testResult.passed" class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <svg v-else class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="font-medium">{{ testResult.passed ? 'Connection Successful!' : 'Connection Failed' }}</p>
                        <p v-if="testResult.message" class="mt-1 text-xs opacity-80">{{ testResult.message }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-between">
                <button
                    @click="testConnection"
                    :disabled="testing"
                    class="inline-flex items-center px-4 py-2 border border-slate-300 shadow-sm text-sm font-medium rounded-xl text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all disabled:opacity-50"
                >
                    <svg v-if="testing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ testing ? 'Testing...' : 'Test Connection' }}
                </button>

                <button
                    @click="next"
                    :disabled="!testResult?.passed"
                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all disabled:bg-gray-400 disabled:cursor-not-allowed"
                >
                    Next: Migration
                </button>
            </div>
        </div>
    </Layout>
</template>
