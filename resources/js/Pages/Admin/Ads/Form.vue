<script setup>
import { useForm, Head } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    type: 'adsense',
    code: '',
    position: 'content_middle',
    auto_insert: false,
    display_rules: {
        after_paragraph: 3,
        exclude_categories: []
    }
});

const submit = () => {
    form.post('/admin/ads');
};
</script>

<template>
    <Head title="Create Ad Unit" />
    <div class="p-8 max-w-3xl mx-auto space-y-8">
        <header class="bg-slate-900 p-8 rounded-3xl shadow-2xl text-white">
            <h1 class="text-2xl font-black uppercase tracking-tighter">Create Ad Unit</h1>
            <p class="text-slate-400 text-sm">Configure your advertisement code and rules.</p>
        </header>

        <form @submit.prevent="submit" class="bg-white p-10 rounded-3xl shadow-sm border border-slate-100 space-y-8">
            <div class="space-y-4">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Unit Name</label>
                <input v-model="form.name" type="text" placeholder="Sidebar Ad" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all font-bold" />
            </div>

            <div class="grid grid-cols-2 gap-8">
                <div class="space-y-4">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Type</label>
                    <select v-model="form.type" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 font-bold">
                        <option value="adsense">AdSense</option>
                        <option value="custom">Custom HTML</option>
                    </select>
                </div>
                <div class="space-y-4">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Position</label>
                    <select v-model="form.position" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 font-bold">
                        <option value="header">Header</option>
                        <option value="sidebar_top">Sidebar Top</option>
                        <option value="content_middle">Content Middle</option>
                        <option value="footer">Footer</option>
                    </select>
                </div>
            </div>

            <div class="space-y-4">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Ad Code (HTML/JS)</label>
                <textarea v-model="form.code" rows="6" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 font-mono text-xs"></textarea>
            </div>

            <div class="p-8 bg-slate-50 rounded-2xl space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="font-black text-slate-900">Auto Insertion</div>
                        <div class="text-xs text-slate-500">Inject this ad automatically into content.</div>
                    </div>
                    <input type="checkbox" v-model="form.auto_insert" class="w-6 h-6 rounded-lg text-indigo-600 focus:ring-indigo-500" />
                </div>

                <div v-if="form.auto_insert" class="space-y-4 pt-4 border-t border-slate-200">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Insert After Paragraph</label>
                    <input v-model.number="form.display_rules.after_paragraph" type="number" class="w-full px-6 py-4 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 font-bold" />
                </div>
            </div>

            <button type="submit" class="w-full py-5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl text-xs font-black uppercase tracking-widest transition-all shadow-xl shadow-indigo-100">
                Save Ad Unit
            </button>
        </form>
    </div>
</template>
