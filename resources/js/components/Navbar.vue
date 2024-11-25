<template>
    <nav class="shadow-lg navbar bg-base-200">
        <div class="flex-1">
            <router-link v-if="isAuthenticated" to="/search" class="text-xl font-bold normal-case btn btn-ghost">KKI-Market</router-link>
            <router-link v-else to="/" class="text-xl font-bold normal-case btn btn-ghost">KKI-Market</router-link>
        </div>

        <div class="flex-none">
            <div class="hidden lg:flex lg:items-center lg:gap-2">
                <div v-if="!isAuthenticated">
                    <router-link to="/login" class="btn btn-primary">Login</router-link>
                </div>
                
                <div v-if="isAuthenticated" class="flex items-center gap-2">
                    <router-link to="/cart" class="btn btn-ghost">
                        Cart
                        <div class="badge badge-primary">{{ cartCount }}</div>
                    </router-link>
                    <div v-if="isAdmin">
                        <button class="btn btn-ghost" @click="admin">Dashboard</button>
                    </div>
                    <button class="btn btn-ghost" @click="user">Profile</button>
                    <button class="btn btn-error btn-outline" @click="logout">Logout</button>
                </div>
            </div>

            <div class="dropdown dropdown-end lg:hidden">
                <button @click="toggleMenu" class="btn btn-ghost">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>
                <ul v-if="isMenuOpen" class="p-2 mt-3 shadow menu dropdown-content bg-base-100 rounded-box w-52">
                    <li v-if="!isAuthenticated">
                        <router-link to="/login" class="block w-full py-2 font-semibold text-center rounded-md text-primary hover:bg-gray-200">Login</router-link>
                    </li>
                    <li v-if="isAuthenticated">
                        <router-link to="/cart" class="flex items-center justify-between w-full px-4 py-2 space-x-2 rounded-md">
                            <span class="text-yellow-400">Cart</span>
                            <span class="badge badge-secondary" id="cart-count">{{ cartCount }}</span>
                        </router-link>
                        <button v-if="isAdmin" @click="admin" class="w-full px-4 py-2 text-left text-green-400 rounded-md">
                            Dashboard
                        </button>
                        <button @click="user" class="w-full px-4 py-2 text-left text-green-400 rounded-md">
                            Profile
                        </button>
                        <button @click="logout" class="w-full px-4 py-2 text-left text-red-400 rounded-md">
                            Logout
                        </button>
                    </li>
                </ul>
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
                
            }
        };

        const admin = async () => {
            try {
                await axios.get('/admin');
                router.push('/admin');
            } catch (error) {
                
            }
        };

        const user = async () => {
            try {
                await axios.get('/profile');
                router.push('/profile');
            } catch (error) {
                
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
