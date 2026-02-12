<script setup>
import { computed } from 'vue';

const props = defineProps({
    attributes: Object,
    content: [Array, String]
});

const ids = computed(() => {
    if (!props.attributes.ids) return [];
    return props.attributes.ids.split(',').filter(id => id);
});

const columns = computed(() => Number(props.attributes.columns) || 3);

// In a real app, these would comes from an injected store or API if they aren't provided in attributes
// For this implementation, we assume the backend might have enriched the attributes or we fetch them here.
</script>

<template>
    <div 
        class="cms-gallery-vue grid gap-4 mb-8"
        :style="{ gridTemplateColumns: `repeat(${columns}, minmax(0, 1fr))` }"
    >
        <div 
            v-for="id in ids" 
            :key="id"
            class="aspect-square bg-slate-100 rounded-2xl overflow-hidden border border-slate-200 animate-pulse"
        >
            <!-- In a fully hydrated app, we'd have the real image data here -->
            <div class="w-full h-full flex items-center justify-center text-[10px] text-slate-400 font-bold uppercase">
                Media #{{ id }}
            </div>
        </div>
    </div>
</template>
