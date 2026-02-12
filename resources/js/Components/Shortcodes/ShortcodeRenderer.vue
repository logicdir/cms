<script setup>
import { computed, defineAsyncComponent } from 'vue';

const props = defineProps({
    node: { type: [Object, Array], required: true }
});

// Map shortcodes to Vue components
const componentMap = {
    gallery: defineAsyncComponent(() => import('./GalleryShortcode.vue')),
    button: defineAsyncComponent(() => import('./ButtonShortcode.vue')),
    video: defineAsyncComponent(() => import('./VideoShortcode.vue'))
};

const isArray = computed(() => Array.isArray(props.node));
</script>

<template>
    <template v-if="isArray">
        <ShortcodeRenderer v-for="(child, index) in node" :key="index" :node="child" />
    </template>
    
    <template v-else>
        <template v-if="node.type === 'text'">
            <span v-html="node.content"></span>
        </template>
        
        <template v-else-if="node.type === 'shortcode'">
            <component 
                v-if="componentMap[node.tag]"
                :is="componentMap[node.tag]"
                :attributes="node.attributes"
                :content="node.content"
            />
            <div v-else class="cms-shortcode-unknown bg-amber-50 text-amber-600 p-2 rounded text-xs border border-amber-100 mb-2">
                Unknown shortcode: [{{ node.tag }}]
            </div>
        </template>
    </template>
</template>

<style scoped>
/* Scoped styles for rendering containers */
span :deep(p) { margin-bottom: 1rem; }
</style>
