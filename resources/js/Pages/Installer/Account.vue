<script setup>
import { useForm } from '@inertiajs/vue3';
import Layout from './Layout.vue';

const props = defineProps({
    site_url: String
});

const form = useForm({
    // Site Info
    site_name: '',
    site_url: props.site_url,
    
    // Admin Info
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('install.save-account'));
};
</script>

<template>
    <Layout 
        :current-step="4" 
        title="Account Setup" 
        description="Create your initial administrator account and configure basic site settings."
    >
        <form @submit.prevent="submit" class="space-y-8">
            <!-- Site Settings Section -->
            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                <h3 class="text-sm font-semibold text-slate-900 mb-4 uppercase tracking-wider flex items-center">
                    <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Site Configuration
                </h3>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Site Name</label>
                        <input v-model="form.site_name" type="text" placeholder="My Awesome CMS" class="block w-full border-slate-200 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                        <p v-if="form.errors.site_name" class="mt-1 text-xs text-red-500">{{ form.errors.site_name }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Site URL</label>
                        <input v-model="form.site_url" type="url" class="block w-full border-slate-200 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                        <p v-if="form.errors.site_url" class="mt-1 text-xs text-red-500">{{ form.errors.site_url }}</p>
                    </div>
                </div>
            </div>

            <!-- Admin Account Section -->
            <div class="bg-indigo-50/30 p-6 rounded-2xl border border-indigo-100">
                <h3 class="text-sm font-semibold text-indigo-900 mb-4 uppercase tracking-wider flex items-center">
                    <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Administrator Details
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Admin Name</label>
                        <input v-model="form.name" type="text" placeholder="John Doe" class="block w-full border-slate-200 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                        <p v-if="form.errors.name" class="mt-1 text-xs text-red-500">{{ form.errors.name }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Email Address</label>
                        <input v-model="form.email" type="email" placeholder="admin@example.com" class="block w-full border-slate-200 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                        <p v-if="form.errors.email" class="mt-1 text-xs text-red-500">{{ form.errors.email }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1">Password</label>
                            <input v-model="form.password" type="password" class="block w-full border-slate-200 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                            <p v-if="form.errors.password" class="mt-1 text-xs text-red-500">{{ form.errors.password }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1">Confirm Password</label>
                            <input v-model="form.password_confirmation" type="password" class="block w-full border-slate-200 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input id="security" type="checkbox" required class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-slate-300 rounded" />
                </div>
                <div class="ml-3 text-xs leading-5">
                    <label for="security" class="text-slate-500">
                        I understand that I should delete the installer directory after completion for security reasons.
                    </label>
                </div>
            </div>

            <div class="pt-4">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full inline-flex items-center justify-center px-6 py-4 border border-transparent text-base font-semibold rounded-2xl shadow-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all disabled:opacity-50"
                >
                    <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ form.processing ? 'Saving Details...' : 'Complete Installation' }}
                </button>
            </div>
        </form>
    </Layout>
</template>
