<script setup>
import { computed } from 'vue';

const props = defineProps({
    attributes: Object,
    content: [Array, String]
});

const styles = {
    primary: 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-indigo-200',
    secondary: 'bg-slate-200 text-slate-900 hover:bg-slate-300 shadow-slate-200',
    outline: 'border-2 border-indigo-600 text-indigo-600 hover:bg-indigo-50 shadow-none',
    danger: 'bg-red-600 text-white hover:bg-red-700 shadow-red-200'
};

const sizes = {
    sm: 'px-4 py-2 text-xs',
    md: 'px-6 py-3 text-sm',
    lg: 'px-10 py-5 text-base'
};

const buttonClass = computed(() => {
    return [
        'inline-flex items-center gap-2 font-black rounded-2xl transition-all shadow-xl hover:-translate-y-0.5 active:translate-y-0 uppercase tracking-widest',
        styles[props.attributes.style] || styles.primary,
        sizes[props.attributes.size] || sizes.md
    ].join(' ');
});
</script>

<template>
    <div class="mb-4">
        <a :href="attributes.url" :target="attributes.target" :class="buttonClass">
            <span v-if="attributes.icon" :class="`icon-${attributes.icon}`"></span>
            {{ attributes.text }}
        </a>
    </div>
</template>
