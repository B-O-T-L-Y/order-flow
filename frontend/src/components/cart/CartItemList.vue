<script setup lang="ts">
import {useCartStore} from "@/stores/useCartStore.ts";

const cartStore = useCartStore();

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
</script>

<template>
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
                class="bg-gray-50 w-14 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-2.5 py-1 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
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
</template>
