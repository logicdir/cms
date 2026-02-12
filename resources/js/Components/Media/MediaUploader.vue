<script setup>
import { ref } from 'vue';
import axios from 'axios';

const props = defineProps({
    folderId: Number
});

const emit = defineEmits(['close', 'finished']);

const dragging = ref(false);
const uploads = ref([]);
const fileInput = ref(null);

const handleFiles = (files) => {
    Array.from(files).forEach(file => {
        const upload = {
            id: Math.random().toString(36).substr(2, 9),
            name: file.name,
            size: file.size,
            progress: 0,
            status: 'uploading',
            error: null
        };
        uploads.value.push(upload);
        
        if (file.size > 1024 * 1024 * 5) { // If > 5MB, used chunked
            uploadChunked(file, upload);
        } else {
            uploadSingle(file, upload);
        }
    });
};

const uploadSingle = async (file, upload) => {
    const formData = new FormData();
    formData.append('file', file);
    if (props.folderId) formData.append('folder_id', props.folderId);

    try {
        await axios.post(route('admin.media.upload'), formData, {
            onUploadProgress: (progressEvent) => {
                upload.progress = Math.round((progressEvent.loaded * 100) / progressEvent.total);
            }
        });
        upload.status = 'success';
        upload.progress = 100;
        checkAllFinished();
    } catch (e) {
        upload.status = 'error';
        upload.error = e.response?.data?.message || 'Upload failed';
    }
};

const uploadChunked = async (file, upload) => {
    const chunkSize = 1024 * 1024 * 2; // 2MB chunks
    const totalChunks = Math.ceil(file.size / chunkSize);
    const identifier = Math.random().toString(36).substr(2, 9);

    for (let i = 0; i < totalChunks; i++) {
        const start = i * chunkSize;
        const end = Math.min(start + chunkSize, file.size);
        const chunk = file.slice(start, end);

        const formData = new FormData();
        formData.append('file', chunk);
        formData.append('chunk_index', i);
        formData.append('total_chunks', totalChunks);
        formData.append('identifier', identifier);
        formData.append('original_name', file.name);
        if (props.folderId) formData.append('folder_id', props.folderId);

        try {
            const response = await axios.post(route('admin.media.upload.chunk'), formData);
            upload.progress = Math.round(((i + 1) * 100) / totalChunks);
            
            if (response.data.completed) {
                upload.status = 'success';
                checkAllFinished();
            }
        } catch (e) {
            upload.status = 'error';
            upload.error = 'Chunk upload failed';
            break;
        }
    }
};

const checkAllFinished = () => {
    if (uploads.value.every(u => u.status === 'success' || u.status === 'error')) {
        setTimeout(() => emit('finished'), 1000);
    }
};

const formatSize = (bytes) => {
    const s = ['bytes', 'KB', 'MB', 'GB'];
    const e = Math.floor(Math.log(bytes) / Math.log(1024));
    return (bytes / Math.pow(1024, e)).toFixed(2) + " " + s[e];
};
</script>

<template>
    <div class="flex flex-col h-[500px]">
        <div class="p-6 border-b flex justify-between items-center">
            <h2 class="font-bold text-lg">Upload Files</h2>
            <button @click="$emit('close')" class="text-slate-400 hover:text-slate-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <div class="p-6 flex-1 overflow-auto">
            <!-- Dropzone -->
            <div 
                v-if="uploads.length === 0"
                @dragover.prevent="dragging = true"
                @dragleave.prevent="dragging = false"
                @drop.prevent="dragging = false; handleFiles($event.dataTransfer.files)"
                @click="fileInput.click()"
                :class="['border-2 border-dashed rounded-2xl h-64 flex flex-col items-center justify-center cursor-pointer transition-all', dragging ? 'border-indigo-500 bg-indigo-50' : 'border-slate-200 hover:border-indigo-400 hover:bg-slate-50']"
            >
                <input ref="fileInput" type="file" multiple class="hidden" @change="handleFiles($event.target.files)" />
                <div class="p-4 bg-indigo-100 text-indigo-600 rounded-full mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                </div>
                <p class="font-bold text-slate-700">Drop files here or click to browse</p>
                <p class="text-xs text-slate-400 mt-2">Max file size: 10MB</p>
            </div>

            <!-- Upload List -->
            <div v-else class="space-y-3">
                <div v-for="upload in uploads" :key="upload.id" class="p-4 bg-slate-50 rounded-xl border border-slate-100 flex items-center gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-bold text-slate-700 truncate">{{ upload.name }}</span>
                            <span class="text-xs text-slate-400">{{ upload.status === 'success' ? 'Finished' : upload.progress + '%' }}</span>
                        </div>
                        <div class="w-full bg-slate-200 h-1.5 rounded-full overflow-hidden">
                            <div 
                                :class="['h-full transition-all duration-300', upload.status === 'error' ? 'bg-red-500' : 'bg-indigo-600']"
                                :style="{ width: upload.progress + '%' }"
                            ></div>
                        </div>
                        <p v-if="upload.error" class="text-[10px] text-red-500 mt-1 font-medium">{{ upload.error }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6 bg-slate-50 border-t flex justify-end">
            <button @click="$emit('close')" class="px-6 py-2 text-sm font-bold text-slate-600">Cancel</button>
        </div>
    </div>
</template>
