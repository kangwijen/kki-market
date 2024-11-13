<template>
    <div class="min-h-screen p-4 sm:p-8 bg-base-300">
        <div class="mb-6 space-y-2">
            <h1 class="text-3xl font-bold">Products</h1>
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
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">        
                    <div v-for="product in filteredProducts" :key="product.id" class="shadow-xl card bg-base-100">
                        <figure class="relative">
                            <img :src="'/storage/' + product.img_path" :alt="product.name" class="object-cover w-full h-48" />
                            <span v-if="product.discount" class="absolute px-2 py-1 text-xs text-white rounded-full top-2 left-2 bg-primary">{{ product.discount }}% Off</span>
                        </figure>
                        <div class="card-body">
                            <h3 class="card-title">{{ product.name }}</h3>
                            <p class="text-lg text-primary">${{ product.product_detail?.price ?? 'N/A' }}</p>
                            <div class="flex flex-col space-y-2 sm:flex-row sm:justify-between sm:space-y-0">
                                <button v-if="product && product.id" class="btn btn-secondary" @click="goToProductDetails(product.id)">
                                    Details
                                </button>
                                <button class="btn btn-primary" @click="addToCart(product)">Add to Cart</button>
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
                console.error('Products is not an array:', products.value);
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
                    console.error('Expected an array of products:', response.data)
                    products.value = []
                }
            } catch (error) {
                console.error('Error fetching products:', error)
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
                if (error.response && error.response.status === 400) {
                    showPopup('Info', error.response.data.message, 'info')
                } else {
                    console.error('Error adding to cart:', error)
                    const message = error.response.data.message || 'An error occurred while adding to cart'
                    showPopup('Error', message, 'error')
                }
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
