<template>
    <div class="min-h-screen p-8 bg-base-300">
        <h1 class="mb-8 text-3xl font-bold">Your Cart</h1>
        <div v-if="cartItems.length === 0" class="text-xl">
            Your cart is empty.
        </div>
        <div v-else class="grid gap-8 md:grid-cols-3">
            <div class="md:col-span-2">
            <div v-for="item in cartItems" :key="item.id" class="mb-4 shadow-xl card bg-base-100">
                <div class="card-body">
                <div class="flex items-center space-x-4">
                    <img :src="'/storage/' + item.product.img_path" :alt="item.product.name" class="object-cover w-24 h-24 rounded-md">
                    <div>
                    <button class="card-title" @click="goToProductDetails(item.product.id)">{{ item.product.name }}</button>
                    <p>Price: ${{ formatPrice(item.product.product_detail?.price) }}</p>
                    <div class="flex items-center mt-2 space-x-2">
                        <button class="btn btn-square btn-sm" @click="updateQuantity(item, item.quantity - 1)">-</button>
                        <input type="number" v-model.number="item.quantity" class="w-16 input input-bordered" min="1" :max="item.product.product_detail?.stock" @change="updateQuantity(item, item.quantity)" />
                        <button class="btn btn-square btn-sm" @click="updateQuantity(item, item.quantity + 1)">+</button>
                    </div>
                    </div>
                </div>
                <button class="mt-4 btn btn-error btn-sm" @click="removeFromCart(item)">Remove</button>
                </div>
            </div>
            </div>
            
            <div class="p-6 shadow-xl card bg-base-100">
            <h2 class="mb-4 text-2xl font-semibold">Order Summary</h2>
            <div class="mb-4 text-xl">
                Total: ${{ formatPrice(cartTotal) }}
            </div>
            <button class="w-full btn btn-primary" @click="checkout">Proceed to Checkout</button>
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
        const cartItems = ref([])
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

        const fetchCart = async () => {
            try {
                const response = await axios.get('/cart');
                cartItems.value = response.data;
            } catch (error) {
                console.error('Error fetching cart:', error);
            }
            console.log(cartItems.value)
        }

        const formatPrice = (price) => {
            return new Intl.NumberFormat('en-US', { minimumFractionDigits: 0 }).format(price || 0)
        }

        const updateQuantity = async (item, newQuantity) => {
            if (newQuantity < 1) {
                newQuantity = 1;
            } else if (newQuantity > item.product.product_detail?.stock) {
                newQuantity = item.product.product_detail.stock;
            }
            try {
                await axios.put(`/cart/${item.product_id}`, { quantity: newQuantity }, { withCredentials: true })
                item.quantity = newQuantity
                updateCartCount()
                showPopup('Success', 'Quantity updated successfully', 'success')
            } catch (error) {
                if (error.response && error.response.status === 400) {
                showPopup('Error', error.response.data.message, 'error')
                } else {
                console.error('Error updating quantity:', error)
                showPopup('Error', 'Failed to update quantity. Please try again.', 'error')
                }
            }
        }

        const removeFromCart = async (item) => {
            try {
                await axios.delete(`/cart/${item.product_id}`, { withCredentials: true })
                cartItems.value = cartItems.value.filter(cartItem => cartItem.product_id !== item.product_id)
                updateCartCount()
                showPopup('Success', 'Item removed from cart', 'success')
            } catch (error) {
                console.error('Error removing item from cart:', error)
                showPopup('Error', 'Failed to remove item from cart. Please try again.', 'error')
            }
        }

        const updateCartCount = async () => {
            try {
                const response = await axios.get('/cart', { withCredentials: true });
                if (Array.isArray(response.data)) {
                    const cartCount = response.data.reduce((total, item) => total + item.quantity, 0);
                    document.getElementById('cart-count').textContent = cartCount;
                } else {
                    console.error('Expected an array from the cart API:', response.data);
                }
            } catch (error) {
                console.error('Error updating cart count:', error);
            }
        };

        const cartTotal = computed(() => {
            return cartItems.value.reduce((total, item) => {
                    return total + (item.product.product_detail?.price * item.quantity)
            }, 0)
        })
        
        const checkout = () => {
            alert('Checkout functionality not implemented yet.')
        }

        onMounted(() => {
            fetchCart(),
            updateCartCount()
        })

        return {
            cartItems,
            formatPrice,
            updateQuantity,
            removeFromCart,
            cartTotal,
            checkout,
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