<script setup>
import { computed } from 'vue';

const props = defineProps({
    meta: Object,
    contentTitle: String,
    contentDescription: String,
    contentImage: String
});

const title = computed(() => props.meta?.og_title || props.meta?.meta_title || props.contentTitle);
const description = computed(() => props.meta?.og_description || props.meta?.meta_description || props.contentDescription);
const image = computed(() => props.meta?.og_image || props.contentImage || 'https://via.placeholder.com/1200x630');
</script>

<template>
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 space-y-6">
        <h3 class="text-lg font-black text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-4">
            Social Media Preview
        </h3>

        <!-- Facebook Preview -->
        <div class="space-y-3">
            <h4 class="text-sm font-bold text-slate-600 flex items-center gap-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                Facebook
            </h4>
            <div class="border border-slate-200 rounded-xl overflow-hidden">
                <img :src="image" alt="Preview" class="w-full aspect-[1.91/1] object-cover bg-slate-100" />
                <div class="p-4 bg-slate-50">
                    <div class="text-xs text-slate-500 uppercase mb-1">example.com</div>
                    <div class="font-bold text-slate-900 mb-1 line-clamp-1">{{ title }}</div>
                    <div class="text-sm text-slate-600 line-clamp-2">{{ description }}</div>
                </div>
            </div>
        </div>

        <!-- Twitter Preview -->
        <div class="space-y-3">
            <h4 class="text-sm font-bold text-slate-600 flex items-center gap-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                Twitter
            </h4>
            <div class="border border-slate-200 rounded-2xl overflow-hidden">
                <img :src="image" alt="Preview" class="w-full aspect-[2/1] object-cover bg-slate-100" />
                <div class="p-4">
                    <div class="font-bold text-slate-900 mb-1 line-clamp-1">{{ title }}</div>
                    <div class="text-sm text-slate-600 line-clamp-2 mb-2">{{ description }}</div>
                    <div class="text-xs text-slate-400">example.com</div>
                </div>
            </div>
        </div>
    </div>
</template>
