<script setup>
import { ref, watch } from 'vue';
import axios from 'axios';
import MediaGrid from './MediaGrid.vue';
import MediaUploader from './MediaUploader.vue';

const props = defineProps({
    show: Boolean,
    title: { type: String, default: 'Select Media' },
    multiple: { type: Boolean, default: false }
});

const emit = defineEmits(['close', 'select']);

const loading = ref(true);
const content = ref({ folders: [], media: [] });
const currentFolderId = ref(null);
const activeTab = ref('browse'); // browse or upload
const selected = ref({ files: [], folders: [] });

const fetchContent = async (folderId = null) => {
    loading.value = true;
    currentFolderId.value = folderId;
    try {
        const response = await axios.get(route('admin.media.index'), {
            params: { folder_id: folderId },
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        // In a real Inertia app with partial reloads, this might be different.
        // Here we simulate the logic or handle the object structure.
        content.value = response.data.content || { folders: [], media: [] };
    } catch (e) {
        console.error('Failed to fetch media:', e);
    } finally {
        loading.value = false;
    }
};

watch(() => props.show, (val) => {
    if (val) fetchContent();
});

const confirmSelection = () => {
    const selectedFiles = content.value.media.filter(m => selected.value.files.includes(m.id));
    emit('select', props.multiple ? selectedFiles : selectedFiles[0]);
    emit('close');
};

const handleUploadFinished = () => {
    activeTab.value = 'browse';
    fetchContent(currentFolderId.value);
};
</script>

<template>
    <div v-if="show" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md" @click="$emit('close')"></div>

        <div class="relative bg-white w-full max-w-5xl h-[85vh] rounded-[2rem] shadow-2xl flex flex-col overflow-hidden animate-in fade-in zoom-in duration-300">
            <!-- Header -->
            <header class="px-8 py-6 border-b flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-black text-slate-900 flex items-center gap-3">
                        <div class="w-8 h-8 bg-indigo-600 text-white rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                        {{ title }}
                    </h2>
                </div>

                <div class="flex bg-slate-100 p-1 rounded-xl">
                    <button 
                        @click="activeTab = 'browse'"
                        :class="['px-6 py-2 rounded-lg text-xs font-bold transition-all', activeTab === 'browse' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700']"
                    >
                        Browse Library
                    </button>
                    <button 
                        @click="activeTab = 'upload'"
                        :class="['px-6 py-2 rounded-lg text-xs font-bold transition-all', activeTab === 'upload' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700']"
                    >
                        Upload New
                    </button>
                </div>
            </header>

            <!-- Main Content Area -->
            <div class="flex-1 overflow-auto bg-slate-50/50">
                <div v-if="activeTab === 'browse'" class="p-8">
                    <div v-if="loading" class="h-64 flex items-center justify-center">
                        <div class="animate-spin rounded-full h-10 w-10 border-4 border-indigo-600 border-t-transparent"></div>
                    </div>
                    <MediaGrid 
                        v-else
                        :folders="content.folders" 
                        :media="content.media"
                        v-model:selected="selected"
                    />
                </div>
                <div v-else class="h-full flex items-center justify-center">
                    <div class="w-full max-w-xl">
                        <MediaUploader 
                            :folder-id="currentFolderId"
                            @close="$emit('close')"
                            @finished="handleUploadFinished"
                        />
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="px-8 py-6 border-t bg-white flex items-center justify-between">
                <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">
                    {{ selected.files.length }} ITEMS SELECTED
                </span>
                <div class="flex gap-4">
                    <button @click="$emit('close')" class="px-8 py-3 text-xs font-bold text-slate-500 uppercase tracking-widest hover:text-slate-700">Cancel</button>
                    <button 
                        @click="confirmSelection"
                        :disabled="selected.files.length === 0"
                        class="px-10 py-3 bg-indigo-600 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition-all disabled:opacity-30"
                    >
                        Insert Media
                    </button>
                </div>
            </footer>
        </div>
    </div>
</template>
