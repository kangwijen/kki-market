<template>
    <nav class="navbar bg-base-100">
        <div class="flex-1">
            <router-link to="/" class="text-xl normal-case btn btn-ghost">KKI-Market</router-link>
        </div>

        <div class="flex-none" v-if="!isAuthenticated">
            <router-link to="/login" class="btn btn-primary">Login</router-link>
        </div>
        
        <div class="flex items-center space-x-4" v-if="isAuthenticated">
            <router-link to="/cart" class="btn btn-neutral">
                Cart
                <span class="ml-1 badge badge-secondary" id="cart-count">0</span>
            </router-link>
            <button class="btn btn-secondary" @click="logout">Logout</button>
        </div>
    </nav>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

export default {
    name: 'Navbar',
    setup() {
        const isAuthenticated = ref(false);
        const router = useRouter();

        const checkAuth = async () => {
            if (isAuthenticated.value) return;
            try {
                const response = await axios.get('/user');
                isAuthenticated.value = response.data.authenticated;
                window.dispatchEvent(new Event('login'));
            } catch (error) {
                console.error('Error checking authentication:', error);
                isAuthenticated.value = false;
            }
        };

        const logout = async () => {
            try {
                await axios.post('/logout');
                isAuthenticated.value = false;
                window.dispatchEvent(new Event('logout'));
                router.push('/');
            } catch (error) {
                console.error('Error logging out:', error);
            }
        };

        onMounted(() => {
            checkAuth();
        });

        window.addEventListener('login', () => {
            checkAuth();
        });

        return {
            isAuthenticated,
            logout
        };
    }
}
</script>
