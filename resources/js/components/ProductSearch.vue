<template>
    <div class="min-h-screen p-4 sm:p-8 bg-base-300">
        <div class="flex items-center mb-4">
            <a @click="$router.back()" class="items-center justify-center rounded-full btn btn-secondary">⮜</a>
            <h1 class="ml-5 text-3xl font-bold">Products</h1>
        </div>
        <div class="mb-6 space-y-2">
            <label for="search" class="block mb-2 text-lg font-bold">Search for products:</label>
            <input v-model="searchQuery" type="text" id="search" placeholder="Type here..." class="w-full input input-bordered input-primary" @input="searchProducts" />
        </div>
        
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
            <div class="p-4 rounded-lg shadow lg:col-span-1 bg-base-200">
                <h3 class="mb-4 text-lg font-bold">Filter by</h3>
                <div class="mb-4">
                    <label class="block mb-2 font-bold">Category</label>
                    <select v-model="selectedCategory" class="w-full select select-bordered" @change="filterProducts">
                        <option value="">All Categories</option>
                        <option v-for="category in categories" :key="category" :value="category">{{ category }}</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block mb-2 font-bold">Price Range</label>
                    <div class="flex items-center justify-between">
                        $<input v-model="minPrice" type="number" min="0" max="200" class="input input-bordered" @input="filterProducts" />
                        <span class="mx-2">to</span>
                        $<input v-model="maxPrice" type="number" min="0" max="200" class="input input-bordered" @input="filterProducts" />
                    </div>
                </div>
            </div>
        
            <div class="lg:col-span-3">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <div v-for="product in filteredProducts" :key="product.id" class="relative overflow-hidden transition-transform rounded-lg shadow-lg card hover:scale-105">
                        <figure class="relative">
                            <img :src="'/storage/' + product.img_path" :alt="product.name" class="object-cover w-full h-48 rounded-t-lg" />
                            
                            <div v-if="!product.product_detail?.stock" class="absolute inset-0 flex items-center justify-center bg-gray-800 bg-opacity-60">
                                <span class="px-4 py-2 text-lg font-semibold text-white bg-red-500 rounded">Sold Out</span>
                            </div>
                            
                            <span class="absolute px-3 py-1 font-bold text-white rounded-full text-s top-2 right-2 bg-primary">
                                Sold: {{ product.product_detail?.sold ?? 'N/A' }}
                            </span>
                        </figure>

                        <div class="p-4 card-body">
                            <h3 class="text-lg font-semibold text-white card-title">{{ product.name }}</h3>
                            
                            <div class="flex items-center justify-between mt-2">
                                <p class="text-xl font-bold text-primary">${{ product.product_detail?.price ?? 'N/A' }}</p>
                            </div>

                            <div v-if="product.product_detail?.stock > 0" class="flex mt-4 space-x-2">
                                <button @click="goToProductDetails(product.id)" class="flex-1 transition btn btn-outline btn-secondary hover:bg-secondary hover:text-white">
                                    Details
                                </button>
                                <button @click="addToCart(product)" class="flex-1 transition btn btn-primary hover:bg-primary-dark">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-center mt-8">
            <div class="join">
                <button v-for="page in totalPages" :key="page" @click="changePage(page)" :class="['join-item btn', { 'btn-active': currentPage === page }]">{{ page }}</button>
            </div>
        </div>
        <Popup v-model:show="popupShow" :title="popupTitle" :message="popupMessage" :type="popupType" />
    </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import Popup from './Popup.vue'

export default {
    components: { Popup },
    setup() {
        const products = ref([])
        const searchQuery = ref('')
        const selectedCategory = ref('')
        const minPrice = ref(0)
        const maxPrice = ref(1000)
        const currentPage = ref(1)
        const itemsPerPage = 12
        const popupShow = ref(false)
        const popupTitle = ref('')
        const popupMessage = ref('')
        const popupType = ref('info')

        const showPopup = (title, message, type = 'info') => {
            popupTitle.value = title
            popupMessage.value = message
            popupType.value = type
            popupShow.value = true
        }

        const categories = computed(() => {
            if (!Array.isArray(products.value)) {
                return [];
            }
            const categorySet = new Set(products.value.map(p => {return p.product_type.name;}).filter(Boolean));
            return Array.from(categorySet);
        });


        const filteredProducts = computed(() => {
            return products.value.filter(product => {
                const matchesSearch = product.name.toLowerCase().includes(searchQuery.value.toLowerCase())
                const matchesCategory = selectedCategory.value === '' || product.product_type?.name === selectedCategory.value;
                const matchesPrice = (product.product_detail?.price >= minPrice.value && product.product_detail?.price <= maxPrice.value)
                return matchesSearch && matchesCategory && matchesPrice
            })
        })

        const paginatedProducts = computed(() => {
            const start = (currentPage.value - 1) * itemsPerPage
            const end = start + itemsPerPage
            return filteredProducts.value.slice(start, end)
        })

        const totalPages = computed(() => Math.ceil(filteredProducts.value.length / itemsPerPage))

        const fetchProducts = async () => {
            try {
                const response = await axios.get('/products', { withCredentials: true })
                if (Array.isArray(response.data)) {
                    products.value = response.data
                } else {
                    showPopup('Error', error.response?.data?.error || 'Failed to fetch products', 'error')
                    products.value = []
                }
            } catch (error) {
                showPopup('Error', error.response?.data?.error || 'Failed to fetch products', 'error')
                products.value = []
            }
        }
        
        const addToCart = async (product) => {
            try {
                await axios.post('/cart', {
                    product_id: product.id,
                    quantity: 1
                }, { withCredentials: true });
                showPopup('Success', 'Product added to cart successfully!', 'success')
                window.dispatchEvent(new Event('cart-updated'));
            } catch (error) {
                showPopup('Error', error.response?.data?.error || 'Failed to add product to cart', 'error')
            }
        }

        const searchProducts = () => {
            currentPage.value = 1
        }

        const filterProducts = () => {
            currentPage.value = 1
        }

        const changePage = (page) => {
            currentPage.value = page
        }

        onMounted(() => {
            fetchProducts()
        })

        return {
            products,
            searchQuery,
            selectedCategory,
            minPrice,
            maxPrice,
            currentPage,
            categories,
            filteredProducts: paginatedProducts,
            totalPages,
            addToCart,
            searchProducts,
            filterProducts,
            changePage,
            popupShow,
            popupTitle,
            popupMessage,
            popupType,
        }
    },
    methods: {
        goToProductDetails(productId) {
            this.$router.push({ name: 'product.details', params: { id: productId } });
        },
    },
}
</script>
