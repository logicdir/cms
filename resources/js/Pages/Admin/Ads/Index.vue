<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    adUnits: Array,
    stats: Object
});
</script>

<template>
    <Head title="Ad Management" />
    <div class="p-8 max-w-7xl mx-auto space-y-8">
        <header class="flex justify-between items-center bg-slate-900 p-8 rounded-3xl shadow-2xl text-white">
            <div>
                <h1 class="text-2xl font-black uppercase tracking-tighter">Ad Management</h1>
                <p class="text-slate-400 text-sm">Track and optimize your advertisement placements.</p>
            </div>
            <Link 
                href="/admin/ads/create" 
                class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 rounded-xl text-xs font-black uppercase tracking-widest transition-all"
            >
                Create Ad Unit
            </Link>
        </header>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-6">
                <div class="p-4 bg-indigo-50 text-indigo-600 rounded-2xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                </div>
                <div>
                    <div class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total Impressions</div>
                    <div class="text-3xl font-black text-slate-900">{{ stats.total_impressions }}</div>
                </div>
            </div>
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-6">
                <div class="p-4 bg-green-50 text-green-600 rounded-2xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5" /></svg>
                </div>
                <div>
                    <div class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total Clicks</div>
                    <div class="text-3xl font-black text-slate-900">{{ stats.total_clicks }}</div>
                </div>
            </div>
        </div>

        <!-- Ad List -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Name</th>
                        <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Type</th>
                        <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Position</th>
                        <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Impressions</th>
                        <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="unit in adUnits" :key="unit.id" class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors">
                        <td class="px-8 py-6 font-bold text-slate-900">{{ unit.name }}</td>
                        <td class="px-8 py-6">
                            <span :class="['px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest', unit.type === 'adsense' ? 'bg-amber-100 text-amber-600' : 'bg-indigo-100 text-indigo-600']">
                                {{ unit.type }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-sm text-slate-500 font-mono">{{ unit.position || 'Auto' }}</td>
                        <td class="px-8 py-6 font-bold text-slate-900">{{ unit.impressions_count }}</td>
                        <td class="px-8 py-6 text-right">
                            <button class="text-slate-400 hover:text-indigo-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
