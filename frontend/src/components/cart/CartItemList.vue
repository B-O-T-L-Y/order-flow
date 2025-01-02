<script setup lang="ts">
import {useCartStore} from "@/stores/useCartStore.ts";
import router from "@/router";
import {onUnmounted, ref} from "vue";

const cartStore = useCartStore();

const props = defineProps({
  isCheckout: Boolean,
  onCancel: Function,
});

const success = ref<boolean>(false);
const countdown = ref<number>(10);
let countdownInterval: ReturnType<typeof setInterval> | null = null;

const startCountdown = () => {
  countdownInterval = setInterval(() => {
    if (countdown.value > 0) {
      countdown.value--;
    } else {
      clearInterval(countdownInterval!);
      success.value = false;
      router.push({path: '/orders', replace: true});
    }
  }, 1000);
};

const increaseQuantity = (productId: number) => {
  const item = cartStore.cart.find(item => item.product.id === productId);

  if (item) {
    cartStore.updateQuantity(productId, item.quantity + 1);
  }
};

const decreaseQuantity = (productId: number) => {
  const item = cartStore.cart.find(item => item.product.id === productId);

  if (item && item.quantity > 1) {
    cartStore.updateQuantity(productId, item.quantity - 1);
  } else if (item) {
    cartStore.removeFromCart(productId);
  }
};

const removeItem = (productId: number) => {
  cartStore.removeFromCart(productId);
};

const goToProducts = async () => {
  if (props.isCheckout) await props.onCancel();

  await router.push({path: '/', replace: true});
};

const goToOrders = async () => {
  if (props.isCheckout) await props.onCancel();

  await router.push({path: '/orders', replace: true});
};

const checkout = async () => {
  if (props.isCheckout) {
    await props.onCancel();
    await router.push({path: '/checkout', replace: true});

    return;
  }

  const {error, statusCode} = await cartStore.checkout();

  if (statusCode.value === 401) {
    await router.push({path: '/login', replace: true});

    return;
  }

  if (statusCode.value === 201) {
    success.value = true;
    await cartStore.clearCart();

    startCountdown();
  } else {
    console.error('Failed to checkout', error.value);
  }
};

onUnmounted(() => clearInterval(countdownInterval!));
</script>

<template>
  <transition name="fade">
    <div
      v-if="success"
      class="flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800" role="alert"
    >
      <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
          <path
            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
        </svg>
        <span class="sr-only">Check icon</span>
      </div>
      <div class="ms-3 text-sm font-normal">
        Order created successfully!<br>Redirecting to Orders in <span class="text-gray-700 font-bold dark:text-gray-400">{{ countdown }}</span> seconds...
      </div>
    </div>
  </transition>
  <template v-if="cartStore.cart.length > 0">
    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
      <li
        v-for="item in cartStore.cart"
        :key="item.product.id"
        class="pb-3 sm:pb-4"
      >
        <div class="flex items-center space-x-4 rtl:space-x-reverse">
          <div class="flex-shrink-0">
            <img class="w-8 h-8 rounded-full" src="https://flowbite.com/docs/images/people/profile-picture-1.jpg" alt="Neil image">
          </div>
          <div class="flex w-full min-w-0">
            <p class="text-sm font-medium text-gray-900 truncate dark:text-white">{{ item.product.name }}</p>
          </div>
          <div class="px-6 py-4">
            <div class="flex items-center">
              <button
                @click="decreaseQuantity(item.product.id)"
                type="button"
                class="inline-flex items-center justify-center p-1 me-3 text-sm font-medium h-6 w-6 text-gray-500 bg-white border border-gray-300 rounded-full focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
              >
                <span class="sr-only">Quantity button</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16"/>
                </svg>
              </button>
              <div>
                <input
                  v-model="item.quantity"
                  type="number" id="first_product"
                  min="1"
                  class="[appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none bg-gray-50 w-14 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-2.5 py-1 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  required
                />
              </div>
              <button
                @click="increaseQuantity(item.product.id)"
                type="button"
                class="inline-flex items-center justify-center h-6 w-6 p-1 ms-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-full focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
              >
                <span class="sr-only">Quantity button</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16"/>
                </svg>
              </button>
            </div>
          </div>
          <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">{{ item.product.price }}</div>
          <button
            @click="removeItem(item.product.id)"
            type="button"
            class="inline-flex flex-shrink-0 items-center justify-center p-1 me-3 text-sm font-medium h-6 w-6 text-white bg-red-700 border border-red-700 rounded-full focus:outline-none hover:bg-red-800 focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:border-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
          >
            <span class="sr-only">Quantity button</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16"/>
            </svg>
          </button>
        </div>
      </li>
    </ul>
    <div class="flex items-center justify-between">
      <p class="text-2xl font-bold text-gray-900 me-auto dark:text-white">Total: {{ cartStore.totalAmount.toFixed(2) }}</p>
      <button
        @click="cartStore.clearCart"
        type="button"
        class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-4 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
      >
        Clear Cart
      </button>
      <button
        @click="checkout"
        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
      >
        Create Order
      </button>
    </div>
  </template>

  <template v-else>
    <div class="flex items-center justify-between space-x-4">
      <p class="text-center text-lg font-semibold text-gray-900 me-auto dark:text-white">Cart is empty</p>
      <button
        @click="goToProducts"
        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
      >
        Go to Products
      </button>
      <button
        @click="goToOrders"
        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
      >
        Go to Orders
      </button>
    </div>
  </template>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
