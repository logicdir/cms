<script setup>
defineProps({
    title: String,
    items: Array
});
</script>

<template>
    <div class="mb-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wider">
            {{ title }}
        </h3>
        <ul class="divide-y divide-gray-100 bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <li v-for="(item, index) in items" :key="index" class="px-4 py-3 flex items-center justify-between">
                <div class="flex flex-col">
                    <span class="text-sm font-medium text-gray-900">{{ item.name || item.path || 'PHP Version' }}</span>
                    <span v-if="item.required" class="text-xs text-gray-500">
                        Required: {{ item.required }} <span v-if="item.version">(Current: {{ item.version }})</span>
                    </span>
                    <span v-if="item.value" class="text-xs text-gray-500">
                        Current: {{ item.value }}
                    </span>
                </div>
                <div>
                    <span v-if="item.passed" class="text-green-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </span>
                    <span v-else class="text-red-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </span>
                </div>
            </li>
        </ul>
    </div>
</template>
