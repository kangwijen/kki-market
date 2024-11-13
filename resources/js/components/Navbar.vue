<template>
    <nav class="navbar bg-base-100">
        <div class="flex-1">
            <router-link to="/" class="text-xl normal-case btn btn-ghost">KKI-Market</router-link>
        </div>

        <div class="flex-none lg:hidden">
            <button @click="toggleMenu" class="btn btn-ghost">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>
        </div>

        <div class="flex-none hidden lg:flex" v-if="!isAuthenticated">
            <router-link to="/login" class="btn btn-primary">Login</router-link>
        </div>

        <div class="items-center hidden space-x-4 lg:flex" v-if="isAuthenticated">
            <router-link to="/cart" class="btn btn-neutral">
                Cart
                <span class="ml-1 badge badge-secondary" id="cart-count">{{ cartCount }}</span>
            </router-link>
            <button class="btn btn-secondary" @click="logout">Logout</button>
            <div v-if="isAdmin">
                <button class="btn btn-secondary" @click="admin">Admin</button>
            </div>
            <div>
                <button class="btn btn-secondary" @click="user">User</button>
            </div>
        </div>

        <div v-if="isMenuOpen" class="absolute right-0 z-50 w-full p-4 space-y-2 rounded-lg shadow-lg top-16 bg-base-100 lg:hidden">
            <div v-if="!isAuthenticated" class="p-2">
                <router-link to="/login" class="block w-full py-2 font-semibold text-center rounded-md text-primary hover:bg-gray-200">Login</router-link>
            </div>
            
            <div v-if="isAuthenticated" class="flex flex-col space-y-2">
                <router-link to="/cart" class="flex items-center justify-between w-full px-4 py-2 space-x-2 rounded-md">
                    <span class="text-yellow-400">Cart</span>
                    <span class="badge badge-secondary" id="cart-count">{{ cartCount }}</span>
                </router-link>
                
                <button v-if="isAdmin" @click="admin" class="w-full px-4 py-2 text-left text-green-400 rounded-md">
                    Admin
                </button>
                
                <button @click="user" class="w-full px-4 py-2 text-left text-green-400 rounded-md">
                    User
                </button>

                <button @click="logout" class="w-full px-4 py-2 text-left text-red-400 rounded-md">
                    Logout
                </button>
            </div>
        </div>
    </nav>
</template>

<script>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

export default {
    name: 'Navbar',
    setup() {
        const cartCount = ref(0);
        const isAuthenticated = ref(false);
        const isAdmin = ref(false);
        const isMenuOpen = ref(false);
        const router = useRouter();

        const toggleMenu = () => {
            isMenuOpen.value = !isMenuOpen.value;
        };

        const checkAuth = async () => {
            if (isAuthenticated.value) return;
            if (isAdmin.value) return;
            try {
                const response = await axios.get('/user-details');
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
                await axios.post('/logout');
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

        const user = async () => {
            try {
                await axios.get('/user');
                router.push('/user');
            } catch (error) {
                console.error('Error accessing user:', error);
            }
        };

        const updateCartCount = async () => {
            try {
                const response = await axios.get('/cart', { withCredentials: true });
                if (Array.isArray(response.data)) {
                    cartCount.value = response.data.reduce(
                        (total, item) => total + item.quantity, 
                        0
                    );
                }
            } catch (error) {
                console.error('Error updating cart count:', error);
                cartCount.value = 0;
            }
        };

        const handleCartUpdate = () => {
            updateCartCount();
        };
        
        window.addEventListener('cart-updated', handleCartUpdate);


        onMounted(() => {
            checkAuth();
            updateCartCount();
            window.addEventListener('cart-updated', handleCartUpdate);
        });

        onBeforeUnmount(() => {
            window.removeEventListener('cart-updated', handleCartUpdate);
        });

        window.addEventListener('login', () => {
            checkAuth();
            updateCartCount();
        });

        return {
            isAuthenticated,
            isAdmin,
            isMenuOpen,
            cartCount,
            updateCartCount,
            toggleMenu,
            logout,
            admin,
            user
        };
    },
}
</script>
