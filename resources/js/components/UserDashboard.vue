<template>
    <div class="min-h-screen bg-base-100">

        <div class="navbar bg-base-300">
            <div class="flex-1">
                <a @click="$router.back()" class="btn btn-circle btn-ghost">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h1 class="text-3xl font-bold">User Dashboard</h1>
            </div>
        </div>
    
        <div class="flex flex-col min-h-screen md:flex-row">
            <Sidebar 
                title=""
                :tabs="userTabs" 
                :activeTab="activeTab" 
                @changeTab="handleTabChange" 
            />
    
            <div class="flex-1 p-4 bg-base-100">
                <div v-if="activeTab === 'profileDetails'">
                    <div class="mb-8 card bg-base-200">
                        <div class="card-body">
                            <h2 class="mb-4 text-2xl card-title">Profile Details</h2>
                            
                            <div class="space-y-4">
                                <div class="shadow-xl card bg-base-100">
                                    <div class="card-body">
                                        <h3 class="card-title">Username</h3>
                                        <input v-model="user.username" placeholder="Username" class="w-full input input-bordered" />
                                    </div>
                                </div>
                                <div class="shadow-xl card bg-base-100">
                                    <div class="card-body">
                                        <h3 class="card-title">Email</h3>
                                        <input v-model="user.email" placeholder="Email" class="w-full input input-bordered" />
                                    </div>
                                </div>
                                <div class="shadow-xl card bg-base-100">
                                    <div class="card-body">
                                        <h3 class="card-title">Password</h3>
                                        <input v-model="user.currentPassword" type="password" placeholder="Confirm Password" class="w-full mt-4 input input-bordered" />
                                    </div>
                                </div>
                                <button @click="updateUser(user)" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
    
                <div v-if="activeTab === 'passwordChange'">
                    <div class="mb-8 card bg-base-200">
                        <div class="card-body">
                            <h2 class="mb-4 text-2xl card-title">Change Password</h2>
                            
                            <div class="space-y-4">
                                <div class="shadow-xl card bg-base-100">
                                    <div class="card-body">
                                        <h3 class="card-title">Current Password</h3>
                                        <input type="password" v-model="user.currentPassword" placeholder="Confirm Password" class="w-full mt-4 input input-bordered" />
                                    </div>
                                    <div class="shadow-xl card bg-base-100">
                                        <div class="card-body">
                                            <h3 class="card-title">New Password</h3>
                                            <input type="password" v-model="user.newPassword" placeholder="Enter New Password" class="w-full mt-4 input input-bordered" />
                                        </div>
                                    </div>
                                        <div class="shadow-xl card bg-base-100">
                                            <div class="card-body">
                                                <h3 class="card-title">Confirm New password</h3>
                                                <input type="password" v-model="user.newPasswordConfirm" placeholder="Confirm New Password" class="w-full mt-4 input input-bordered" />
                                            </div>
                                        </div>
                                </div>
                                <button @click="updatePassword(user)" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
    
                <div v-if="activeTab === 'purchaseHistory'">
                    <div class="shadow-xl card bg-base-200">
                        <div class="card-body">
                            <h2 class="text-2xl card-title">Purchase History</h2>
                            
                            <div class="space-y-6">
                                <div v-for="transaction in purchaseHistory" :key="transaction.id" 
                                    class="shadow-lg card bg-base-100">
                                    <div class="card-body">
                                        <div class="flex items-center justify-between pb-4 border-b">
                                            <div>
                                                <h3 class="text-lg font-bold">
                                                    Transaction #{{ transaction.id }}
                                                </h3>
                                                <p class="text-sm opacity-70">
                                                    {{ new Date(transaction.created_at).toLocaleDateString() }} 
                                                    at {{ new Date(transaction.created_at).toLocaleTimeString() }}
                                                </p>
                                            </div>
                                            <div class="badge badge-primary badge-lg">
                                                ${{ transaction.transaction_details.reduce((sum, item) => sum + item.total_price, 0) }}
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4">
                                            <div v-for="detail in transaction.transaction_details" 
                                                :key="detail.product_id" 
                                                class="flex items-center gap-4 py-4">
                                                <div class="avatar">
                                                    <div class="w-16 h-16 rounded">
                                                        <img :src="'/storage/' + detail.product.img_path" 
                                                            :alt="detail.product.name" />
                                                    </div>
                                                </div>
                                                <div class="flex-1">
                                                    <button @click="goToProductDetails(detail.product_id)" class="flex-1 transition hover:text-white">
                                                        {{ detail.product.name }}
                                                    </button>
                                                    <div class="text-sm opacity-70">
                                                        <p>Quantity: {{ detail.quantity }}</p>
                                                        <p>Price: ${{ detail.price }}</p>
                                                        <p class="font-semibold">Total: ${{ detail.total_price }}</p>
                                                    </div>
                                                </div>
                                                <a>Download Link</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    
                <div v-if="activeTab === 'purchaseCredits'">
                    <div class="mb-8 card bg-base-200">
                        <div class="card-body">
                            <h2 class="mb-4 text-2xl card-title">Purchase Credits</h2>
                            
                            <div class="shadow-xl card bg-base-100">
                                <div class="card-body">
                                    <h3 class="card-title">Current Balance</h3>
                                    <p class="text-2xl font-bold">${{ userBalance }}</p>
                                </div>
                            </div>
                            
                            <div class="space-x-4 space-y-4">
                                <input v-model="amount" type="number" min="1" placeholder="Enter amount" class="input input-bordered">
                                <button @click="purchaseCredits" class="btn btn-primary">Purchase</button>
                            </div>
                        </div>
                    </div>
                </div>
    
            
            </div>
        </div>
    
        <Popup v-model:show="popupShow" :title="popupTitle" :message="popupMessage" :type="popupType" />
    </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Popup from './Popup.vue';
