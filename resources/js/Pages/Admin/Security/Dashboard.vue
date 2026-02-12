<script setup>
import { Head } from '@inertiajs/vue3';

defineProps({
    recentLogs: Array,
    stats: Object
});
</script>

<template>
    <Head title="Security Dashboard" />
    <div class="p-8 max-w-7xl mx-auto space-y-8">
        <header class="bg-red-900 p-8 rounded-3xl shadow-2xl text-white">
            <h1 class="text-2xl font-black uppercase tracking-tighter">Security Dashboard</h1>
            <p class="text-red-300 text-sm">Enterprise-grade protection and audit monitoring.</p>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                <div class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Audit Status</div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    <span class="font-bold text-slate-700">Protected</span>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                <div class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Threat Level</div>
                <div class="font-bold text-green-600">Low</div>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                <div class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Failed Logins (24h)</div>
                <div class="font-bold text-red-600">{{ stats.failed_logins }}</div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-50 flex justify-between items-center">
                <h2 class="font-black uppercase text-xs tracking-widest text-slate-500">Recent Activity Log</h2>
            </div>
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">User</th>
                        <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Event</th>
                        <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Target</th>
                        <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">IP Address</th>
                        <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="log in recentLogs" :key="log.id" class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors">
                        <td class="px-8 py-4 font-bold text-slate-700">{{ log.user_id ? 'Admin #' + log.user_id : 'System' }}</td>
                        <td class="px-8 py-4">
                            <span :class="['px-2 py-0.5 rounded text-[9px] font-black uppercase', log.event === 'failed_login' ? 'bg-red-100 text-red-600' : 'bg-slate-100 text-slate-600']">
                                {{ log.event }}
                            </span>
                        </td>
                        <td class="px-8 py-4 text-xs font-mono text-slate-400">{{ log.auditable_type || '-' }}</td>
                        <td class="px-8 py-4 text-xs font-mono">{{ log.ip_address }}</td>
                        <td class="px-8 py-4 text-xs text-slate-400">{{ log.created_at }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
