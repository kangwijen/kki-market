<template>
    <div class="container p-4 mx-auto">
        <h1 class="mb-6 text-3xl font-bold text-primary">Admin Dashboard</h1>
        
        <!-- Create Product Form -->
        <div class="mb-8 card bg-base-200">
            <div class="card-body">
            <h2 class="mb-4 text-2xl card-title">Create New Product</h2>
            <form @submit.prevent="createProduct" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <input v-model="newProduct.name" placeholder="Product Name" class="w-full input input-bordered" required>
                <input v-model="newProduct.product_detail.price" type="number" step="0.01" placeholder="Price" class="w-full input input-bordered" required>
                <input v-model="newProduct.product_detail.description" placeholder="Description" class="w-full input input-bordered" required>
                <input v-model="newProduct.product_detail.stock" type="number" step="1" placeholder="Stock" class="w-full input input-bordered" required>
                <input v-model="newProduct.img_path" placeholder="Image Path" class="w-full input input-bordered" required>
                <select v-model="newProduct.product_type_id" class="w-full select select-bordered" required>
                    <option disabled value="">Select Product Type</option>
                    <option v-for="type in productTypes" :key="type.id" :value="type.id">{{ type.name }}</option>
                </select>
                <button type="submit" class="w-full btn btn-primary sm:col-span-2 lg:col-span-3">Create Product</button>
            </form>
            </div>
        </div>
    
        <!-- Product List -->
        <div class="space-y-4">
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
                    <span class="label-text">Image Path</span>
                    </label>
                    <input v-model="product.img_path" placeholder="Image Path" class="w-full input input-bordered" />
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
        
        <Popup v-model:show="popupShow" :title="popupTitle" :message="popupMessage" :type="popupType" />
        <Confirmation
            v-model:show="confirmationShow"
            :title="confirmationTitle"
            :message="confirmationMessage"
            @confirm="handleConfirmDelete"
            @cancel="cancelDelete"
        />
    </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Popup from './Popup.vue';
import Confirmation from './Confirmation.vue';

export default {
    components: { Popup, Confirmation },
    setup() {
        const products = ref([]);
        const productTypes = ref([]);
        const popupShow = ref(false);
        const popupTitle = ref('');
        const popupMessage = ref('');
        const popupType = ref('info');
        const confirmationShow = ref(false);
        const confirmationTitle = ref('');
        const confirmationMessage = ref('');
        const productToDelete = ref(null);
        const newProduct = ref({
            name: '',
            img_path: '',
            product_type_id: '',
            product_detail: {
                price: '',
                description: '',
                stock: '',
                discount: 0
            }
        });

        const showPopup = (title, message, type = 'info') => {
            popupTitle.value = title;
            popupMessage.value = message;
            popupType.value = type;
            popupShow.value = true;
        };

        const fetchProducts = async () => {
            try {
                const response = await axios.get('/products', { withCredentials: true });
                products.value = response.data;
                console.log(products.value);
            } catch (error) {
                showPopup('Error', error.response.data.message, 'error');
            }
        };

        const fetchProductTypes = async () => {
            try {
                const response = await axios.get('/product-types', { withCredentials: true });
                productTypes.value = response.data;
            } catch (error) {
                showPopup('Error', error.response.data.message, 'error');
            }
        };

        const createProduct = async () => {
            try {
                const productData = {
                    ...newProduct.value,
                    product_detail: {
                        ...newProduct.value.product_detail,
                        price: parseFloat(newProduct.value.product_detail.price),
                        stock: parseInt(newProduct.value.product_detail.stock),
                        discount: parseInt(newProduct.value.product_detail.discount)
                    }
                };
                
                const response = await axios.post('/product', productData);
                products.value.push(response.data);
                showPopup('Success', 'Product created successfully', 'success');
                
                newProduct.value = {
                    name: '',
                    img_path: '',
                    product_type_id: '',
                    product_detail: {
                        price: '',
                        description: '',
                        stock: '',
                        discount: 0
                    }
                };
            } catch (error) {
                showPopup('Error', error.response?.data?.message || 'An error occurred while creating the product', 'error');
            }
        };

        const updateProduct = async (product) => {
            try {
                await axios.put(`/product/${product.id}`, product);
                showPopup('Success', 'Product updated successfully', 'success');
            } catch (error) {
                showPopup('Error', error.response.data.message, 'error');
            }
        };

        const confirmDelete = (id) => {
            productToDelete.value = id;
            confirmationTitle.value = 'Confirm Deletion';
            confirmationMessage.value = 'Are you sure you want to delete this product?';
            confirmationShow.value = true;
        };

        const handleConfirmDelete = async () => {
            if (productToDelete.value) {
                try {
                    await axios.delete(`/product/${productToDelete.value}`);
                    products.value = products.value.filter(p => p.id !== productToDelete.value);
                    showPopup('Success', 'Product deleted successfully', 'success');
                } catch (error) {
                    showPopup('Error', error.response.data.message, 'error');
                }
                productToDelete.value = null;
            }
        };

        const cancelDelete = () => {
            productToDelete.value = null;
        };

        onMounted(() => {
            fetchProducts();
            fetchProductTypes();
        });

        return {
            products,
            productTypes,
            newProduct,
            createProduct,
            updateProduct,
            confirmDelete,
            handleConfirmDelete,
            cancelDelete,
            popupShow,
            popupTitle,
            popupMessage,
            popupType,
            confirmationShow,
            confirmationTitle,
            confirmationMessage,
        };
    },
};
</script>