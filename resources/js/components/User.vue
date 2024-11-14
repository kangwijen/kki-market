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
                <a
                    class="justify-start btn"
                    :class="{ 'bg-base-300': activeTab === 'passwordChange' }"
                    @click="activeTab = 'passwordChange'"
                >
                    Change Password
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
                                    <input type="password" v-model="user.currentPassword" placeholder="Confirm Password" class="w-full mt-4 input input-bordered" />
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

            <div v-if="activeTab === 'passwordChange'">
                <div class="mb-8 card bg-base-200">
                    <div class="card-body">
                        <h2 class="mb-4 text-2xl card-title">Change Password</h2>
                        
                        <div class="space-y-4">
                            <div class="shadow-xl card bg-base-100">
                                <div class="card-body">
                                    <h3 class="card-title">Username</h3>
                                    <input v-model="user.username" placeholder="Username" class="w-full input input-bordered" />
                                </div>
                            </div>
                            <div class="shadow-xl card bg-base-100">
                                <div class="card-body">
                                    <h3 class="card-title">Current password</h3>
                                    <input type="password" v-model="user.currentPassword" placeholder="Confirm Password" class="w-full mt-4 input input-bordered" />
                                </div>
                                <div class="shadow-xl card bg-base-100">
                                    <div class="card-body">
                                        <h3 class="card-title">New password</h3>
                                        <input type="password" v-model="user.newPassword" placeholder="Enter New Password" class="w-full mt-4 input input-bordered" />
                                    </div>
                                </div>
                                    <div class="shadow-xl card bg-base-100">
                                        <div class="card-body">
                                            <h3 class="card-title">Confirm new password</h3>
                                            <input type="password" v-model="user.newPassword_confirmation" placeholder="Confirm New Password" class="w-full mt-4 input input-bordered" />
                                        </div>
                                    </div>
                            </div>
                            <button @click="updatePassword(user)" class="btn btn-primary">Update</button>
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
            currentPassword: '',
            newPassword: ''
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

        const updatePassword = async (userData) => {
            try {
                // if (!validatePasswords()) return;
                // if (!this.user.newPassword) {
                //     this.showPopup('Error', 'New password is required', 'error');
                //     return false;
                // }
                // if (this.user.newPassword.length < 8) {
                //     this.showPopup('Error', 'New password must be at least 8 characters', 'error');
                //     return false;
                // }
                // if (this.user.newPassword !== this.user.newPassword_confirmation) {
                //     this.showPopup('Error', 'New password is required', 'error');
                //     return false;
                // }
                // if (this.user.currentPassword === this.user.newPassword) {
                //     this.showPopup('Error', 'New password must be different from current password', 'error');
                //     return false;
                // }
                if (!userData.newPassword) {
                    showPopup('Error', 'New password is required', 'error');
                    return false;
                }
                
                if (userData.newPassword.length < 8) {
                    showPopup('Error', 'New password must be at least 8 characters', 'error');
                    return false;
                }
                
                if (userData.newPassword !== userData.newPassword_confirmation) {
                    showPopup('Error', 'Password confirmation does not match', 'error');
                    return false;
                }
                
                if (userData.currentPassword === userData.newPassword) {
                    showPopup('Error', 'New password must be different from current password', 'error');
                    return false;
                }
                const updateData = { 
                    username: userData.username,
                    currentPassword: userData.currentPassword,
                    newPassword: userData.newPassword,
                    newPassword_confirmation: userData.newPassword_confirmation
                };
                // showPopup('error', 'keren');

                const response = await axios.put('/user-details', updateData);
                showPopup('Success', 'Password updated successfully', 'success');
            } catch (error) {
                showPopup('Error', error.response?.data?.message || 'Failed to change password', 'error');
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
            updateUser,
            updatePassword
        };
    },
    data() {
        return {
            activeTab: 'profileDetails'
        };
    }
};
</script>