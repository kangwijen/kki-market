<template>
    <div class="flex flex-col h-screen md:flex-row">
        <div class="p-4 border-r md:w-64 bg-base-200">
            <h1 class="mb-6 text-2xl font-bold text-primary">Admin Dashboard</h1>
            <div class="space-y-4">
                <a
                    class="justify-start btn"
                    :class="{ 'bg-base-300': activeTab === 'createProduct' }"
                    @click="activeTab = 'createProduct'"
                >
                    Create Product
                </a>
                <a
                    class="justify-start btn"
                    :class="{ 'bg-base-300': activeTab === 'updateProduct' }"
                    @click="activeTab = 'updateProduct'"
                >
                    Update Product
                </a>
                <a
                    class="justify-start btn"
                    :class="{ 'bg-base-300': activeTab === 'productTypeManagement' }"
                    @click="activeTab = 'productTypeManagement'"
                >
                    Product Type Management
                </a>
            </div>
        </div>

        <div class="flex-1 p-4">
            <div v-if="activeTab === 'createProduct'">
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
                        <div v-for="product in products" :key="product.id" class="shadow-xl card bg-base-100">
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
                                        <span class="label-text">Discount</span>
                                    </label>
                                    <input v-model="product.product_detail.discount" type="number" step="1" placeholder="Discount" class="w-full input input-bordered" />
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

            <div v-if="activeTab === 'productTypeManagement'">
                <div class="mb-8 card bg-base-200">
                    <div class="card-body">
                        <h2 class="mb-4 text-2xl card-title">Product Type Management</h2>
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
        </div>
    </div>

    <Popup v-model:show="popupShow" :title="popupTitle" :message="popupMessage" :type="popupType" />
    <Confirmation v-model:show="confirmationShow" :title="confirmationTitle" :message="confirmationMessage" @confirm="handleConfirmation" @cancel="handleCancelConfirmation" />
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Popup from './Popup.vue';
import Confirmation from './Confirmation.vue';

export default {
    components: { Popup, Confirmation },
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
                stock: '',
                discount: 0
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
                showPopup('Error', error.response?.data?.message || 'Failed to fetch product types', 'error');
            }
        };

        const fetchProducts = async () => {
            try {
                const response = await axios.get('/products');
                products.value = response.data;
            } catch (error) {
                showPopup('Error', error.response?.data?.message || 'Failed to fetch products', 'error');
            }
        };

        const createProduct = async () => {
            try {
                const response = await axios.post('/product', newProduct.value);
                products.value.push(response.data);
                showPopup('Success', 'Product created successfully', 'success');
                newProduct.value = {
                    name: '',
                    product_detail: {
                        price: '',
                        description: '',
                        stock: '',
                        discount: 0
                    },
                    img_path: '',
                    product_type_id: ''
                };
            } catch (error) {
                showPopup('Error', error.response?.data?.message || 'Failed to create product', 'error');
            }
        };

        const createProductType = async () => {
            try {
                const response = await axios.post('/product-types', newProductType.value);
                productTypes.value.push(response.data);
                showPopup('Success', 'Product type created successfully', 'success');
                newProductType.value = { name: '' };
            } catch (error) {
                showPopup('Error', error.response?.data?.message || 'Failed to create product type', 'error');
            }
        };

        const updateProduct = async (product) => {
            try {
                product.img_path = imageFile.value || product.img_path;
            
                await axios.put(`/product/${product.id}`, product);
                showPopup('Success', 'Product updated successfully', 'success');
            } catch (error) {
                showPopup('Error', error.response?.data?.message || 'Failed to update product', 'error');
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
                showPopup('Error', error.response?.data?.message || 'Failed to update product type', 'error');
            }
        };

        const handleImageUpload = async (event, product = null) => {
            try {
                const file = event.target.files[0];
                const formData = new FormData();
                formData.append('image', file);

                if (product) {
                    console.log("trying to update product image")
                    const response = await axios.post('/product/upload-image', formData, {
                        headers: {
                        'Content-Type': 'multipart/form-data'
                        }
                    });

                    const jsonResponse = response.data.match(/{.*}/s);
                    if (jsonResponse) {
                        const parsedResponse = JSON.parse(jsonResponse[0]);
                        console.log(parsedResponse)
                        imageFile.value = parsedResponse.image_path;
                        console.log(imageFile)
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
                showPopup('Error', error.response?.data?.message || 'Failed to upload image', 'error');
            }
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
            activeTab: 'createProduct'
        };
    }
};
</script>