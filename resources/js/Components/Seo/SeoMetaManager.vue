<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    modelValue: Object,
    contentTitle: String
});

const emit = defineEmits(['update:modelValue']);

const meta = computed({
    get: () => props.modelValue || {},
    set: (value) => emit('update:modelValue', value)
});

const updateField = (field, value) => {
    meta.value = { ...meta.value, [field]: value };
};

const titleLength = computed(() => (meta.value.meta_title || '').length);
const descriptionLength = computed(() => (meta.value.meta_description || '').length);
</script>

<template>
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <h3 class="text-lg font-black text-slate-900 uppercase tracking-wider">SEO Settings</h3>
            <div class="flex items-center gap-2 text-xs">
                <span class="px-3 py-1 bg-green-50 text-green-700 rounded-full font-bold">Optimized</span>
            </div>
        </div>

        <!-- Meta Title -->
        <div class="space-y-2">
            <label class="flex items-center justify-between text-sm font-bold text-slate-700">
                <span>Meta Title</span>
                <span :class="['text-xs font-mono', titleLength > 60 ? 'text-red-600' : 'text-slate-400']">
                    {{ titleLength }}/60
                </span>
            </label>
            <input 
                type="text" 
                :value="meta.meta_title"
                @input="updateField('meta_title', $event.target.value)"
                :placeholder="contentTitle"
                class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
            />
            <p class="text-xs text-slate-500">Recommended: 50-60 characters</p>
        </div>

        <!-- Meta Description -->
        <div class="space-y-2">
            <label class="flex items-center justify-between text-sm font-bold text-slate-700">
                <span>Meta Description</span>
                <span :class="['text-xs font-mono', descriptionLength > 160 ? 'text-red-600' : 'text-slate-400']">
                    {{ descriptionLength }}/160
                </span>
            </label>
            <textarea 
                :value="meta.meta_description"
                @input="updateField('meta_description', $event.target.value)"
                rows="3"
                placeholder="Brief description of this content..."
                class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all resize-none"
            ></textarea>
            <p class="text-xs text-slate-500">Recommended: 120-160 characters</p>
        </div>

        <!-- Meta Keywords -->
        <div class="space-y-2">
            <label class="text-sm font-bold text-slate-700">Meta Keywords</label>
            <input 
                type="text" 
                :value="meta.meta_keywords"
                @input="updateField('meta_keywords', $event.target.value)"
                placeholder="keyword1, keyword2, keyword3"
                class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
            />
            <p class="text-xs text-slate-500">Comma-separated keywords (optional)</p>
        </div>

        <!-- Robots -->
        <div class="space-y-2">
            <label class="text-sm font-bold text-slate-700">Robots Meta</label>
            <select 
                :value="meta.robots || 'index,follow'"
                @change="updateField('robots', $event.target.value)"
                class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
            >
                <option value="index,follow">Index, Follow (Default)</option>
                <option value="noindex,follow">No Index, Follow</option>
                <option value="index,nofollow">Index, No Follow</option>
                <option value="noindex,nofollow">No Index, No Follow</option>
            </select>
        </div>

        <!-- Canonical URL -->
        <div class="space-y-2">
            <label class="text-sm font-bold text-slate-700">Canonical URL</label>
            <input 
                type="url" 
                :value="meta.canonical_url"
                @input="updateField('canonical_url', $event.target.value)"
                placeholder="https://example.com/page"
                class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
            />
            <p class="text-xs text-slate-500">Leave empty to use current URL</p>
        </div>
    </div>
</template>
