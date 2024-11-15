import './bootstrap';
import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import Index from './components/Index.vue'
import ProductSearch from './components/ProductSearch.vue'
import ProductDetail from './components/ProductDetail.vue'
import Cart from './components/Cart.vue'
import Login from './components/Login.vue'
import Register from './components/Register.vue'
import Navbar from './components/Navbar.vue'
import AdminDashboard from './components/AdminDashboard.vue'
import UserDashboard from './components/UserDashboard.vue'
import axios from 'axios'
import NotFound from './components/NotFound.vue'
import Sidebar from './components/Sidebar.vue';

axios.defaults.withCredentials = true;
axios.defaults.baseURL = 'http://localhost:8000/api';

const routes = [
    { path: '/', name: 'index', component: Index },
    { path: '/search', name: 'search', component: ProductSearch },
    { path: '/product/:id', name: 'product.details', component: ProductDetail },
    { path: '/cart', name: 'cart', component: Cart },
    { path: '/login', component: Login },
    { path: '/register', component: Register },
    { path: '/admin', name: 'admin', component: AdminDashboard },
    { path: '/profile', name: 'profile', component: UserDashboard },
    { path: '/:pathMatch(.*)*', name: 'not-found', component: NotFound }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach((to, from, next) => {
    if (to.path === '/login' || to.path === '/register') {
        axios.defaults.baseURL = 'http://localhost:8000';
    } else {
        axios.defaults.baseURL = 'http://localhost:8000/api';
    }
    next();
});

const app = createApp({
    components: {
        Navbar,
        Sidebar
    },
    template: `
        <Navbar />
        <router-view></router-view>
    `
})

app.use(router);
app.mount('#app');