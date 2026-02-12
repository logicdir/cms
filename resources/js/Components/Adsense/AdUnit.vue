<script setup>
import { ref, onMounted } from 'vue';
import AdPlaceholder from './AdPlaceholder.vue';

const props = defineProps({
    adUnitId: Number,
    code: String,
    type: String,
    size: String,
    responsive: Boolean
});

const isLoaded = ref(false);
const isBlocked = ref(false);

onMounted(() => {
    // Check for adblocker after a short delay
    setTimeout(() => {
        const adEl = document.getElementById(`ad-unit-${props.adUnitId}`);
        if (adEl && adEl.offsetHeight === 0) {
            isBlocked.value = true;
        }
        isLoaded.value = true;
    }, 1000);

    // If AdSense, we need to push the ad
    if (props.type === 'adsense') {
        try {
            (window.adsbygoogle = window.adsbygoogle || []).push({});
        } catch (e) {
            console.error('AdSense failed to load:', e);
        }
    }
});
</script>

<template>
    <div :id="`ad-unit-${adUnitId}`" class="cms-ad-wrapper my-8 flex justify-center">
        <!-- Ad Content -->
        <div v-if="!isBlocked" v-html="code" class="w-full"></div>

        <!-- Fallback if Blocked -->
        <div v-else class="bg-slate-50 border border-slate-200 rounded-2xl p-6 text-center max-w-lg">
            <p class="text-sm font-bold text-slate-500 mb-2">Advertisement</p>
            <p class="text-xs text-slate-400 italic">Please support us by disabling your AdBlocker.</p>
        </div>

        <!-- Skeleton Loader -->
        <AdPlaceholder v-if="!isLoaded" :size="size" />
    </div>
</template>
