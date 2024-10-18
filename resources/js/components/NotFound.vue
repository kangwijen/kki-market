<template>
    <div class="flex items-center justify-center min-h-screen bg-base-300">
        <div class="text-center">
            <h1 class="font-black text-red-500 text-9xl">404</h1>
    
            <p class="text-2xl font-bold tracking-tight text-white sm:text-4xl">Uh-oh!</p>
    
            <p class="mt-4 text-white">We can't find that page.</p>
    
            <router-link :to="{ name: 'index' }" class="mt-6 btn btn-primary">
            Go Back Home
            </router-link>
        </div>
    
        <Popup v-model:show="popupShow" :title="popupTitle" :message="popupMessage" :type="popupType" />
    </div>
</template>

<script>
import { onMounted, ref } from 'vue';
import Popup from './Popup.vue';

export default {
    components: { Popup },
    setup() {
        const popupShow = ref(false)
        const popupTitle = ref('')
        const popupMessage = ref('')
        const popupType = ref('info')

        const showPopup = (title, message, type = 'info') => {
            popupTitle.value = title
            popupMessage.value = message
            popupType.value = type
            popupShow.value = true
        }

        onMounted(() => {
            showPopup('Page Not Found', 'The page you are looking for does not exist.', 'error')
        })

        return {
            popupShow,
            popupTitle,
            popupMessage,
            popupType,
        }
    },
};
</script>