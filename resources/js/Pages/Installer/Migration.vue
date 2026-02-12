<script setup>
import { onMounted, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import Layout from './Layout.vue';
import axios from 'axios';

const migrating = ref(true);
const success = ref(false);
const message = ref('Initializing system migration...');
const terminalOutput = ref([]);
const error = ref(null);

const addLog = (text, type = 'info') => {
    terminalOutput.value.push({
        text,
        type,
        time: new Date().toLocaleTimeString([], { hour12: false, hour: '2-digit', minute: '2-digit', second: '2-digit' })
    });
};

const runMigration = async () => {
    addLog('Starting artisan migrate --force...', 'command');
    
    try {
        const response = await axios.post(route('install.migrate'));
        
        if (response.data.output) {
            const lines = response.data.output.split('\n');
            lines.forEach(line => {
                if (line.trim()) addLog(line, 'output');
            });
        }

        if (response.data.success) {
            success.value = true;
            message.value = 'Database optimized and tables created successfully.';
            addLog('Migration completed successfully.', 'success');
            setTimeout(() => {
                router.get(route('install.account'));
            }, 3000);
        } else {
            error.value = response.data.message;
            addLog('ERROR: ' + response.data.message, 'error');
        }
    } catch (e) {
        const errorMsg = 'An error occurred during migration. Please check storage/logs/laravel.log';
        error.value = errorMsg;
        addLog('FATAL: ' + errorMsg, 'error');
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
        <div class="space-y-6">
            <!-- Terminal Window -->
            <div class="bg-slate-950 rounded-2xl shadow-2xl border border-slate-800 overflow-hidden font-mono text-sm">
                <!-- Terminal Header -->
                <div class="bg-slate-900 px-4 py-3 flex items-center justify-between border-b border-slate-800">
                    <div class="flex space-x-2">
                        <div class="w-3 h-3 rounded-full bg-red-500/80"></div>
                        <div class="w-3 h-3 rounded-full bg-amber-500/80"></div>
                        <div class="w-3 h-3 rounded-full bg-green-500/80"></div>
                    </div>
                    <div class="text-slate-400 text-xs font-medium">artisan migrate</div>
                    <div class="w-12"></div>
                </div>
                
                <!-- Terminal Content -->
                <div class="p-4 h-80 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-700 scrollbar-track-transparent bg-slate-950/50 backdrop-blur-sm">
                    <div v-for="(log, index) in terminalOutput" :key="index" class="mb-1 flex items-start">
                        <span class="text-slate-600 mr-3 select-none">[{{ log.time }}]</span>
                        <span v-if="log.type === 'command'" class="text-indigo-400">➜</span>
                        <span 
                            :class="{
                                'text-slate-300': log.type === 'output',
                                'text-indigo-300 font-bold': log.type === 'command',
                                'text-green-400': log.type === 'success',
                                'text-red-400': log.type === 'error'
                            }"
                            class="ml-2 whitespace-pre-wrap break-all"
                        >
                            {{ log.text }}
                        </span>
                    </div>
                    <!-- Typing Indicator -->
                    <div v-if="migrating" class="flex items-center text-slate-400 mt-2">
                        <span class="mr-2 italic">Processing</span>
                        <span class="flex space-x-1">
                            <span class="animate-bounce">.</span>
                            <span class="animate-bounce" style="animation-delay: 0.2s">.</span>
                            <span class="animate-bounce" style="animation-delay: 0.4s">.</span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Status Footer -->
            <div class="flex items-center justify-between px-2">
                <div v-if="migrating" class="flex items-center text-slate-500">
                    <svg class="animate-spin h-5 w-5 text-indigo-500 mr-3" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-sm font-medium">{{ message }}</span>
                </div>

                <div v-else-if="success" class="flex items-center text-green-600">
                    <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="text-sm font-bold">{{ message }}</span>
                </div>

                <div v-else class="flex flex-col items-end">
                    <div class="flex items-center text-red-500 mb-2">
                        <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span class="text-sm font-bold">Migration Failed</span>
                    </div>
                    <button
                        @click="runMigration"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-semibold transition-all shadow-lg active:scale-95"
                    >
                        Retry Installation
                    </button>
                </div>
            </div>
        </div>
    </Layout>
</template>

<style scoped>
.scrollbar-thin::-webkit-scrollbar {
    width: 6px;
}
.scrollbar-thin::-webkit-scrollbar-track {
    background: transparent;
}
.scrollbar-thin::-webkit-scrollbar-thumb {
    background: #334155;
    border-radius: 10px;
}
.scrollbar-thin::-webkit-scrollbar-thumb:hover {
    background: #475569;
}
</style>
