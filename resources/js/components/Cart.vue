<template>
    <div class="min-h-screen p-8 bg-base-300">
        <div class="mb-4 flex items-center">
                <a @click="$router.back()" class="btn btn-secondary rounded-full items-center justify-center">⮜</a>
                <h1 class="ml-5 text-3xl font-bold">Your Cart</h1>
        </div>

        <div v-if="cartItems.length === 0" class="grid gap-8 md:grid-cols-3">
                <div class="mt-4 md:col-span-2">Your cart is empty. Add items to your cart to see them displayed here. </div>
                <div class="p-6 space-y-6 shadow-xl card bg-base-100">
                <div>
                    <h2 class="mb-4 text-2xl font-semibold">Order Summary</h2>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span>Subtotal:</span>
                            <span>${{ formatPrice(cartTotal) }}</span>
                        </div>
                        <div class="flex justify-between font-bold">
                            <span>Total:</span>
                            <span>${{ formatPrice(cartTotal) }}</span>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t">
                    <div class="flex justify-between mb-2">
                        <span class="font-semibold">Your Balance:</span>
                        <span class="font-semibold" :class="{'text-error': insufficientFunds}">${{ formatPrice(userBalance) }}</span>
                    </div>
                    <div v-if="insufficientFunds" class="mb-4 text-sm text-error">
                        Insufficient balance. Please <router-link to="/profile" class="text-primary">top up</router-link> your account.
                    </div>
                </div>

                <button 
                    class="w-full btn btn-primary" 
                    @click="initiateCheckout"
                    :disabled="isProcessing || cartItems.length === 0 || insufficientFunds"
                >
                    {{ isProcessing ? 'Processing...' : 'Purchase Now' }}
                </button>
            </div>
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
            
            <div class="p-6 space-y-6 shadow-xl card bg-base-100">
                <div>
                    <h2 class="mb-4 text-2xl font-semibold">Order Summary</h2>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span>Subtotal:</span>
                            <span>${{ formatPrice(cartTotal) }}</span>
                        </div>
                        <div class="flex justify-between font-bold">
                            <span>Total:</span>
                            <span>${{ formatPrice(cartTotal) }}</span>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t">
                    <div class="flex justify-between mb-2">
                        <span class="font-semibold">Your Balance:</span>
                        <span class="font-semibold" :class="{'text-error': insufficientFunds}">${{ formatPrice(userBalance) }}</span>
                    </div>
                    <div v-if="insufficientFunds" class="mb-4 text-sm text-error">
                        Insufficient balance. Please <router-link to="/profile" class="text-primary">top up</router-link> your account.
                    </div>
                </div>

                <button 
                    class="w-full btn btn-primary" 
                    @click="initiateCheckout"
                    :disabled="isProcessing || cartItems.length === 0 || insufficientFunds"
                >
                    {{ isProcessing ? 'Processing...' : 'Purchase Now' }}
                </button>
            </div>
        </div>

        <!-- Confirmation Modal -->
        <dialog class="modal" :class="{ 'modal-open': showConfirmationModal }">
            <div class="modal-box">
                <h3 class="text-lg font-bold">Confirm Purchase</h3>
                <div class="py-4">
                    <div v-if="checkoutStep === 'confirmation'" class="space-y-4">
                        <p>Are you sure you want to purchase these items?</p>
                        <div class="p-4 bg-base-200 rounded-box">
                            <div class="flex justify-between mb-2">
                                <span>Total Amount:</span>
                                <span>${{ formatPrice(cartTotal) }}</span>
                            </div>
                            <div class="flex justify-between font-bold">
                                <span>Remaining Balance:</span>
                                <span>${{ formatPrice(userBalance - cartTotal) }}</span>
                            </div>
                        </div>
                    </div>
                    <div v-else-if="checkoutStep === 'processing'" class="text-center">
                        <div class="loading loading-spinner loading-lg"></div>
                        <p class="mt-4">Processing your purchase...</p>
                    </div>
                    <div v-else-if="checkoutStep === 'success'" class="text-center">
                        <div class="flex justify-center mb-4 text-success">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold">Purchase Successful!</h4>
                        <p class="mt-2">Your items have been sent to you <router-link to="/profile" class="text-primary">here</router-link>.</p>
                        <div class="mt-4 text-sm text-base-content/70">
                            New Balance: ${{ formatPrice(userBalance - cartTotal) }}
                        </div>
                    </div>
                </div>
                <div class="modal-action">
                    <button 
                        v-if="checkoutStep === 'confirmation'"
                        class="btn btn-primary"
                        @click="processPayment"
                    >
                        Confirm Purchase
                    </button>
                    <button 
                        v-if="checkoutStep === 'success'"
                        class="btn btn-primary"
                        @click="finishCheckout"
                    >
                        Done
                    </button>
                    <button 
                        v-if="checkoutStep === 'confirmation'"
                        class="btn"
                        @click="closeConfirmationModal"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        </dialog>

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
        const userBalance = ref(0)
        const popupShow = ref(false)
        const popupTitle = ref('')
        const popupMessage = ref('')
        const popupType = ref('info')
        const isProcessing = ref(false)
        const showConfirmationModal = ref(false)
        const checkoutStep = ref('confirmation')

        const showPopup = (title, message, type = 'info') => {
            popupTitle.value = title
            popupMessage.value = message
            popupType.value = type
            popupShow.value = true
        }

        const fetchUserBalance = async () => {
            try {
                const response = await axios.get('/user-details/balance');
                userBalance.value = response.data.balance;
            } catch (error) {
                console.error('Error fetching user balance:', error);
                showPopup('Error', 'Failed to load balance', 'error');
            }
        }

        const fetchCart = async () => {
            try {
                const response = await axios.get('/cart');
                cartItems.value = response.data;
            } catch (error) {
                console.error('Error fetching cart:', error);
                showPopup('Error', 'Failed to load cart items', 'error');
            }
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
                showPopup('Success', 'Quantity updated successfully', 'success')
                window.dispatchEvent(new Event('cart-updated'));
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
                showPopup('Success', 'Item removed from cart', 'success')
                window.dispatchEvent(new Event('cart-updated'));
            } catch (error) {
                console.error('Error removing item from cart:', error)
                showPopup('Error', 'Failed to remove item from cart. Please try again.', 'error')
            }
        }

        const cartTotal = computed(() => {
            return cartItems.value.reduce((total, item) => {
                return total + (item.product.product_detail?.price * item.quantity)
            }, 0)
        })

        const insufficientFunds = computed(() => {
            return userBalance.value < cartTotal.value
        })

        const initiateCheckout = () => {
            checkoutStep.value = 'confirmation'
            showConfirmationModal.value = true
        }

        const processPayment = async () => {
            checkoutStep.value = 'processing'
            isProcessing.value = true

            try {
                const response = await axios.post('/cart/checkout', {
                    items: cartItems.value.map(item => ({
                        product_id: item.product_id,
                        quantity: item.quantity
                    }))
                })

                userBalance.value -= cartTotal.value

                cartItems.value = []
                window.dispatchEvent(new Event('cart-updated'))

                checkoutStep.value = 'success'
            } catch (error) {
                console.error('Purchase error:', error)
                const errorMessage = error.response?.data?.message || 'Purchase failed. Please try again.'
                showPopup('Error', errorMessage, 'error')
                checkoutStep.value = 'confirmation'
            } finally {
                isProcessing.value = false
            }
        }

        const closeConfirmationModal = () => {
            showConfirmationModal.value = false
            checkoutStep.value = 'confirmation'
        }

        const finishCheckout = () => {
            closeConfirmationModal()
        }

        onMounted(() => {
            fetchCart()
            fetchUserBalance()
        })

        return {
            cartItems,
            userBalance,
            cartTotal,
            insufficientFunds,
            popupShow,
            popupTitle,
            popupMessage,
            popupType,
            isProcessing,
            showConfirmationModal,
            checkoutStep,
            formatPrice,
            updateQuantity,
            removeFromCart,
            initiateCheckout,
            processPayment,
            closeConfirmationModal,
            finishCheckout
        }
    },
    methods: {
        goToProductDetails(productId) {
            this.$router.push({ name: 'product.details', params: { id: productId } });
        },
    },
}
</script>