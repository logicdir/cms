<script setup>
defineProps({
    currentStep: {
        type: Number,
        default: 1
    },
    totalSteps: {
        type: Number,
        default: 5
    }
});

const steps = [
    'Requirements',
    'Database',
    'Migration',
    'Account',
    'Complete'
];
</script>

<template>
    <div class="mb-8">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-semibold uppercase text-indigo-600">
                Step {{ currentStep }} of {{ totalSteps }}
            </span>
            <span class="text-xs font-semibold uppercase text-indigo-600">
                {{ steps[currentStep - 1] }}
            </span>
        </div>
        <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-indigo-200">
            <div
                :style="{ width: (currentStep / totalSteps) * 100 + '%' }"
                class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-indigo-600 transition-all duration-500"
            ></div>
        </div>
        <div class="flex justify-between">
            <template v-for="(step, index) in steps" :key="index">
                <div class="flex flex-col items-center">
                    <div
                        :class="[
                            'w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-bold',
                            currentStep > index + 1 ? 'bg-green-500 text-white' : (currentStep === index + 1 ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-400')
                        ]"
                    >
                        <span v-if="currentStep > index + 1">✓</span>
                        <span v-else>{{ index + 1 }}</span>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>
