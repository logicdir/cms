<script setup>
import { Link } from '@inertiajs/vue3';
import Layout from './Layout.vue';
import RequirementList from '../../Components/Installer/RequirementList.vue';

defineProps({
    requirements: Object
});
</script>

<template>
    <Layout 
        :current-step="1" 
        title="Check Requirements" 
        description="Before we start, we need to make sure your server meets the minimum requirements to run LogicDir CMS."
    >
        <RequirementList title="System" :items="[{ 
            name: 'PHP Version', 
            version: requirements.php.version, 
            required: requirements.php.required, 
            passed: requirements.php.passed 
        }]" />

        <RequirementList title="Extensions" :items="requirements.extensions" />

        <RequirementList title="Permissions" :items="requirements.permissions" />

        <RequirementList title="Configuration" :items="requirements.settings" />

        <div class="mt-8 flex justify-end">
            <Link
                v-if="requirements.passed"
                href="/install/database"
                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all"
            >
                Next: Database Setup
                <svg class="ml-2 -mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </Link>
            <button
                v-else
                disabled
                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-sm text-white bg-gray-400 cursor-not-allowed"
            >
                Requirements Not Met
            </button>
        </div>
    </Layout>
</template>
