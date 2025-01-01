<script setup lang="ts">
import {useCartStore} from "@/stores/useCartStore.ts";
import CartItemList from "@/components/cart/CartItemList.vue";

const cartStore = useCartStore();
</script>

<template>
  <div class="mx-auto px-8 py-4 space-y-6">
    <h5 class="text-xl font-medium text-gray-900 dark:text-white">Edit Cart</h5>
    <CartItemList/>
    <div class="flex items-center justify-between">
      <p class="text-3xl font-bold text-gray-900 dark:text-white">Total: {{ cartStore.totalAmount.toFixed(2) }}</p>
      <button
        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
      >
        Add to cart
      </button>
    </div>
  </div>
</template>
