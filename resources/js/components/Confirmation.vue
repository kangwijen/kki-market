<template>
    <Teleport to="body">
        <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-black opacity-50" @click="cancel"></div>
            <div class="relative p-6 bg-white rounded-lg shadow-xl">
            <h2 class="mb-4 text-xl font-bold text-gray-800">{{ title }}</h2>
            <p class="mb-4 text-sm text-gray-700">{{ message }}</p>
            <div class="flex justify-end space-x-2">
                <button @click="cancel" class="px-4 py-2 text-gray-600 bg-gray-200 rounded hover:bg-gray-300">
                Cancel
                </button>
                <button @click="confirm" class="px-4 py-2 text-white bg-red-600 rounded hover:bg-red-700">
                Confirm
                </button>
            </div>
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
    },
    emits: ['update:show', 'confirm', 'cancel'],
    methods: {
        confirm() {
            this.$emit('confirm')
            this.$emit('update:show', false)
        },
        cancel() {
            this.$emit('cancel')
            this.$emit('update:show', false)
        }
    }
})
</script>