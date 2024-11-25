<template>
    <Teleport to="body">
        <div v-if="show" class="fixed inset-0 flex items-center justify-center z-100">
            <div class="absolute inset-0 bg-black opacity-50" @click="close"></div>
            <div class="relative p-6 bg-white rounded-lg shadow-xl">
            <h2 class="mb-4 text-xl font-bold" :class="popupTitleClass">{{ title }}</h2>
            <p class="mb-4 text-sm text-gray-700">{{ message }}</p>
            <button @click="close" :class="popupButtonClass">
                Close
            </button>
            </div>
        </div>
    </Teleport>
</template>

<script>
import { defineComponent } from 'vue'

export default defineComponent({
    props: {
        show: Boolean,
        title: String,
        message: String,
        type: {
        type: String,
        default: 'error',
        validator: (value) => ['success', 'error', 'info'].includes(value)
        }
    },
    emits: ['update:show'],
    computed: {
        popupTitleClass() {
        return {
            'text-green-600': this.type === 'success',
            'text-red-600': this.type === 'error',
            'text-blue-600': this.type === 'info'
        }
        },
        popupButtonClass() {
        return {
            'px-4 py-2 text-white rounded hover:bg-opacity-80': true,
            'bg-green-600': this.type === 'success',
            'bg-red-600': this.type === 'error',
            'bg-blue-600': this.type === 'info'
        }
        }
    },
    methods: {
        close() {
        this.$emit('update:show', false)
        }
    }
})
</script>