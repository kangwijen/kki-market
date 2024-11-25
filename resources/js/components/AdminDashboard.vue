<template>
    <div class="min-h-screen bg-base-100">
        <div class="navbar bg-base-300">
            <div class="flex-1">
                <a @click="$router.back()" class="btn btn-circle btn-ghost">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h1 class="text-3xl font-bold">Admin Dashboard</h1>
            </div>
        </div>
    
        <div class="flex flex-col min-h-screen md:flex-row">
            <Sidebar 
                title=""
                :tabs="adminTabs" 
                :activeTab="activeTab" 
                @changeTab="handleTabChange" 
            />
    
            <div class="flex-1 p-4">
                <div v-if="activeTab === 'createProduct'" class="prose">
                    <div class="mb-8 card bg-base-200">
                        <div class="card-body">
                            <h2 class="mb-4 text-2xl card-title">Create New Product</h2>
                            <form @submit.prevent="createProduct" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text">Product Name</span>
                                    </label>
                                    <input v-model="newProduct.name" placeholder="Product Name" class="w-full input input-bordered" required>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text">Price</span>
                                    </label>
                                    <input v-model="newProduct.product_detail.price" type="number" step="0.01" placeholder="Price" class="w-full input input-bordered" required>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text">Description</span>
                                    </label>
                                    <input v-model="newProduct.product_detail.description" placeholder="Description" class="w-full input input-bordered" required>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text">Stock</span>
                                    </label>
                                    <input v-model="newProduct.product_detail.stock" type="number" step="1" placeholder="Stock" class="w-full input input-bordered" required>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text">Choose Image</span>
                                    </label>
                                    <input type="file" @change="handleImageUpload($event)" class="w-full file-input file-input-bordered" />
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text">Product Type</span>
                                    </label>
                                    <select v-model="newProduct.product_type_id" class="w-full select select-bordered" required>
                                        <option disabled value="">Select Product Type</option>
                                        <option v-for="type in productTypes" :key="type.id" :value="type.id">{{ type.name }}</option>
                                    </select>
                                </div>
                                <button type="submit" class="w-full btn btn-primary sm:col-span-2 lg:col-span-3">Create Product</button>
                            </form>
                        </div>
                    </div>
                </div>
    
                <div v-if="activeTab === 'updateProduct'">
                    <div class="mb-8 card bg-base-200">
                        <div class="card-body">
                            <h2 class="mb-4 text-2xl card-title">Update Product</h2>
                            <div v-for="product in products" :key="product.id" class="mb-4">
                                <div class="shadow-xl card bg-base-100">
                                    <div class="card-body">
                                        <h2 class="card-title">
                                            <input v-model="product.name" placeholder="Product Name" class="w-full input input-bordered" />
                                        </h2>
                                        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                            <div class="form-control">
                                                <label class="label">
                                                    <span class="label-text">Price</span>
                                                </label>
                                                <input v-model="product.product_detail.price" type="number" step="0.01" placeholder="Price" class="w-full input input-bordered" />
                                            </div>
                                            <div class="form-control">
                                                <label class="label">
                                                    <span class="label-text">Description</span>
                                                </label>
                                                <input v-model="product.product_detail.description" placeholder="Description" class="w-full input input-bordered" />
                                            </div>
                                            <div class="form-control">
                                                <label class="label">
                                                    <span class="label-text">Stock</span>
                                                </label>
                                                <input v-model="product.product_detail.stock" type="number" step="1" placeholder="Stock" class="w-full input input-bordered" />
                                            </div>
                                            <div class="form-control">
                                                <label class="label">
                                                    <span class="label-text">Change Image</span>
                                                </label>
                                                <input type="file" @change="handleImageUpload($event, updateProduct)" class="w-full file-input file-input-bordered" />
                                            </div>
                                            <div class="form-control">
                                                <label class="label">
                                                    <span class="label-text">Product Type</span>
                                                </label>
                                                <select v-model="product.product_type_id" class="w-full select select-bordered">
                                                <option v-for="type in productTypes" :key="type.id" :value="type.id">{{ type.name }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="justify-end card-actions">
                                            <button @click="updateProduct(product)" class="btn btn-primary">Update</button>
                                            <button @click="confirmDelete(product.id)" class="btn btn-error">Delete</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    
                <div v-if="activeTab === 'productTypeManagement'" class="prose">
                    <div class="mb-8 card bg-base-200">
                        <div class="card-body">
                            <h2 class="card-title">Product Type Management</h2>
                            <div role="tablist" class="tabs tabs-boxed">
                                <a role="tab" class="tab" :class="{ 'tab-active': activeTabProductType === 'create' }" @click="activeTabProductType = 'create'">Create</a>
                                <a role="tab" class="tab" :class="{ 'tab-active': activeTabProductType === 'update' }" @click="activeTabProductType = 'update'">Update</a>
                                <a role="tab" class="tab" :class="{ 'tab-active': activeTabProductType === 'delete' }" @click="activeTabProductType = 'delete'">Delete</a>
                            </div>
    
                            <div v-if="activeTabProductType === 'create'" class="p-4">
                                <form @submit.prevent="createProductType" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                    <input v-model="newProductType.name" placeholder="Product Type Name" class="w-full input input-bordered" required>
                                    <button type="submit" class="w-full btn btn-primary sm:col-span-2 lg:col-span-3">
                                        Create Product Type
                                    </button>
                                </form>
                            </div>
    
                            <div v-if="activeTabProductType === 'update'" class="p-4">
                                <form @submit.prevent="handleUpdateProductType" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                    <select v-model="updateForm.id" class="w-full select select-bordered" required @change="handleProductTypeSelect">
                                        <option disabled value="">Select Product Type</option>
                                        <option v-for="type in productTypes" :key="type.id" :value="type.id">
                                            {{ type.name }}
                                        </option>
                                    </select>
                                    <input v-model="updateForm.name" placeholder="New Product Type Name" class="w-full input input-bordered" required>
                                    <button type="submit" class="w-full btn btn-warning sm:col-span-2 lg:col-span-3">
                                        Update Product Type
                                    </button>
                                </form>
                            </div>
    
                            <div v-if="activeTabProductType === 'delete'" class="p-4">
                                <form @submit.prevent="handleDeleteProductType" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                    <select v-model="deleteForm.id" class="w-full select select-bordered" required>
                                        <option disabled value="">Select Product Type</option>
                                        <option v-for="type in productTypes" :key="type.id" :value="type.id">
                                            {{ type.name }}
                                        </option>
                                    </select>
                                    <button type="submit" class="w-full btn btn-error sm:col-span-2 lg:col-span-3">
                                        Delete Product Type
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
    
                <div v-if="activeTab === 'userManagement'" class="prose">
                    <div class="mb-8 card bg-base-200">
                        <div class="card-body">
                            <h2 class="card-title">User Management</h2>
                            <div v-for="user in users" :key="user.id">
                                <div class="shadow-xl card bg-base-100">
                                    <div class="card-body">
                                        <div class="grid gap-6 md:grid-cols-2">
                                            <div class="space-y-4">
                                                <h3 class="text-xl font-bold">Basic Information</h3>
                                                <div class="form-control">
                                                    <label class="label">
                                                        <span class="label-text">Username</span>
                                                    </label>
                                                    <input 
                                                        v-model="user.username" 
                                                        type="text"
                                                        class="input input-bordered"
                                                    />
                                                </div>
                                                <div class="form-control">
                                                    <label class="label">
                                                        <span class="label-text">Email</span>
                                                    </label>
                                                    <input 
                                                        v-model="user.email" 
                                                        type="email" 
                                                        class="input input-bordered"
                                                    />
                                                </div>
                                                <div class="form-control">
                                                    <label class="label">
                                                        <span class="label-text">Role</span>
                                                    </label>
                                                    <span>{{ user.role_name }}</span>
                                                </div>
                                            </div>
    
                                            <div class="space-y-4">
                                                <h3 class="text-xl font-bold">User Details</h3>
                                                <div class="form-control">
                                                    <label class="label">
                                                        <span class="label-text">Balance</span>
                                                    </label>
                                                    <input 
                                                        v-model="user.user_detail.balance" 
                                                        type="number" 
                                                        step="0.01" 
                                                        class="input input-bordered" 
                                                    />
                                                </div>
                                                <div class="form-control">
                                                    <label class="label">
                                                        <span class="label-text">Verification Status</span>
                                                    </label>
                                                    <div class="flex items-center space-x-2">
                                                        <input 
                                                            v-model="user.user_detail.verified" 
                                                            type="checkbox" 
                                                            class="toggle toggle-primary" 
                                                            :true-value="1" 
                                                            :false-value="0"
                                                        />
                                                        <span>{{ user.user_detail.verified ? 'Verified' : 'Not Verified' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="mt-4 divider"></div>
                                            
                                            <div class="flex flex-wrap justify-end gap-2 mt-4">
                                                <button @click="updateUser(user)" class="btn btn-primary">Update</button>
                                                <button @click="handleDeleteUser(user.id)" class="btn btn-error">Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <Popup v-model:show="popupShow" :title="popupTitle" :message="popupMessage" :type="popupType" />
        <Confirmation v-model:show="confirmationShow" :title="confirmationTitle" :message="confirmationMessage" @confirm="handleConfirmation" @cancel="handleCancelConfirmation" />
    </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Popup from './Popup.vue';
import Confirmation from './Confirmation.vue';
import Sidebar from './Sidebar.vue';

export default {
    components: { Popup, Confirmation, Sidebar },
    setup() {
        const imageFile = ref(null);

        const productTypes = ref([]);
        const newProductType = ref({
            name: ''
        });

        const updateForm = ref({
            id: '',
            name: ''
        });

        const deleteForm = ref({
            id: ''
        });
                
        const products = ref([]);
        const newProduct = ref({
            name: '',
            product_detail: {
                price: '',
                description: '',
                stock: ''
            },
            img_path: '',
            product_type_id: ''
        });

        const popupShow = ref(false);
        const popupTitle = ref('');
        const popupMessage = ref('');
        const popupType = ref('info');

        const confirmationShow = ref(false);
        const confirmationTitle = ref('');
        const confirmationMessage = ref('');

        const deleteState = ref({
            type: null,
            id: null
        });

        const users = ref([]);

        const showPopup = (title, message, type = 'info') => {
            popupTitle.value = title;
            popupMessage.value = message;
            popupType.value = type;
            popupShow.value = true;
        };

        const fetchProductTypes = async () => {
            try {
                const response = await axios.get('/product-types');
                productTypes.value = response.data;
            } catch (error) {
                showPopup('Error', error.response?.data?.error || 'Failed to fetch product types', 'error');
            }
        };

        const fetchProducts = async () => {
            try {
                const response = await axios.get('/products');
                products.value = response.data;
            } catch (error) {
                showPopup('Error', error.response?.data?.error || 'Failed to fetch products', 'error');
            }
        };

        const fetchUserDetails = async () => {
            try {
                const response = await axios.get('/user-details/all');
                users.value = response.data.userDetails;
                users.value.forEach(user => {
                    user.role_name = user.role_id === 1 ? 'Admin' : 'User';
                });
            } catch (error) {
                showPopup('Error', error.response?.data?.error || 'Failed to fetch user details', 'error');
            }
        };

        const validateProductData = (product) => {
            if (!product.name?.trim()) {
                showPopup('Error', 'Product name is required', 'error');
                return false;
            }
            if (isNaN(product.product_detail.price) || product.product_detail.price <= 0) {
                showPopup('Error', 'Please enter a valid price', 'error');
                return false;
            }
            if (isNaN(product.product_detail.stock) || product.product_detail.stock < 0) {
                showPopup('Error', 'Please enter a valid stock amount', 'error');
                return false;
            }
            return true;
        };

        const validateFileUpload = (file) => {
            const maxSize = 5 * 1024 * 1024; // 5MB
            const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            
            if (!file) return false;
            if (file.size > maxSize) {
                showPopup('Error', 'File size must be less than 5MB', 'error');
                return false;
            }
            if (!allowedTypes.includes(file.type)) {
                showPopup('Error', 'Only JPEG, PNG and WEBP files are allowed', 'error');
                return false;
            }
            return true;
        };

        const handleImageUpload = async (event, product = null) => {
            try {
                const file = event.target.files[0];
                const formData = new FormData();
                formData.append('image', file);
                if (product) {
                    const response = await axios.post('/product/upload-image', formData, {
                        headers: {
                        'Content-Type': 'multipart/form-data'
                        }
                    });
                    const jsonResponse = response.data.match(/{.*}/s);
                    if (jsonResponse) {
                        const parsedResponse = JSON.parse(jsonResponse[0]);
                        imageFile.value = parsedResponse.image_path;
                    } else {
                        showPopup('Error', 'Failed to upload image', 'error');
                    }
                } else {
                    const file = event.target.files[0];
                    const formData = new FormData();
                    formData.append('image', file);
                    const response = await axios.post('/product/upload-image', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    });
                    const jsonResponse = response.data.match(/{.*}/s);
                    if (jsonResponse) {
                        const parsedResponse = JSON.parse(jsonResponse[0]);
                        newProduct.value.img_path = parsedResponse.image_path;
                    } else {
                        showPopup('Error', 'Failed to upload image', 'error');
                    }
                }
            } catch (error) {
                showPopup('Error', error.response?.data?.error || 'Failed to upload image', 'error');
            }
        };

        const createProduct = async () => {
            try {
                if (!validateProductData(newProduct.value)) return;

                const productData = {
                    name: newProduct.value.name.trim(),
                    product_detail: {
                        price: parseFloat(newProduct.value.product_detail.price),
                        description: newProduct.value.product_detail.description.trim(),
                        stock: parseInt(newProduct.value.product_detail.stock)
                    },
                    img_path: newProduct.value.img_path,
                    product_type_id: newProduct.value.product_type_id
                };

                const response = await axios.post('/product', productData);
                products.value.push(response.data);
                showPopup('Success', 'Product created successfully', 'success');
                newProduct.value = {
                    name: '',
                    product_detail: {
                        price: '',
                        description: '',
                        stock: ''
                    },
                    img_path: '',
                    product_type_id: ''
                };
            } catch (error) {
                showPopup('Error', error.response?.data?.error || 'Failed to create product', 'error');
            }
        };

        const createProductType = async () => {
            try {
                const response = await axios.post('/product-types', newProductType.value);
                productTypes.value.push(response.data);
                showPopup('Success', 'Product type created successfully', 'success');
                newProductType.value = { name: '' };
            } catch (error) {
                showPopup('Error', error.response?.data?.error || 'Failed to create product type', 'error');
            }
        };

        const updateProduct = async (product) => {
            try {
                product.img_path = imageFile.value || product.img_path;
            
                await axios.put(`/product/${product.id}`, product);
                showPopup('Success', 'Product updated successfully', 'success');
            } catch (error) {
                showPopup('Error', error.response?.data?.error || 'Failed to update product', 'error');
            }
        };

        const handleProductTypeSelect = () => {
            const selectedType = productTypes.value.find(type => type.id === updateForm.value.id);
            if (selectedType) {
                updateForm.value.name = selectedType.name;
            }
        };

        const handleUpdateProductType = async () => {
            try {
                const response = await axios.put(`/product-types/${updateForm.value.id}`, {
                    name: updateForm.value.name
                });
                
                const index = productTypes.value.findIndex(type => type.id === updateForm.value.id);
                if (index !== -1) {
                    productTypes.value[index] = response.data;
                }
                
                showPopup('Success', 'Product type updated successfully', 'success');
                updateForm.value = { id: '', name: '' };
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

        const updateUser = async (user) => {
            try {
                if (user.email === '') {
                    showPopup('Error', 'Please enter new email', 'error');
                    return false;
                }

                if (user.username === '') {
                    showPopup('Error', 'Please enter new username', 'error');
                    return false;
                }

                const updateData = { ...user};
                
                await axios.put(`/user-update/${user.id}`, updateData);
                showPopup('Success', 'User details updated successfully', 'success');
            } catch (error) {
                showPopup('Error', error.response?.data?.error || 'Failed to update user details', 'error');
            }
        };

        const handleDeleteUser = (user) => {
            showPopup('Delete User', 'This feature is not yet implemented', 'info');
        };

        const confirmDelete = (id) => {
            deleteState.value = {
                type: 'product',
                id: id
            };
            confirmationTitle.value = 'Confirm Product Deletion';
            confirmationMessage.value = 'Are you sure you want to delete this product?';
            confirmationShow.value = true;
        };

        const handleDeleteProductType = () => {
            deleteState.value = {
                type: 'productType',
                id: deleteForm.value.id
            };
            confirmationTitle.value = 'Confirm Product Type Deletion';
            confirmationMessage.value = 'Are you sure you want to delete this product type? This action cannot be undone.';
            confirmationShow.value = true;
        };


        const handleConfirmation = async () => {
            if (!deleteState.value.id) return;

            try {
                if (deleteState.value.type === 'product') {
                    await axios.delete(`/product/${deleteState.value.id}`);
                    products.value = products.value.filter(p => p.id !== deleteState.value.id);
                    showPopup('Success', 'Product deleted successfully', 'success');
                } else if (deleteState.value.type === 'productType') {
                    await axios.delete(`/product-types/${deleteState.value.id}`);
                    productTypes.value = productTypes.value.filter(t => t.id !== deleteState.value.id);
                    deleteForm.value.id = '';
                    showPopup('Success', 'Product type deleted successfully', 'success');
                }
            } catch (error) {
                const errorMessage = error.response?.data?.error || `Failed to delete ${deleteState.value.type}`;
                showPopup('Error', errorMessage, 'error');
            }

            confirmationShow.value = false;
            deleteState.value = { type: null, id: null };
        };

        const handleCancelConfirmation = () => {
            confirmationShow.value = false;
            deleteState.value = { type: null, id: null };
            if (deleteState.value.type === 'productType') {
                deleteForm.value.id = '';
            }
        };

        onMounted(() => {
            fetchUserDetails();
            fetchProductTypes();
            fetchProducts();
        });

        return {
            productTypes,
            products,
            newProduct,
            newProductType,
            popupShow,
            popupTitle,
            popupMessage,
            popupType,
            confirmationShow,
            confirmationTitle,
            confirmationMessage,
            updateForm,
            deleteForm,
            users,
            updateUser,
            handleDeleteUser,
            fetchUserDetails,
            createProduct,
            createProductType,
            updateProduct,
            confirmDelete,
            handleImageUpload,
            handleConfirmation,
            handleCancelConfirmation,
            handleProductTypeSelect,
            handleUpdateProductType,
            handleDeleteProductType
        };
    },
    data() {
        return {
            activeTabProductType: 'create',
            activeTab: 'createProduct',
            activeTabUserManagement: 'update',
            adminTabs: [
                { name: 'createProduct', label: 'Create Product' },
                { name: 'updateProduct', label: 'Update Product' },
                { name: 'productTypeManagement', label: 'Product Type Management' },
                { name: 'userManagement', label: 'User Management' }
            ]
        };
    },
    methods: {
        handleTabChange(tab) {
            this.activeTab = tab;
        }
    }
};
</script>