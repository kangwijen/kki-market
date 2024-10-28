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
            <div v-if="isAdmin">
                <button class="btn btn-secondary" @click="admin">Admin</button>
            </div>
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
        const isAdmin = ref(false);
        const router = useRouter();

        const checkAuth = async () => {
            try {
                if (isAuthenticated.value) return;
                if (isAdmin.value) return;
                const response = await axios.get('/user');
                isAuthenticated.value = response.data.authenticated;
                isAdmin.value = response.data.user?.isAdmin || false;
                window.dispatchEvent(new Event('login'));
            } catch (error) {
                console.error('Error checking authentication:', error);
                isAuthenticated.value = false;
                isAdmin.value = false;
            }
        };

        const logout = async () => {
            try {
                await axios.post('/api/logout');
                isAuthenticated.value = false;
                isAdmin.value = false;
                window.dispatchEvent(new Event('logout'));
                router.push('/');
            } catch (error) {
                console.error('Error logging out:', error);
            }
        };

        const admin = async () => {
            try {
                await axios.get('/admin');
                router.push('/admin');
            } catch (error) {
                console.error('Error accessing admin:', error);
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
            isAdmin,
            logout,
            admin
        };
    }
}
</script>
