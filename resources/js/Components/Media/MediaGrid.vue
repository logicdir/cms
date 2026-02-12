<script setup>
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    folders: Array,
    media: Array,
    selected: Object // { files: [], folders: [] }
});

const emit = defineEmits(['update:selected']);

const toggleFile = (id) => {
    const files = [...props.selected.files];
    const index = files.indexOf(id);
    if (index > -1) files.splice(index, 1);
    else files.push(id);
    emit('update:selected', { ...props.selected, files });
};

const enterFolder = (id) => {
    router.get(route('admin.media.index'), { folder_id: id });
};

const getThumbnail = (item) => {
    return item.mime_type.startsWith('image/') ? item.thumbnail_url : null;
};

const isFileSelected = (id) => props.selected.files.includes(id);

const formatSize = (bytes) => {
    const s = ['B', 'KB', 'MB', 'GB'];
    const e = Math.floor(Math.log(bytes) / Math.log(1024));
    return (bytes / Math.pow(1024, e)).toFixed(1) + " " + s[e];
};
</script>

<template>
    <div class="space-y-8">
        <!-- Folders -->
        <section v-if="folders.length > 0">
            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Folders</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-4">
                <div 
                    v-for="folder in folders" 
                    :key="folder.id"
                    @dblclick="enterFolder(folder.id)"
                    class="group p-4 bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-md hover:border-indigo-200 cursor-pointer transition-all flex flex-col items-center text-center"
                >
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-500 rounded-xl flex items-center justify-center mb-3 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" /></svg>
                    </div>
                    <span class="text-sm font-bold text-slate-700 truncate w-full">{{ folder.name }}</span>
                    <span class="text-[10px] text-slate-400 font-medium">{{ folder.media_count }} items</span>
                </div>
            </div>
        </section>

        <!-- Media Files -->
        <section>
            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Files</h3>
            <div v-if="media.length === 0" class="h-64 flex flex-col items-center justify-center bg-white border border-dashed border-slate-200 rounded-3xl text-slate-400">
                <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                <p class="font-bold">No files found in this folder</p>
            </div>
            
            <div v-else class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-4">
                <div 
                    v-for="item in media" 
                    :key="item.id"
                    @click="toggleFile(item.id)"
                    :class="['group relative bg-white border rounded-2xl shadow-sm cursor-pointer transition-all overflow-hidden', isFileSelected(item.id) ? 'border-indigo-600 ring-2 ring-indigo-500 ring-offset-2' : 'border-slate-100 hover:border-indigo-200']"
                >
                    <!-- Thumbnail Container -->
                    <div class="aspect-square bg-slate-100 flex items-center justify-center overflow-hidden">
                        <img v-if="getThumbnail(item)" :src="getThumbnail(item)" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" />
                        <div v-else class="flex flex-col items-center">
                            <span class="text-[10px] uppercase font-black px-2 py-1 bg-slate-200 text-slate-500 rounded mb-1">{{ item.mime_type.split('/')[1] }}</span>
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="p-3">
                        <p class="text-[11px] font-bold text-slate-700 truncate">{{ item.name }}</p>
                        <div class="flex justify-between items-center mt-1">
                            <span class="text-[9px] text-slate-400 uppercase font-black">{{ formatSize(item.size) }}</span>
                        </div>
                    </div>

                    <!-- Selection Indicator -->
                    <div v-if="isFileSelected(item.id)" class="absolute top-2 right-2 bg-indigo-600 text-white rounded-full p-1 shadow-lg">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7" /></svg>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
