<script setup>
const props = defineProps({
    size: { type: String, default: '300x250' }
});

// Extract dimensions for placeholder
const dimensions = props.size.split('x');
const width = dimensions[0] || '100%';
const height = dimensions[1] || '250px';
</script>

<template>
    <div 
        class="bg-slate-100 animate-pulse rounded-2xl border border-slate-200 flex items-center justify-center overflow-hidden"
        :style="{ width: width === 'responsive' ? '100%' : width + 'px', height: height + 'px' }"
    >
        <span class="text-[10px] font-black uppercase tracking-widest text-slate-300">
            Ad Loading...
        </span>
    </div>
</template>