import Sidebar from './Sidebar.vue';

export default {
    components: { Popup, Sidebar },
    setup() {
        const popupShow = ref(false);
        const popupTitle = ref('');
        const popupMessage = ref('');
        const popupType = ref('info');
        const userBalance = ref(0);

        const purchaseHistory = ref([]);

        const user = ref({
            username: '',
            email: '',
            currentPassword: '',
            newPassword: '',
            newPasswordConfirm: ''
        });

        const showPopup = (title, message, type = 'info') => {
            popupTitle.value = title;
            popupMessage.value = message;
            popupType.value = type;
            popupShow.value = true;
        };
        
        const clearPasswords = () => {
            user.value.currentPassword = '';
            user.value.newPassword = '';
            user.value.newPasswordConfirm = '';
        };

        const fetchUserDetails = async () => {
            try {
                const response = await axios.get('/user-details');
                const { currentPassword, ...userData } = response.data.user;
                user.value = { ...userData, currentPassword: '', newPassword: '', newPasswordConfirm: '' };
            } catch (error) {
                showPopup('Error', error.response?.data?.error || 'Failed to fetch user details', 'error');
            }
        };

        const fetchUserBalance = async () => {
            try {
                const response = await axios.get('/user-details/balance');
                userBalance.value = response.data.balance;
            } catch (error) {
                showPopup('Error', 'Failed to load balance', 'error');
            }
        }

        const fetchPurchaseHistory = async () => {
            try {
                const response = await axios.get('/user-details/purchase-history');
                purchaseHistory.value = response.data;
            } catch (error) {
                showPopup('Error', error.response?.data?.error || 'Failed to fetch purchase history', 'error');
            }
        };

        const validatePasswords = () => {
            if (user.value.currentPassword === '') {
                showPopup('Error', 'Please enter your current password', 'error');
                return false;
            }

            if (user.value.newPassword !== '' && user.value.newPassword !== user.value.newPasswordConfirm) {
                showPopup('Error', 'New password and confirm password do not match', 'error');
                return false;
            }

            return true;
        };

        const validateUserData = (userData) => {
            if (!userData.email?.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
                showPopup('Error', 'Please enter a valid email address', 'error');
                return false;
            }
            if (!userData.username?.match(/^[a-zA-Z0-9_]{3,20}$/)) {
                showPopup('Error', 'Username must be 3-20 characters and contain only letters, numbers and underscores', 'error');
                return false;
            }
            return true;
        };
        
        const updateUser = async (userData) => {
            try {
                if (!validateUserData(userData)) return;
                if (!validatePasswords()) return;

                const updateData = {
                    username: userData.username.trim(),
                    email: userData.email.trim(),
                    currentPassword: userData.currentPassword
                };

                await axios.put('/user-details', updateData);
                showPopup('Success', 'User details updated successfully', 'success');
                clearPasswords();
            } catch (error) {
                showPopup('Error', error.response?.data?.error || 'Failed to update user details', 'error');
            }
        };

        const updatePassword = async (userData) => {
            try {
                if (!validatePasswords()) return;

                const updateData = { ...userData, currentPassword: userData.currentPassword, newPassword: userData.newPassword };

                await axios.put('/user-details', updateData);
                showPopup('Success', 'Password updated successfully', 'success');
                clearPasswords();
            } catch (error) {
                const errors = error.response?.data?.errors;

                let errorMessage = 'Failed to update password';
                if (errors) {
                    errorMessage = Object.values(errors)
                        .flat() 
                        .join('\n'); 
                }

                showPopup('Error', errorMessage, 'error');
            }
        };

        onMounted(() => {
            fetchUserDetails();
            fetchPurchaseHistory();
            fetchUserBalance();
        });

        return {
            popupShow,
            popupTitle,
            popupMessage,
            popupType,
            user,
            purchaseHistory,
            userBalance,
            fetchUserBalance,
            updatePassword,
            fetchPurchaseHistory,
            showPopup,
            fetchUserDetails,
            updateUser
        };
    },
    data() {
        return {
            activeTab: 'profileDetails',
            userTabs: [
                { name: 'profileDetails', label: 'Profile Details' },
                { name: 'passwordChange', label: 'Change Password' },
                { name: 'purchaseHistory', label: 'Purchase History' },
                { name: 'purchaseCredits', label: 'Purchase Credits' }
            ]
        };
    },
    methods: {
        handleTabChange(tab) {
            this.activeTab = tab;
        },
        goToProductDetails(productId) {
            this.$router.push({ name: 'product.details', params: { id: productId } });
        },
        async purchaseCredits() {
            try {
                const response = await axios.post('/user-details/purchase-credits', { amount: this.amount });
                this.userBalance = response.data.balance;
                this.amount = parseFloat(this.amount).toFixed(2);
                this.showPopup('Success', 'Credits purchased successfully', 'success');
            } catch (error) {
                this.showPopup('Error', error.response?.data?.error || 'Failed to purchase credits', 'error');
            }
        }
    }
};
</script>