<template>
    <div class="flex flex-col h-screen md:flex-row">
        <div class="p-4 border-r md:w-64 bg-base-200">
            <h1 class="mb-6 text-2xl font-bold text-primary">User Dashboard</h1>
            <div class="space-y-4">
                <a
                    class="justify-start btn"
                    :class="{ 'bg-base-300': activeTab === 'profileDetails' }"
                    @click="activeTab = 'profileDetails'"
                >
                    Profile Details
                </a>
                <a
                    class="justify-start btn"
                    :class="{ 'bg-base-300': activeTab === 'purchaseHistory' }"
                    @click="activeTab = 'purchaseHistory'"
                >
                    Purchase History
                </a>
            </div>
        </div>

        <div class="flex-1 p-4">
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
                                    <input v-model="user.currentPassword" placeholder="Confirm Password" class="w-full mt-4 input input-bordered" />
                                </div>
                            </div>
                            <button @click="updateUser(user)" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="activeTab === 'purchaseHistory'">
                <div class="mb-8 card bg-base-200">
                    <div class="card-body">
                        <h2 class="mb-4 text-2xl card-title">Purchase History</h2>
                        
                        <div class="space-y-4">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <Popup v-model:show="popupShow" :title="popupTitle" :message="popupMessage" :type="popupType" />
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Popup from './Popup.vue';
import Confirmation from './Confirmation.vue';

export default {
    components: { Popup, Confirmation },
    setup() {
        const popupShow = ref(false);
        const popupTitle = ref('');
        const popupMessage = ref('');
        const popupType = ref('info');

        const user = ref({
            username: '',
            email: '',
            currentPassword: ''
        });

        const showPopup = (title, message, type = 'info') => {
            popupTitle.value = title;
            popupMessage.value = message;
            popupType.value = type;
            popupShow.value = true;
        };
        
        const clearPasswords = () => {
            user.value.currentPassword = '';
        };

        const fetchUserDetails = async () => {
            try {
                const response = await axios.get('/user-details');
                const { currentPassword, ...userData } = response.data.user;
                user.value = { ...userData, currentPassword: '' };
            } catch (error) {
                showPopup('Error', error.response?.data?.message || 'Failed to fetch user details', 'error');
            }
        };

        const validatePasswords = () => {
            if (user.value.currentPassword === '') {
                showPopup('Error', 'Please enter your current password', 'error');
                return false;
            }

            return true;
        };
        
        const updateUser = async (userData) => {
            try {
                if (!validatePasswords()) return;

                const updateData = { ...userData };

                await axios.put('/user-details', updateData);
                showPopup('Success', 'User details updated successfully', 'success');
                clearPasswords();
            } catch (error) {
                showPopup('Error', error.response?.data?.message || 'Failed to update user details', 'error');
            }
        };

        onMounted(() => {
            fetchUserDetails();
        });

        return {
            popupShow,
            popupTitle,
            popupMessage,
            popupType,
            user,
            showPopup,
            fetchUserDetails,
            updateUser
        };
    },
    data() {
        return {
            activeTab: 'profileDetails'
        };
    }
};
</script>