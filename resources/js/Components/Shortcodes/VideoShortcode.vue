<script setup>
import { computed } from 'vue';

const props = defineProps({
    attributes: Object,
    content: [Array, String]
});

const isYoutube = computed(() => props.attributes.src?.includes('youtube.com') || props.attributes.src?.includes('youtu.be'));
const isVimeo = computed(() => props.attributes.src?.includes('vimeo.com'));

const embedUrl = computed(() => {
    if (isYoutube.value) {
        const id = props.attributes.src.match(/(?:v=|\/be\/)([a-zA-Z0-9_-]{11})/)?.[1];
        return `https://www.youtube.com/embed/${id}`;
    }
    if (isVimeo.value) {
        const id = props.attributes.src.match(/vimeo\.com\/(\d+)/)?.[1];
        return `https://player.vimeo.com/video/${id}`;
    }
    return null;
});
</script>

<template>
    <div class="cms-video-vue aspect-video mb-10 rounded-3xl overflow-hidden shadow-2xl bg-black group relative">
        <iframe 
            v-if="embedUrl"
            :src="embedUrl"
            class="w-full h-full"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen
        ></iframe>
        
        <video 
            v-else
            :src="attributes.src"
            :poster="attributes.poster"
            :autoplay="attributes.autoplay"
            :controls="attributes.controls"
            class="w-full h-full object-cover"
        ></video>
    </div>
</template>
