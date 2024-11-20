<template>
    <div class="min-h-screen hero bg-base-200">
        <div class="flex-col w-full max-w-md hero-content">
            <div class="w-full shadow-2xl card bg-base-100">
                <div class="card-body">
                    <h1 class="justify-center text-3xl card-title">Login</h1>
                    <form @submit.prevent="handleSubmit">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Email</span>
                            </label>
                            <input type="email" v-model="email" placeholder="Email" 
                                   class="input input-bordered" required />
                        </div>
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Password</span>
                            </label>
                            <input type="password" v-model="password" placeholder="Password" 
                                   class="input input-bordered" required />
                        </div>
                        <div class="mt-6 form-control">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                        <div class="mt-4 form-control">
                            <label class="justify-center label">
                                <span class="label-text-alt">No account?
                                    <router-link to="/register" class="link link-primary">Create one here</router-link>
                                </span>
                            </label>
                        </div>
                    </form>
                </div>
            </div>
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