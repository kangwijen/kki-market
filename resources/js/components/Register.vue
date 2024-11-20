<template>
    <div class="min-h-screen hero bg-base-200">
        <div class="flex-col w-full max-w-md hero-content">
            <div class="w-full shadow-2xl card bg-base-100">
                <div class="card-body">
                    <h1 class="justify-center text-3xl card-title">Register</h1>
                    <form @submit.prevent="handleSubmit">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Email</span>
                            </label>
                            <input type="email" v-model="email" @input="validateEmail" 
                                   placeholder="Email" :class="['input input-bordered', {'input-error': emailError}]" required/>
                            <label class="label" v-if="emailError">
                                <span class="label-text-alt text-error">{{ emailError }}</span>
                            </label>
                        </div>
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Username</span>
                            </label>
                            <input type="text" v-model="username" @input="validateUsername" 
                                   placeholder="Username" :class="['input input-bordered', {'input-error': usernameError}]" required/>
                            <label class="label" v-if="usernameError">
                                <span class="label-text-alt text-error">{{ usernameError }}</span>
                            </label>
                        </div>
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Password</span>
                            </label>
                            <input type="password" v-model="password" @input="validatePassword" 
                                   placeholder="Password" :class="['input input-bordered', {'input-error': passwordError}]" required/>
                            <label class="label" v-if="passwordError">
                                <span class="label-text-alt text-error">{{ passwordError }}</span>
                            </label>
                        </div>
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Confirm Password</span>
                            </label>
                            <input type="password" v-model="passwordConfirmation" 
                                   placeholder="Confirm Password" class="input input-bordered" required/>
                            <label class="label" v-if="passwordError">
                                <span class="label-text-alt text-error">{{ passwordError }}</span>
                            </label>
                        </div>
                        <div class="mt-6 form-control">
                            <button type="submit" class="btn btn-primary">Register</button>
                        </div>
                        <div class="mt-4 form-control">
                            <label class="justify-center label">
                                <span class="label-text-alt">Have an account?
                                    <router-link to="/login" class="link link-primary">Login here</router-link>
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
import Popup from './Popup.vue';
import DOMPurify from 'dompurify';

export default {
    components: { Popup },
    setup() {
        const router = useRouter();
        const email = ref('');
        const username = ref('');
        const password = ref('');
        const passwordConfirmation = ref('');
        const popupShow = ref(false);
        const popupTitle = ref('');
        const popupMessage = ref('');
        const popupType = ref('info');
        const emailError = ref('');
        const usernameError = ref('');
        const passwordError = ref('');

        const showPopup = (title, message, type = 'info') => {
            popupTitle.value = title;
            popupMessage.value = message;
            popupType.value = type;
            popupShow.value = true;
        };

        const validateInput = (input) => {
            return DOMPurify.sanitize(input.trim());
        };

        const validateEmail = () => {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email.value) {
                emailError.value = 'Email is required';
            } else if (!emailRegex.test(email.value)) {
                emailError.value = 'Please enter a valid email address';
            } else {
                emailError.value = '';
            }
        };

        const validateUsername = () => {
            const usernameRegex = /^[a-zA-Z0-9_]+$/;
            if (!username.value) {
                usernameError.value = 'Username is required';
            } else if (!usernameRegex.test(username.value)) {
                usernameError.value = 'Username can only contain letters, numbers, and underscores';
            } else {
                usernameError.value = '';
            }
        };

        const validatePassword = () => {
            const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/;
            console.log(password.value.length);
            if (!password.value) {
                passwordError.value = 'Password is required';
            } else if (password.value.length < 8) {
                passwordError.value = 'Password must be at least 8 characters long';
            } else if (!passwordRegex.test(password.value)) {
                passwordError.value = 'Password must contain at least one uppercase letter, one lowercase letter, and one number';
            } else if (password.value !== passwordConfirmation.value) {
                passwordError.value = 'Passwords do not match';
            } else {
                passwordError.value = '';
            }
        };

        const handleSubmit = async () => {
            validateEmail();
            validateUsername();
            validatePassword();

            if (emailError.value || usernameError.value || passwordError.value) {
                showPopup('Error', 'Please fix the validation errors', 'error');
                return;
            }

            try {
                const response = await axios.post('/register', {
                    email: validateInput(email.value),
                    username: validateInput(username.value),
                    password: password.value,
                    password_confirmation: passwordConfirmation.value,
                });
                
                showPopup('Success', 'Registration successful! Please log in.', 'success');
                router.push('/login');
            } catch (error) {
                if (error.response?.status === 422) {
                    showPopup('Error', 'Invalid input. Please check your data.', 'error');
                } else {
                    showPopup('Error', 
                        error.response?.data?.error || 'Failed to register. Please try again.',
                        'error'
                    );
                }
            }
        };

        return {
            email,
            username,
            password,
            passwordConfirmation,
            popupShow,
            popupTitle,
            popupMessage,
            popupType,
            handleSubmit,
            emailError,
            usernameError,
            passwordError,
            validateEmail,
            validateUsername,
            validatePassword,
        };
    },
};
</script>