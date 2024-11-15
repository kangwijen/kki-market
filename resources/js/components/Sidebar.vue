<template>
    <div class="p-4 border-r md:w-64 bg-base-200">
        <h1 class="mb-6 text-2xl font-bold text-primary">{{ title }}</h1>
        <div class="space-y-4">
            <a v-for="tab in tabs" :key="tab.name" 
               class="justify-start btn" 
               :class="{ 'bg-base-300': activeTab === tab.name }"
               @click="$emit('changeTab', tab.name)">
                {{ tab.label }}
            </a>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        title: {
            type: String,
            required: true
        },
        tabs: {
            type: Array,
            required: true
        },
        activeTab: String
    }
};
</script>