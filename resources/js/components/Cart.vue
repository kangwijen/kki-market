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
                            <h2 class="card-title">{{ item.product.name }}</h2>
                            <p>Price: ${{ formatPrice(item.product.product_detail?.price) }}</p>
                            <div class="flex items-center space-x-2">
                                <button class="btn btn-square btn-sm" @click="updateQuantity(item, item.quantity - 1)">-</button>
                                <input type="number" v-model.number="item.quantity" class="w-16 input input-bordered" min="1" :max="item.product.product_detail?.stock" @change="updateQuantity(item, item.quantity)" />
                                <button class="btn btn-square btn-sm" @click="updateQuantity(item, item.quantity + 1)">+</button>
                            </div>
                            <button class="btn btn-error btn-sm" @click="removeFromCart(item)">Remove</button>
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
    </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'

export default {
    setup() {
        const cartItems = ref([])
        
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
            if (newQuantity < 1 || newQuantity > item.product.product_detail?.stock) return
            try {
                    await axios.put(`/cart/${item.product_id}`, { quantity: newQuantity }, { withCredentials: true })
                    item.quantity = newQuantity
                    updateCartCount()
            } catch (error) {
                    console.error('Error updating quantity:', error)
            }
        }

        const removeFromCart = async (item) => {
            try {
                    await axios.delete(`/cart/${item.product_id}`, { withCredentials: true })
                    cartItems.value = cartItems.value.filter(cartItem => cartItem.product_id !== item.product_id)
                    updateCartCount()
            } catch (error) {
                    console.error('Error removing item from cart:', error)
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
            checkout
        }
    }
}
</script>