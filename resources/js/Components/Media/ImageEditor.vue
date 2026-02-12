<script setup>
import { ref, onMounted } from 'vue';

const props = defineProps({
    media: Object
});

const emit = defineEmits(['save', 'cancel']);

const canvas = ref(null);
const ctx = ref(null);
const img = ref(null);
const cropActive = ref(false);

const filters = ref({
    brightness: 100,
    contrast: 100,
    grayscale: 0,
    sepia: 0
});

const rotation = ref(0);
const cropRect = ref({ x: 0, y: 0, width: 0, height: 0 });
const aspectRatios = [
    { label: 'Free', value: 0 },
    { label: '1:1', value: 1 },
    { label: '16:9', value: 16/9 },
    { label: '4:3', value: 4/3 }
];
const selectedRatio = ref(0);

onMounted(() => {
    img.value = new Image();
    img.value.crossOrigin = 'anonymous';
    img.value.src = props.media.full_url;
    img.value.onload = () => {
        render();
    };
});

const render = () => {
    const cvs = canvas.value;
    const c = cvs.getContext('2d');
    
    // Set canvas dimensions based on rotation
    if (rotation.value % 180 === 0) {
        cvs.width = img.value.width;
        cvs.height = img.value.height;
    } else {
        cvs.width = img.value.height;
        cvs.height = img.value.width;
    }

    c.clearRect(0, 0, cvs.width, cvs.height);
    
    // Apply filters
    c.filter = `brightness(${filters.value.brightness}%) contrast(${filters.value.contrast}%) grayscale(${filters.value.grayscale}%) sepia(${filters.value.sepia}%)`;
    
    // Apply rotation
    c.save();
    c.translate(cvs.width / 2, cvs.height / 2);
    c.rotate((rotation.value * Math.PI) / 180);
    c.drawImage(img.value, -img.value.width / 2, -img.value.height / 2);
    c.restore();

    if (cropActive.value) {
        drawCropOverlay(c);
    }
};

const drawCropOverlay = (c) => {
    c.fillStyle = 'rgba(0, 0, 0, 0.5)';
    c.fillRect(0, 0, canvas.value.width, canvas.value.height);
    
    // Default crop rect if not set
    if (cropRect.value.width === 0) {
        const size = Math.min(canvas.value.width, canvas.value.height) * 0.8;
        cropRect.value = {
            x: (canvas.value.width - size) / 2,
            y: (canvas.value.height - size) / 2,
            width: size,
            height: selectedRatio.value ? size / selectedRatio.value : size
        };
    }

    c.clearRect(cropRect.value.x, cropRect.value.y, cropRect.value.width, cropRect.value.height);
    c.strokeStyle = '#6366f1';
    c.lineWidth = 2;
    c.strokeRect(cropRect.value.x, cropRect.value.y, cropRect.value.width, cropRect.value.height);
};

const rotate = () => {
    rotation.value = (rotation.value + 90) % 360;
    render();
};

const toggleCrop = () => {
    cropActive.value = !cropActive.value;
    render();
};

const save = () => {
    const finalCanvas = document.createElement('canvas');
    const fctx = finalCanvas.getContext('2d');

    if (cropActive.value) {
        finalCanvas.width = cropRect.value.width;
        finalCanvas.height = cropRect.value.height;
        fctx.drawImage(canvas.value, 
            cropRect.value.x, cropRect.value.y, cropRect.value.width, cropRect.value.height,
            0, 0, cropRect.value.width, cropRect.value.height
        );
    } else {
        finalCanvas.width = canvas.value.width;
        finalCanvas.height = canvas.value.height;
        fctx.drawImage(canvas.value, 0, 0);
    }

    const dataUrl = finalCanvas.toDataURL('image/png');
    emit('save', dataUrl);
};
</script>

<template>
    <div class="flex flex-col h-[700px] bg-slate-900 overflow-hidden rounded-3xl shadow-2xl">
        <!-- Editor Header -->
        <header class="p-6 border-b border-white/10 flex justify-between items-center bg-slate-800">
            <h3 class="text-white font-bold">Edit Image: {{ media.name }}</h3>
            <div class="flex items-center gap-3">
                <button 
                    @click="toggleCrop" 
                    :class="['p-2 rounded-lg transition-colors', cropActive ? 'bg-indigo-600 text-white' : 'text-white/70 hover:text-white bg-white/5']"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h8M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2" /></svg>
                </button>
                <button @click="rotate" class="p-2 text-white/70 hover:text-white bg-white/5 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                </button>
            </div>
        </header>

        <!-- Canvas Area -->
        <div class="flex-1 overflow-auto flex items-center justify-center p-8 bg-[radial-gradient(#ffffff10_1px,transparent_1px)] [background-size:20px_20px]">
            <canvas ref="canvas" class="max-w-full max-h-full shadow-2xl rounded-lg"></canvas>
        </div>

        <!-- Controls Area -->
        <footer class="p-8 bg-slate-800 border-t border-white/10 text-white">
            <div v-if="cropActive" class="flex gap-4 mb-8">
                <button 
                    v-for="ratio in aspectRatios" 
                    :key="ratio.label"
                    @click="selectedRatio = ratio.value; cropRect.width = 0; render()"
                    :class="['px-4 py-2 rounded-lg text-xs font-bold transition-all', selectedRatio === ratio.value ? 'bg-indigo-600' : 'bg-white/5 hover:bg-white/10']"
                >
                    {{ ratio.label }}
                </button>
            </div>

            <div v-else class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                <div v-for="(val, key) in filters" :key="key" class="space-y-2">
                    <div class="flex justify-between text-[10px] font-black uppercase tracking-widest text-white/50">
                        <span>{{ key }}</span>
                        <span>{{ val }}%</span>
                    </div>
                    <input type="range" v-model="filters[key]" min="0" max="200" @input="render" class="w-full accent-indigo-500" />
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <button @click="$emit('cancel')" class="px-6 py-2 text-sm font-bold text-white/50 hover:text-white transition-colors">Cancel</button>
                <button @click="save" class="px-10 py-3 bg-indigo-600 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-indigo-900/40 hover:bg-indigo-700 transition-all">Save Changes</button>
            </div>
        </footer>
    </div>
</template>
