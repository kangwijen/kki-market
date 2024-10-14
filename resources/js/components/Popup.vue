<template>
    <div v-if="messages.length > 0" class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black opacity-50"></div>
        <div class="relative p-6 bg-white rounded-lg shadow-xl">
            <h2 class="mb-4 text-xl font-bold" :class="popupTitleClass">{{ popupTitle }}</h2>
            <ul class="mb-4 text-sm text-gray-700">
                <li v-for="(message, index) in messages" :key="index">{{ message }}</li>
            </ul>
            <button @click="closePopup" :class="popupButtonClass">
                Close
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        messages: {
            type: Array,
            default: () => []
        },
        type: {
            type: String,
            default: 'error',
        }
    },
    computed: {
        popupTitle() {
            return this.type === 'success' ? 'Success' : 'Error';
        },
        popupTitleClass() {
            return this.type === 'success' ? 'text-green-600' : 'text-red-600';
        },
        popupButtonClass() {
            return this.type === 'success'
                ? 'px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700'
                : 'px-4 py-2 text-white bg-red-600 rounded hover:bg-red-700';
        }
    },
    methods: {
        closePopup() {
            this.$emit('close');
        }
    }
}
</script>
