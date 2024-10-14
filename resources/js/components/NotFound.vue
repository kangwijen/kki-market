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
    
        <ErrorPopup :errors="errors" v-if="showErrorPopup" @close="closeErrorPopup" />
    </div>
</template>

<script>
import { ref } from 'vue';

export default {
    setup() {
        const errors = ref(['Page not found. Please check the URL and try again.']);
        const showErrorPopup = ref(true);

        const closeErrorPopup = () => {
            showErrorPopup.value = false;
        };

        return {
            errors,
            showErrorPopup,
            closeErrorPopup,
        };
    },
};
</script>