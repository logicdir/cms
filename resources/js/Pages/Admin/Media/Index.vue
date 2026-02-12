<script setup>
import { ref, computed } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import MediaGrid from '@/Components/Media/MediaGrid.vue';
import MediaUploader from '@/Components/Media/MediaUploader.vue';
import MediaModal from '@/Components/Media/MediaModal.vue';

const props = defineProps({
    content: Object,
    currentFolder: Object,
    filters: Object
});

const showUploader = ref(false);
const viewMode = ref('grid'); // grid or list
const selectedItems = ref({ files: [], folders: [] });

const handleUploadFinished = () => {
    showUploader.value = false;
    router.reload({ only: ['content'] });
};

const createFolder = () => {
    const name = prompt('Folder name:');
    if (name) {
        router.post(route('admin.media.folders.store'), {
            name,
            parent_id: props.currentFolder?.id
        });
    }
};

const deleteSelected = () => {
    if (confirm(`Delete ${selectedItems.value.files.length} files?`)) {
        router.delete(route('admin.media.bulk-delete'), {
            data: { ids: selectedItems.value.files },
            onSuccess: () => selectedItems.value.files = []
        });
    }
};
</script>

<template>
    <AdminLayout>
        <Head title="Media Library" />

        <div class="flex flex-col h-full bg-slate-50">
            <!-- Header -->
            <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between sticky top-0 z-10">
                <div class="flex items-center gap-4">
                    <h1 class="text-xl font-bold text-slate-900">Media Library</h1>
                    <div class="h-6 w-px bg-slate-200"></div>
                    <nav class="flex items-center text-sm font-medium text-slate-500">
                        <button @click="router.get(route('admin.media.index'))" class="hover:text-indigo-600 transition-colors">Root</button>
                        <template v-if="currentFolder">
                            <span class="mx-2 text-slate-300">/</span>
                            <span class="text-slate-900">{{ currentFolder.name }}</span>
                        </template>
                    </nav>
                </div>

                <div class="flex items-center gap-2">
                    <button @click="createFolder" class="p-2 text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" /></svg>
                    </button>
                    <button @click="showUploader = true" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-bold shadow-sm hover:bg-indigo-700 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Upload
                    </button>
                </div>
            </header>

            <!-- Library Content -->
            <main class="flex-1 overflow-auto p-6">
                <div v-if="selectedItems.files.length > 0" class="mb-4 p-3 bg-indigo-50 border border-indigo-100 rounded-xl flex items-center justify-between">
                    <span class="text-sm text-indigo-700 font-medium">{{ selectedItems.files.length }} items selected</span>
                    <div class="flex gap-2">
                        <button class="text-xs font-bold text-slate-600 hover:text-slate-900">Move</button>
                        <button @click="deleteSelected" class="text-xs font-bold text-red-600 hover:text-red-800">Delete</button>
                    </div>
                </div>

                <MediaGrid 
                    :folders="content.folders" 
                    :media="content.media"
                    v-model:selected="selectedItems"
                />
            </main>
        </div>

        <!-- Uploader Modal -->
        <div v-if="showUploader" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl w-full max-w-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
                <MediaUploader 
                    :folder-id="currentFolder?.id" 
                    @close="showUploader = false" 
                    @finished="handleUploadFinished"
                />
            </div>
        </div>
    </AdminLayout>
</template>
