<template>
    <div class="flex items-center justify-center min-h-screen p-4 bg-base-300">
        <div class="w-full max-w-md p-6 space-y-6 rounded-lg shadow-lg sm:p-8 bg-base-100">
            <h1 class="text-3xl font-bold text-center sm:text-4xl text-primary">Login</h1>
            <form @submit.prevent="handleSubmit" class="space-y-4 sm:space-y-6">
                <div class="form-control">
                    <label for="email" class="label">
                    <span class="label-text">Email</span>
                    </label>
                    <input
                    type="email"
                    id="email"
                    v-model="email"
                    placeholder="Email"
                    class="w-full input input-bordered"
                    required
                    />
                </div>
                <div class="form-control">
                    <label for="password" class="label">
                    <span class="label-text">Password</span>
                    </label>
                    <input
                    type="password"
                    id="password"
                    v-model="password"
                    placeholder="Password"
                    class="w-full input input-bordered"
                    required
                    />
                </div>
                <div class="text-center">
                    <p class="text-sm">
                    No account?
                    <router-link to="/register" class="text-primary hover:underline">Create one here</router-link>.
                    </p>
                </div>
                <div class="mt-6 form-control">
                    <button type="submit" class="w-full btn btn-primary">Login</button>
                </div>
            </form>
        </div>
        <Popup v-model:show="popupShow" :title="popupTitle" :message="popupMessage" :type="popupType" />
    </div>
</template>

<script>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import Popup from './Popup.vue'
import DOMPurify from 'dompurify';

export default {
    components: { Popup },
    setup() {
        const router = useRouter();
        const email = ref('');
        const password = ref('');
        const popupShow = ref(false);
        const popupTitle = ref('');
        const popupMessage = ref('');
        const popupType = ref('info');

        const showPopup = (title, message, type = 'info') => {
            popupTitle.value = title;
            popupMessage.value = message;
            popupType.value = type;
            popupShow.value = true;
        };

        const sanitizeInput = (input) => {
            return DOMPurify.sanitize(input.trim());
        };

        const handleSubmit = async () => {
            try {
                const sanitizedEmail = sanitizeInput(email.value);
                const response = await axios.post('/login', {
                    email: sanitizedEmail,
                    password: password.value, // Don't sanitize passwords
                });
                
                if (response.data.token) {
                    localStorage.setItem('token', response.data.token);
                }
                
                window.dispatchEvent(new Event('login'));
                router.push('/search');
            } catch (error) {
                showPopup('Error', 
                    error.response?.status === 429 ? 'Too many login attempts. Please try again later.' :
                    error.response?.data?.message || 'Failed to login. Please try again.',
                    'error'
                );
            }
        };

        return {
            email,
            password,
            popupShow,
            popupTitle,
            popupMessage,
            popupType,
            handleSubmit
        };
    },
};
</script>